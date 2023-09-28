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
        //
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->integer('size');
            $table->integer('parent_folder')->nullable();
            $table->string('path', 255);
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);
            $table->string('link_share', 255)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('updated_Ts')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
