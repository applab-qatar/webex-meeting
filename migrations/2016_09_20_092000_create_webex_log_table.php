<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebexLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webex_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('loggable');
            $table->string('event',50)->nullable();
            $table->mediumText('request')->nullable();
            $table->string('response_id',100)->nullable();
            $table->longText('response')->nullable();
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
        Schema::dropIfExists('webex_logs');
    }
}
