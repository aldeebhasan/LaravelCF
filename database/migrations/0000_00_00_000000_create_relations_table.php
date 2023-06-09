<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_relations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('source')->index();
            $table->bigInteger('target')->index();
            $table->string('type', 10)->index();
            $table->string('value', 25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rs_relations');
    }
};
