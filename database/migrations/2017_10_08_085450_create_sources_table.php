<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->string('base_url', 255);
            $table->string('api_path', 255);
            $table->timestamps();
        });

        DB::table('sources')->insert([
            [
                'name'       => 'coinmarketcap',
                'base_url'   => 'https://api.coinmarketcap.com',
                'api_path'   => '/v1/ticker',
                'created_at' => Carbon::now(),
            ],
            [
                'name'       => 'coincap',
                'base_url'   => 'https://coincap.io',
                'api_path'   => '/front',
                'created_at' => Carbon::now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sources');
    }
}
