<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->string('icon')->nullable(); // optional CSS class or icon name
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->string('seo_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
