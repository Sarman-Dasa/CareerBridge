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
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('user_id');
            $table->string('title');
            $table->text('content');
            $table->enum('status', ['D', 'P'])->default('D')->comment('D = Draft, P = published');
            $table->enum('visibility', ['P', 'C'])->default('P')->comment('P = Public , C = Connection only');
            $table->enum('comment_control', ['A', 'C', 'N'])->default('A')->comment('A = Anyone , C = Connection only , N = No one');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
