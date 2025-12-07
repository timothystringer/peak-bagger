<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peaks', function (Blueprint $table) {
            if (! Schema::hasColumn('peaks', 'name')) return;

            $table->index('name');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('peaks', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['category']);
        });
    }
};

