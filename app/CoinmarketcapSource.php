<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Класс CoinmarketcapSource
 *
 * @package App
 */
class CoinmarketcapSource extends Source
{
    protected function afterRequest($coins)
    {
        $targetCoins = Coin::all()->map(function ($coin) {
            return $coin['code'];
        })->toArray();
        foreach ($coins as $coin) {
            if (in_array($coin['symbol'], $targetCoins)) {
                $coinCode = $coin['symbol'];
                $price = $coin['price_usd'];
                $percentChange24h = $coin['percent_change_24h'];
                $lastUpdated = $coin['last_updated'];
                $average = CoinHistory::average($coinCode);
                if (!$average) $average = $price;

                /** @var Coin $coin */
                $coin = Coin::where('code', $coinCode)->first();

                $exist = CoinHistory::where( 'coin_id', $coin->id )
                    ->where( 'value', $price)
                    ->where( 'avg', $average)
                    ->where( 'rel', $percentChange24h)
                    ->first();

                if ( !$exist ) {
                    // Одни и те же данные не добавляем
                    CoinHistory::create([
                        'coin_id'    => $coin->id,
                        'source_id'  => $this->id,
                        'value'      => $price,
                        'avg'        => $average,
                        'rel'        => $percentChange24h,
                        'created_at' => Carbon::now(),
                        'updated_at' => $lastUpdated,
                    ]);
                }
            }
        }
    }

    protected static function sourceName()
    {
        return 'coinmarketcap';
    }
}
