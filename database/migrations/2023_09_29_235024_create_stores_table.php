<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('logo');
            $table->string('address');
            $table->foreignId('owner_id')
                ->nullable()
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
