<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix the activity_log table to support UUID morphs.
 *
 * Safely changes subject_id and causer_id to string(36) for UUID support.
 * DB-agnostic: works on both MySQL and SQLite.
 *
 * NOTE: try/catch must wrap the ENTIRE Schema::table() call — not just
 * $t->dropIndex() inside the closure — because Blueprint collects commands
 * inside the closure and executes them all at once via build() outside it.
 */
return new class extends Migration
{
    public function up(): void
    {
        $table = config('activitylog.table_name', 'activity_log');
        $conn  = config('activitylog.database_connection');

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

        // Drop subject index — try/catch wraps Schema::table() (not just the closure)
        foreach ($subjectIndexNames as $name) {
            try {
                Schema::connection($conn)->table($table, function (Blueprint $t) use ($name) {
                    $t->dropIndex($name);
                });
                break; // succeeded — stop trying
            } catch (\Throwable) {
                // Index not found under this name — try the next
            }
        }

        // Drop causer index — try/catch wraps Schema::table() (not just the closure)
        foreach ($causerIndexNames as $name) {
            try {
                Schema::connection($conn)->table($table, function (Blueprint $t) use ($name) {
                    $t->dropIndex($name);
                });
                break; // succeeded — stop trying
            } catch (\Throwable) {
                // Index not found under this name — try the next
            }
        }

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

