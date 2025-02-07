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
        Schema::create('short_links', function (Blueprint $table) {
            $table->uuid('id')->primary()->nullable(false);
            $table->foreignUuid('user')->nullable(false);
            $table->text('url')->nullable(false);
            $table->text('short')->nullable(false)->unique();
            $table->boolean('isDelete')->default(false);
            $table->timestamps();

            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
