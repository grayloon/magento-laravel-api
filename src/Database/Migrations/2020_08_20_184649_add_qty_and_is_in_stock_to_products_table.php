<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyAndIsInStockToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magento_products', function (Blueprint $table) {
            $table->integer('qty')->default(0);
            $table->tinyInteger('is_in_stock')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('magento_products', function (Blueprint $table) {
            $table->dropColumn('qty');
            $table->dropColumn('is_in_stock');
        });
    }
}
