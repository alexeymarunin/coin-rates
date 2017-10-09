<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->string('code', 3)->unique();
            $table->timestamps();
        });

        DB::table('coins')->insert([
            [
                'name' => 'Bitcoin',
                'code' => 'BTC',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Ethereum',
                'code' => 'ETH',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Ripple',
                'code' => 'XRP',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Litecoin',
                'code' => 'LTC',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'NEO',
                'code' => 'NEO',
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
        Schema::dropIfExists('coins');
    }
}
