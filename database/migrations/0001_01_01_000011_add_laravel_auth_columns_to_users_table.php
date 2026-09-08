<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = config('laravel-auth.users_table', 'users');

        Schema::table($table, function (Blueprint $table) {
            if (! Schema::hasColumn($table->getTable(), 'two_factor_secret')) {
                $table->text('two_factor_secret')->after('password')->nullable();
                $table->text('two_factor_recovery_codes')->after('two_factor_secret')->nullable();
                $table->timestamp('two_factor_confirmed_at')->after('two_factor_recovery_codes')->nullable();
            }

            if (! Schema::hasColumn($table->getTable(), 'banned_at')) {
                $table->timestamp('banned_at')->nullable();
                $table->string('banned_reason')->nullable();
            }
        });
    }

    public function down(): void
    {
        $table = config('laravel-auth.users_table', 'users');

        Schema::table($table, function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'banned_at',
                'banned_reason',
            ]);
        });
    }
};
