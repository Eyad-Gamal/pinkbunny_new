<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix: Spatie Permission tables were created with bigint model_id,
 * but the User model uses UUID (varchar 36) primary keys.
 * This migration alters model_has_roles and model_has_permissions
 * to use varchar(36) for model_id so UUID values fit.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop primary keys first (MySQL requires this before altering PK columns)
        DB::statement('ALTER TABLE model_has_roles DROP PRIMARY KEY');
        DB::statement('ALTER TABLE model_has_permissions DROP PRIMARY KEY');

        // Drop the composite indexes that reference model_id
        DB::statement('ALTER TABLE model_has_roles DROP INDEX model_has_roles_model_id_model_type_index');
        DB::statement('ALTER TABLE model_has_permissions DROP INDEX model_has_permissions_model_id_model_type_index');

        // Change model_id from bigint unsigned → varchar(36) to support UUIDs
        DB::statement('ALTER TABLE model_has_roles MODIFY model_id VARCHAR(36) NOT NULL');
        DB::statement('ALTER TABLE model_has_permissions MODIFY model_id VARCHAR(36) NOT NULL');

        // Re-add primary keys
        DB::statement('ALTER TABLE model_has_roles ADD PRIMARY KEY (role_id, model_id, model_type)');
        DB::statement('ALTER TABLE model_has_permissions ADD PRIMARY KEY (permission_id, model_id, model_type)');

        // Re-add indexes
        DB::statement('ALTER TABLE model_has_roles ADD INDEX model_has_roles_model_id_model_type_index (model_id, model_type)');
        DB::statement('ALTER TABLE model_has_permissions ADD INDEX model_has_permissions_model_id_model_type_index (model_id, model_type)');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement('ALTER TABLE model_has_roles DROP PRIMARY KEY');
        DB::statement('ALTER TABLE model_has_permissions DROP PRIMARY KEY');
        DB::statement('ALTER TABLE model_has_roles DROP INDEX model_has_roles_model_id_model_type_index');
        DB::statement('ALTER TABLE model_has_permissions DROP INDEX model_has_permissions_model_id_model_type_index');

        DB::statement('ALTER TABLE model_has_roles MODIFY model_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE model_has_permissions MODIFY model_id BIGINT UNSIGNED NOT NULL');

        DB::statement('ALTER TABLE model_has_roles ADD PRIMARY KEY (role_id, model_id, model_type)');
        DB::statement('ALTER TABLE model_has_permissions ADD PRIMARY KEY (permission_id, model_id, model_type)');
        DB::statement('ALTER TABLE model_has_roles ADD INDEX model_has_roles_model_id_model_type_index (model_id, model_type)');
        DB::statement('ALTER TABLE model_has_permissions ADD INDEX model_has_permissions_model_id_model_type_index (model_id, model_type)');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
