<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('co_name');
            $table->string('cust_name');
            $table->string('email');
            $table->string('contact');
            $table->string('subject');
            $table->text('details');
            $table->integer('status')->default(1);
            $table->integer('progress')->default(0);
            $table->string('comment')->nullable();
            $table->string('slug');
            $table->string('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_forms');
    }
}
