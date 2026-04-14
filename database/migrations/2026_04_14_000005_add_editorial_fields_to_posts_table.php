<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('hero_title')->nullable()->after('excerpt');
            $table->text('hero_summary')->nullable()->after('hero_title');
            $table->text('quick_answer')->nullable()->after('hero_summary');
            $table->string('cta_title')->nullable()->after('related_keywords');
            $table->text('cta_text')->nullable()->after('cta_title');
            $table->string('cta_button_text')->nullable()->after('cta_text');
            $table->string('cta_button_url')->nullable()->after('cta_button_text');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn([
                'hero_title',
                'hero_summary',
                'quick_answer',
                'cta_title',
                'cta_text',
                'cta_button_text',
                'cta_button_url',
            ]);
        });
    }
};
