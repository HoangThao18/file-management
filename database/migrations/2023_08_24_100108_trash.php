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
        Schema::create('trash', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('folder_id');
            $table->timestamp('created_at')->nullable();
            $table->string('created_by', 50)->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('updated_Ts')->nullable();

            $table->foreign('file_id')->references('id')->on('files')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('folder_id')->references('id')->on('folders')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('=trash');
    }
};
