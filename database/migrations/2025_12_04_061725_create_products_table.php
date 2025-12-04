<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Product name
            $table->text('detail')->nullable(); // Product details
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status
            $table->unsignedBigInteger('created_by')->nullable(); // Creator ID
            $table->unsignedBigInteger('updated_by')->nullable(); // Updater ID
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at (soft delete)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
