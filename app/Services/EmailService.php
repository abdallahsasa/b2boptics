<?php

namespace App\Services;

use App\Mail\FactoryApprovedMail;
use App\Mail\ReminderMail;
use App\Mail\WelcomeUserMail;
use App\Models\EmailLog;
use App\Models\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public static function resolveUserLocale(?User $user, ?Factory $factory = null, ?string $overrideLocale = null): string
    {
        $supported = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];
        if ($overrideLocale && in_array($overrideLocale, $supported, true)) {
            return $overrideLocale;
        }

        $factoryLang = $factory?->language ?: $user?->factory?->language;
        if ($factoryLang && in_array($factoryLang, $supported, true)) {
            return $factoryLang;
        }

        $appLang = app()->getLocale();
        return in_array($appLang, $supported, true) ? $appLang : 'en';
    }

    public static function sendWelcome(User $user, ?string $accountType = null, ?string $locale = null): bool
    {
        $factory = $user->factory;
        $type = $accountType ?: ($user->hasRole(['factory_owner', 'factory_staff']) || $factory ? 'factory' : 'buyer');
        $mailLocale = self::resolveUserLocale($user, $factory, $locale);
        $mailable = new WelcomeUserMail($user, $type, $mailLocale);
        $subject = $mailable->envelope()->subject;

        try {
            Mail::to($user->email)->send($mailable);

            EmailLog::record([
                'user_id' => $user->id,
                'factory_id' => $factory?->id,
                'recipient_email' => $user->email,
                'recipient_name' => $user->name,
                'email_type' => 'welcome',
                'locale' => $mailLocale,
                'subject' => $subject,
                'status' => 'sent',
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error("EmailService::sendWelcome failed for user [{$user->id}]: " . $e->getMessage());

            EmailLog::record([
                'user_id' => $user->id,
                'factory_id' => $factory?->id,
                'recipient_email' => $user->email,
                'recipient_name' => $user->name,
                'email_type' => 'welcome',
                'locale' => $mailLocale,
                'subject' => $subject,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public static function sendFactoryApproved(Factory $factory, ?string $locale = null): bool
    {
        $recipientEmail = !empty($factory->email) ? trim($factory->email) : trim((string) $factory->owner?->email);

        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            Log::warning("Factory [{$factory->id}] approved, but no valid email found.");
            return false;
        }

        $mailLocale = self::resolveUserLocale($factory->owner, $factory, $locale);
        if ($locale && $factory->language !== $mailLocale) {
            $factory->language = $mailLocale;
        }

        $mailable = new FactoryApprovedMail($factory);
        $subject = $mailable->envelope()->subject;
        $recipientName = $factory->getTranslation('official_name', $mailLocale) ?: ($factory->owner?->name ?? 'Supplier');

        try {
            $mail = Mail::to($recipientEmail);

            if (
                $factory->owner?->email &&
                filter_var($factory->owner->email, FILTER_VALIDATE_EMAIL) &&
                strtolower(trim($factory->owner->email)) !== strtolower($recipientEmail)
            ) {
                $mail->cc(trim($factory->owner->email));
            }

            $mail->send($mailable);

            EmailLog::record([
                'user_id' => $factory->user_id,
                'factory_id' => $factory->id,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'email_type' => 'factory_approved',
                'locale' => $mailLocale,
                'subject' => $subject,
                'status' => 'sent',
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error("EmailService::sendFactoryApproved failed for factory [{$factory->id}]: " . $e->getMessage());

            EmailLog::record([
                'user_id' => $factory->user_id,
                'factory_id' => $factory->id,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'email_type' => 'factory_approved',
                'locale' => $mailLocale,
                'subject' => $subject,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public static function sendReminder(
        ?User $user = null,
        ?Factory $factory = null,
        string $template = 'complete_profile',
        ?string $locale = null,
        ?string $customSubject = null,
        ?string $customBody = null
    ): bool {
        $targetUser = $user ?: $factory?->owner;
        $targetFactory = $factory ?: $targetUser?->factory;

        $recipientEmail = !empty($targetFactory?->email)
            ? trim($targetFactory->email)
            : trim((string) $targetUser?->email);

        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $mailLocale = self::resolveUserLocale($targetUser, $targetFactory, $locale);
        $recipientName = $targetFactory
            ? ($targetFactory->getTranslation('official_name', $mailLocale) ?: $targetFactory->official_name)
            : ($targetUser?->name ?? 'Partner');

        $isSupplier = (bool) $targetFactory || ($targetUser?->hasRole(['factory_owner', 'factory_staff']) ?? false);
        $mailable = new ReminderMail(
            recipientName: $recipientName,
            reminderTemplate: $template,
            locale: $mailLocale,
            customSubject: $customSubject,
            customBody: $customBody,
            isSupplier: $isSupplier
        );
        $subject = $mailable->resolvedSubject();
        $emailType = $template === 'custom' ? 'custom' : 'reminder';

        try {
            $mail = Mail::to($recipientEmail);

            if (
                $targetUser?->email &&
                filter_var($targetUser->email, FILTER_VALIDATE_EMAIL) &&
                strtolower(trim($targetUser->email)) !== strtolower($recipientEmail)
            ) {
                $mail->cc(trim($targetUser->email));
            }

            $mail->send($mailable);

            EmailLog::record([
                'user_id' => $targetUser?->id,
                'factory_id' => $targetFactory?->id,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'email_type' => $emailType,
                'locale' => $mailLocale,
                'subject' => $subject,
                'status' => 'sent',
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error("EmailService::sendReminder failed for {$recipientEmail}: " . $e->getMessage());

            EmailLog::record([
                'user_id' => $targetUser?->id,
                'factory_id' => $targetFactory?->id,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'email_type' => $emailType,
                'locale' => $mailLocale,
                'subject' => $subject,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public static function sendFromAdminForm(array $data): array
    {
        $emailType = $data['email_type'] ?? 'welcome';
        $audience = $data['audience'] ?? 'selected_users';
        $localeOverride = ($data['locale'] ?? 'auto') === 'auto' ? null : $data['locale'];
        $reminderTemplate = $data['reminder_template'] ?? 'complete_profile';
        $customSubject = $data['custom_subject'] ?? null;
        $customBody = $data['custom_body'] ?? null;

        $sent = 0;
        $failed = 0;

        $users = collect();
        $factories = collect();

        if ($audience === 'selected_users') {
            $users = User::with('factory')->whereIn('id', $data['user_ids'] ?? [])->get();
        } elseif ($audience === 'selected_factories') {
            $factories = Factory::with('owner')->whereIn('id', $data['factory_ids'] ?? [])->get();
        } elseif ($audience === 'pending_factories') {
            $factories = Factory::with('owner')->where('status', 'pending')->get();
        } elseif ($audience === 'approved_factories_no_products') {
            $factories = Factory::with('owner')->where('status', 'approved')->doesntHave('products')->get();
        } elseif ($audience === 'all_factories') {
            $factories = Factory::with('owner')->get();
        } elseif ($audience === 'all_buyers') {
            $users = User::whereHas('roles', fn ($q) => $q->where('name', 'buyer'))->get();
        }

        foreach ($factories as $factory) {
            $ok = match ($emailType) {
                'welcome' => $factory->owner ? self::sendWelcome($factory->owner, 'factory', $localeOverride) : false,
                'factory_approved' => self::sendFactoryApproved($factory, $localeOverride),
                'custom' => self::sendReminder($factory->owner, $factory, 'custom', $localeOverride, $customSubject, $customBody),
                default => self::sendReminder($factory->owner, $factory, $reminderTemplate, $localeOverride),
            };
            $ok ? $sent++ : $failed++;
        }

        foreach ($users as $user) {
            $ok = match ($emailType) {
                'welcome' => self::sendWelcome($user, null, $localeOverride),
                'factory_approved' => $user->factory ? self::sendFactoryApproved($user->factory, $localeOverride) : false,
                'custom' => self::sendReminder($user, $user->factory, 'custom', $localeOverride, $customSubject, $customBody),
                default => self::sendReminder($user, $user->factory, $reminderTemplate, $localeOverride),
            };
            $ok ? $sent++ : $failed++;
        }

        return ['sent' => $sent, 'failed' => $failed];
    }
}
