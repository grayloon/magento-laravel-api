<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_stock_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->index();
            $table->bigInteger('product_id')->index();
            $table->integer('stock_id');
            $table->integer('qty')->default(0);
            $table->tinyInteger('is_in_stock')->default(0);
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
        Schema::dropIfExists('magento_stock_items');
    }
}
