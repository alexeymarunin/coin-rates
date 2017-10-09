<?php

namespace App\Http\Controllers;

use App\Coin;
use App\Http\Resources\CoinResource;
use App\Http\Resources\CoinHistoryResource;
use App\CoinHistory;
use Illuminate\Support\Facades\DB;

/**
 * Класс CoinController
 *
 * @package App\Http\Controllers
 */
class CoinController extends Controller
{
    public function latest(Coin $coin)
    {
        // Это лютый треш. Потыкать потом палочкой
        $subQuery = DB::raw('(SELECT coin_id, MAX(updated_at) AS latest_updated FROM coins_history GROUP BY coin_id) AS sq');
        $sourceHistory = CoinHistory::select('ch.*')
            ->from('coins_history AS ch')
//            ->where('ch.coin_id', $coin)
            ->join($subQuery, function ($join) {
                $join->on('ch.coin_id', '=', 'sq.coin_id')->on('ch.updated_at', '=', 'sq.latest_updated');
            })
            ->orderBy( 'ch.rel', 'desc');

//        $sql = $sourceHistory->toSql();

        return CoinHistoryResource::collection($sourceHistory->get());
    }

    public function index(Coin $coin)
    {
        return CoinResource::collection($coin->paginate());
    }

    public function view(Coin $coin)
    {
        return CoinResource::make($coin);
    }
}
