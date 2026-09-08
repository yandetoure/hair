<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('icon');
        });

        // Galerie « Nos réalisations »
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');           // Tresses, Coiffure, Onglerie, Soins…
            $table->string('image');
            $table->string('format')->default('portrait'); // portrait|paysage
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        // Forfaits & rituels beauté
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('compare_price')->nullable();
            $table->unsignedSmallInteger('duration')->nullable(); // minutes
            $table->text('includes')->nullable();                 // une prestation par ligne
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        // Journal beauté
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Conseils');
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('reading_time')->default(3);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('gallery_items');
        Schema::table('service_categories', fn (Blueprint $table) => $table->dropColumn('image'));
    }
};
