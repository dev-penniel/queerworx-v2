<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('team_members', 'bio')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->text('bio')->nullable()->after('role');
            });
        }

        if (! Schema::hasColumn('team_members', 'member_type')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->string('member_type')->default('team')->after('bio');
            });
        }
    }

    public function down(): void
    {
        // This repair migration intentionally keeps existing profile data intact on rollback.
    }
};
