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
        Schema::create('connections', function (Blueprint $table) {
            $table->char('user_id', 36);
            $table->char('connection_id', 36);
            $table->enum('status', ['P', 'A', 'R'])->default('P')->comment('P : Pending , A : Accepted , R : Rejected');
            $table->timestamp('request_send_date');
            $table->timestamp('request_accepted_date')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('connection_id')->references('id')->on('users')->onDelete('cascade');

            // Composite unique constraint to prevent duplicate and vice-versa connections
            $table->unique(['user_id', 'connection_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
