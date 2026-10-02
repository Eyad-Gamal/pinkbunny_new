<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix: Spatie Permission tables were created with bigint model_id,
 * but the User model uses UUID (varchar 36) primary keys.
 * This migration alters model_has_roles and model_has_permissions
 * to use varchar(36) for model_id so UUID values fit.
 *
 * DB-agnostic: works on both MySQL and SQLite.
 * Uses Laravel Schema builder instead of raw MySQL SQL.
 */
return new class extends Migration
{
    public function up(): void
    {
        // On SQLite, foreign key enforcement must be disabled before structural changes
        Schema::disableForeignKeyConstraints();

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_id', 36)->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->string('model_id', 36)->change();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};

