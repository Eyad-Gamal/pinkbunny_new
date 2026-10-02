<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix the activity_log table to support UUID morphs.
 *
 * Safely changes subject_id and causer_id to string(36) for UUID support.
 * DB-agnostic: works on both MySQL and SQLite (no SHOW INDEX).
 */
return new class extends Migration
{
    public function up(): void
    {
        $table  = config('activitylog.table_name', 'activity_log');
        $conn   = config('activitylog.database_connection');

        // Possible index names for subject morph
        $subjectIndexNames = [
            'subject_subject_id_subject_type_index',
            'activity_log_subject_type_subject_id_index',
            'activity_log_subject_id_subject_type_index',
            'subject_type_subject_id_index',
        ];

        // Possible index names for causer morph
        $causerIndexNames = [
            'causer_causer_id_causer_type_index',
            'activity_log_causer_type_causer_id_index',
            'activity_log_causer_id_causer_type_index',
            'causer_type_causer_id_index',
        ];

        // Drop subject index — try each possible name, silently skip if not found
        Schema::connection($conn)->table($table, function (Blueprint $t) use ($subjectIndexNames) {
            foreach ($subjectIndexNames as $name) {
                try {
                    $t->dropIndex($name);
                    break;
                } catch (\Throwable) {
                    // Index doesn't exist under this name — try the next one
                }
            }
        });

        // Drop causer index — try each possible name, silently skip if not found
        Schema::connection($conn)->table($table, function (Blueprint $t) use ($causerIndexNames) {
            foreach ($causerIndexNames as $name) {
                try {
                    $t->dropIndex($name);
                    break;
                } catch (\Throwable) {
                    // Index doesn't exist under this name — try the next one
                }
            }
        });

        // Change columns to string(36) (UUID-compatible)
        Schema::connection($conn)->table($table, function (Blueprint $t) {
            $t->string('subject_id', 36)->nullable()->change();
            $t->string('causer_id', 36)->nullable()->change();
        });

        // Re-add indexes with consistent names
        Schema::connection($conn)->table($table, function (Blueprint $t) {
            $t->index(['subject_id', 'subject_type']);
            $t->index(['causer_id', 'causer_type']);
        });
    }

    public function down(): void
    {
        // No rollback needed — once converted to UUID, no reason to go back
    }
};
