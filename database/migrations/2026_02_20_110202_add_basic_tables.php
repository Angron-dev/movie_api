<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->string('poster_path', 500)->nullable();
            $table->string('backdrop_path', 500)->nullable();
            $table->date('release_date')->nullable();
            $table->float('vote_average')->default(0);
            $table->unsignedInteger('vote_count')->default(0);
            $table->float('popularity')->default(0);
            $table->timestamps();
        });

        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tmdb_id')->unique();
            $table->string('poster_path', 500)->nullable();
            $table->string('backdrop_path', 500)->nullable();
            $table->date('release_date')->nullable();
            $table->float('vote_average')->default(0);
            $table->unsignedInteger('vote_count')->default(0);
            $table->float('popularity')->default(0);
            $table->timestamps();
        });

        Schema::create('movie_genre', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['movie_id','genre_id']);
        });

        Schema::create('serie_genre', function (Blueprint $table) {
            $table->foreignId('serie_id')->constrained('series')->onDelete('cascade');
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['serie_id','genre_id']);
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
        });

        Schema::create('serie_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained('series')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->string('title');
            $table->text('overview')->nullable();
            $table->timestamps();
            $table->unique(['series_id', 'language_id']);
        });

        Schema::create('movie_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movies_id')->constrained('movies')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->string('title');
            $table->text('overview')->nullable();
            $table->timestamps();
            $table->unique(['movies_id', 'language_id']);
        });

        Schema::create('genre_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained('genres')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->string('title');
            $table->timestamps();
            $table->unique(['genre_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_translations');
        Schema::dropIfExists('movie_translations');
        Schema::dropIfExists('serie_translations');
        Schema::dropIfExists('movie_genre');
        Schema::dropIfExists('serie_genre');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('series');
        Schema::dropIfExists('genres');
    }
};
