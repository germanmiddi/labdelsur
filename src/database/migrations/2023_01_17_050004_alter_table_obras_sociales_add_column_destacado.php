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
        Schema::table('obras_sociales', function (Blueprint $table) {
            $table->boolean('favorite')->default(0)->after('visible');
            $table->string('url')->nullable()->after('favorite');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obras_sociales', function (Blueprint $table) {
            
        });        
    }
};
