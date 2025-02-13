<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('app_top', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category');
            $table->unsignedInteger('position')->nullable();
            $table->date('date');
            $table->timestamps();

            $table->unique(['category', 'date']);

            $table->index('category');
            $table->index('position');
            $table->index('date');
        });

    }

    public function down()
    {
        Schema::dropIfExists('app_top');
    }
};

