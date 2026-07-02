<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('phone')->nullable()->after('email');
            $table->string('interest')->nullable()->after('phone');
            $table->text('message')->nullable()->after('interest');
        });
    }

    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone', 'interest', 'message']);
        });
    }
};
