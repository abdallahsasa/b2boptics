<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
