<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Класс CoincapSource
 *
 * @package App
 */
class CoincapSource extends Source
{
    protected function afterRequest($coins)
    {
        $targetCoins = Coin::all()->map(function ($coin) {
            return $coin['code'];
        })->toArray();

        foreach ($coins as $coin) {
            if (in_array($coin['short'], $targetCoins)) {
                $coinCode = $coin['short'];
                $price = $coin['price'];
                $percentChange24h = $coin['cap24hrChange'];
                $lastUpdated = time();
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
        return 'coincap';
    }
}
