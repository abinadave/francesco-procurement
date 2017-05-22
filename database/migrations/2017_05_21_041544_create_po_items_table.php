<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_form_id')->default(0);
            $table->integer('request_form_id')->default(0);
            $table->integer('po_id')->default(0);
            $table->integer('qty')->default(0);
            $table->string('unit')->nullable();
            $table->string('description')->nullable();
            $table->double('unit_price', 10, 2)->default(0);
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
        Schema::dropIfExists('po_items');
    }
}
