<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('lastname')->nullable();
            $table->string('name')->nullable();
            $table->integer('dni')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();            
            $table->string('cellphone')->nullable();            
            $table->text('notes')->nullable();            
            $table->timestamps();
            $table->softDeletes();
        

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
