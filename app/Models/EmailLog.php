<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmailLog extends Model
{
    protected $fillable = [
        'user_id',
        'factory_id',
        'sent_by',
        'recipient_email',
        'recipient_name',
        'email_type',
        'locale',
        'subject',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public static function typeOptions(): array
    {
        return [
            'welcome' => 'Welcome Email',
            'factory_approved' => 'Factory Approved',
            'reminder' => 'Reminder Email',
            'new_message' => 'New Inquiry Notification',
            'custom' => 'Custom Email',
        ];
    }

    public static function ensureTableExists(): void
    {
        try {
            if (!Schema::hasTable('email_logs')) {
                Schema::create('email_logs', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                    $table->foreignId('factory_id')->nullable()->constrained()->nullOnDelete();
                    $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
                    $table->string('recipient_email')->index();
                    $table->string('recipient_name')->nullable();
                    $table->string('email_type', 50)->default('custom')->index();
                    $table->string('locale', 10)->default('en');
                    $table->string('subject');
                    $table->string('status', 20)->default('sent')->index();
                    $table->text('error_message')->nullable();
                    $table->timestamp('sent_at')->nullable()->index();
                    $table->timestamps();
                });
            }
        } catch (\Throwable $e) {
            // Ignore if DB not ready
        }
    }

    public static function record(array $attributes): ?self
    {
        self::ensureTableExists();

        try {
            return self::create(array_merge([
                'sent_at' => now(),
                'sent_by' => auth()->check() ? auth()->id() : null,
            ], $attributes));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to write EmailLog: ' . $e->getMessage());
            return null;
        }
    }
}
