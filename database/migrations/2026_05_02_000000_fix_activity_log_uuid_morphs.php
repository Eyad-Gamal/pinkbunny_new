<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix the activity_log table to support UUID morphs.
 *
 * The original Spatie migration created `subject_id` and `causer_id`
 * using `nullableMorphs()` which generates BIGINT columns.
 * Since ALL models in this app use UUIDs (HasUuids trait),
 * we need to change these columns to VARCHAR(36) to store UUIDs.
 */
return new class extends Migration
{
    public function up(): void
    {
        $table = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection');

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            // Drop old indexes first
            $table->dropIndex('subject_subject_id_subject_type_index');
            $table->dropIndex('causer_causer_id_causer_type_index');
        });

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            // Change integer morph columns to string (UUID-compatible)
            $table->string('subject_id', 36)->nullable()->change();
            $table->string('causer_id', 36)->nullable()->change();
        });

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            // Re-add indexes
            $table->index(['subject_id', 'subject_type'], 'subject_subject_id_subject_type_index');
            $table->index(['causer_id', 'causer_type'], 'causer_causer_id_causer_type_index');
        });
    }

    public function down(): void
    {
        $table = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection');

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            $table->dropIndex('subject_subject_id_subject_type_index');
            $table->dropIndex('causer_causer_id_causer_type_index');
        });

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->change();
            $table->unsignedBigInteger('causer_id')->nullable()->change();
        });

        Schema::connection($connection)->table($table, function (Blueprint $table) {
            $table->index(['subject_id', 'subject_type'], 'subject_subject_id_subject_type_index');
            $table->index(['causer_id', 'causer_type'], 'causer_causer_id_causer_type_index');
        });
    }
};
