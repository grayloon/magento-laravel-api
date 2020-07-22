<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagentoCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magento_customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id')->index();
            $table->bigInteger('default_billing_id')->nullable();
            $table->bigInteger('default_address_id')->nullable();
            $table->timestamps();
            $table->string('email')->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->bigInteger('store_id')->index();
            $table->bigInteger('website_id')->index();
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
        Schema::dropIfExists('magento_customers');
    }
}
