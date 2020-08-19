<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoProductLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_product_links', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->index();
            $table->bigInteger('related_product_id');
            $table->string('link_type');
            $table->integer('position');
            $table->timestamps();
            $table->timestamp('synced_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magento_product_links');
    }
}
