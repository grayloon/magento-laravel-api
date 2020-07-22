<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('region_code');
            $table->string('region');
            $table->bigInteger('region_id');
            $table->text('street');
            $table->string('telephone')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->tinyInteger('default_billing')->default(0);
            $table->tinyInteger('default_shipping')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magento_customer_addresses');
    }
}
