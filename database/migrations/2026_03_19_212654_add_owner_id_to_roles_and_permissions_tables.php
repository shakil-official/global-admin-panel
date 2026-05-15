<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable()->after('id');
                $table->index('owner_id');
            }
        });

        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'owner_id')) {
                $table->unsignedBigInteger('owner_id')->nullable()->after('id');
                $table->index('owner_id');
            }
            if (!Schema::hasColumn('permissions', 'group_name')) {
                $table->string('group_name')->nullable()->after('name');
                $table->index('group_name');
            }
        });

        // Set default owner_id for existing records
        \DB::statement('UPDATE roles SET owner_id = 1 WHERE owner_id IS NULL');
        \DB::statement('UPDATE permissions SET owner_id = 1 WHERE owner_id IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'owner_id')) {
                $table->dropIndex(['owner_id']);
                $table->dropColumn('owner_id');
            }
        });

        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'owner_id')) {
                $table->dropIndex(['owner_id']);
            }
            if (Schema::hasColumn('permissions', 'group_name')) {
                $table->dropIndex(['group_name']);
            }
            if (Schema::hasColumn('permissions', 'owner_id') || Schema::hasColumn('permissions', 'group_name')) {
                $table->dropColumn(['owner_id', 'group_name']);
            }
        });
    }
};
