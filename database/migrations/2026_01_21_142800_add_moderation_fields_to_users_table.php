<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false)->after('character_class_changed_at');
            $table->timestamp('banned_at')->nullable()->after('is_banned');
            $table->timestamp('banned_until')->nullable()->after('banned_at');
            $table->string('ban_reason')->nullable()->after('banned_until');
            $table->unsignedInteger('banned_by_user_id')->nullable()->after('ban_reason');
            $table->foreign('banned_by_user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['is_banned', 'banned_until']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['banned_by_user_id']);
            $table->dropIndex(['is_banned', 'banned_until']);
            $table->dropColumn([
                'is_banned',
                'banned_at',
                'banned_until',
                'ban_reason',
                'banned_by_user_id',
            ]);
        });
    }
};
