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
        Schema::table('mesas', function (Blueprint $table) {
            $table->float('x')->default(50); // Coordenada X con un valor predeterminado
            $table->float('y')->default(50); // Coordenada Y con un valor predeterminado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mesas', function (Blueprint $table) {
            $table->dropColumn(['x', 'y']);
        });
    }
};
