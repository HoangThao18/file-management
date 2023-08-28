<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name', 50);
            $table->string('password')->nullable();
            $table->string('social_id')->nullable();
            $table->string('package_type', 10)->default('basic');
            $table->date('package_register_date')->nullable();
            $table->date('package_expiration_date');
            $table->integer('max_storage');
            $table->date('last_login_date')->nullable();
            $table->boolean('remember')->default(0);
            $table->string('created_by', 50)->nullable();
            $table->string('updated_by', 50)->nullable();
            $table->timestamp('updated_ts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
