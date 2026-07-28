<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('programs', 'outcomes')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->text('outcomes')->nullable()->after('summary');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('programs', 'outcomes')) {
            Schema::table('programs', function (Blueprint $table) {
                $table->dropColumn('outcomes');
            });
        }
    }
};
