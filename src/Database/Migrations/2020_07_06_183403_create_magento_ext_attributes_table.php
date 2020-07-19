<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoExtAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_extension_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('magento_product_id')->index();
            $table->bigInteger('magento_ext_attribute_type_id')->index();
            $table->text('attribute');
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
        Schema::dropIfExists('magento_ext_attributes');
    }
}
