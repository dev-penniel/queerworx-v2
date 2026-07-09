<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('color');
            $table->string('cover_image_path')->nullable()->after('summary');
        });

        Schema::table('program_activities', function (Blueprint $table) {
            $table->time('activity_time')->nullable()->after('activity_date');
            $table->string('venue')->nullable()->after('activity_time');
            $table->string('status')->default('upcoming')->after('venue');
            $table->string('featured_image_path')->nullable()->after('status');
        });

        Schema::create('program_activity_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_activity_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_activity_media');

        Schema::table('program_activities', function (Blueprint $table) {
            $table->dropColumn(['activity_time', 'venue', 'status', 'featured_image_path']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['sort_order', 'cover_image_path']);
        });
    }
};
