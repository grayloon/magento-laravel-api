<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoProductMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_product_media', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->index();
            $table->string('media_type')->index();
            $table->string('label')->nullable();
            $table->integer('position');
            $table->bigInteger('disabled')->default(0);
            $table->text('types')->nullable();
            $table->text('file');
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
        Schema::dropIfExists('magento_product_media');
    }
}
