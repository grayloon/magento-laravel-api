<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoCustomAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_custom_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_type')->index();
            $table->integer('attribute_type_id')->index();
            $table->text('value');
            $table->bigInteger('attributable_id')->index();
            $table->string('attributable_type')->index();
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
        Schema::dropIfExists('magento_custom_attributes');
    }
}
