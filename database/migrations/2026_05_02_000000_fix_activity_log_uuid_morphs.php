<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema, DB};

/**
 * Fix the activity_log table to support UUID morphs.
 *
 * Safely changes subject_id and causer_id to string(36) for UUID support.
 * Handles cases where indexes may or may not exist with various naming conventions.
 */
return new class extends Migration
{
    public function up(): void
    {
        $table = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection');

        // Get existing indexes on the table
        $existingIndexes = collect(DB::connection($connection)
            ->select("SHOW INDEX FROM `{$table}`"))
            ->pluck('Key_name')
            ->unique()
            ->toArray();

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

        // Drop existing subject index (whichever name it has)
        Schema::connection($connection)->table($table, function (Blueprint $t) use ($existingIndexes, $subjectIndexNames) {
            foreach ($subjectIndexNames as $name) {
                if (in_array($name, $existingIndexes)) {
                    $t->dropIndex($name);
                    break;
                }
            }
        });

        // Drop existing causer index (whichever name it has)
        Schema::connection($connection)->table($table, function (Blueprint $t) use ($existingIndexes, $causerIndexNames) {
            foreach ($causerIndexNames as $name) {
                if (in_array($name, $existingIndexes)) {
                    $t->dropIndex($name);
                    break;
                }
            }
        });

        // Change columns to string (UUID-compatible)
        Schema::connection($connection)->table($table, function (Blueprint $t) {
            $t->string('subject_id', 36)->nullable()->change();
            $t->string('causer_id', 36)->nullable()->change();
        });

        // Re-add indexes
        Schema::connection($connection)->table($table, function (Blueprint $t) {
            $t->index(['subject_id', 'subject_type']);
            $t->index(['causer_id', 'causer_type']);
        });
    }

    public function down(): void
    {
        // No rollback needed — once converted to UUID, no reason to go back
    }
};
