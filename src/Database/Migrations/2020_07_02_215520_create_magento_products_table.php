<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->index();
            $table->string('name')->index();
            $table->decimal('price', 15, 4)->default(0.00);
            $table->tinyInteger('status');
            $table->integer('visibility');
            $table->string('type');
            $table->decimal('weight', 15, 4)->default(0.00);
            $table->text('extension_attributes');
            $table->text('custom_attributes');
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
        Schema::dropIfExists('magento_products');
    }
}
