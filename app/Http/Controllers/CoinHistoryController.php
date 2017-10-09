<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoinHistoryResource;
use App\CoinHistory;
use Illuminate\Support\Facades\DB;

/**
 * Класс CoinHistoryController
 *
 * @package App\Http\Controllers
 */
class CoinHistoryController extends Controller
{
    public function latest($source)
    {
        // Это лютый треш. Потыкать потом палочкой
        $subQuery = DB::raw('(SELECT coin_id, MAX(updated_at) AS latest_updated FROM coins_history WHERE source_id = \'' . $source . '\' GROUP BY coin_id) AS sq');
        $sourceHistory = CoinHistory::select('ch.*')
            ->from('coins_history AS ch')
            ->where('ch.source_id', $source)
            ->join($subQuery, function ($join) {
                $join->on('ch.coin_id', '=', 'sq.coin_id')->on('ch.updated_at', '=', 'sq.latest_updated');
            });

//        $sql = $sourceHistory->toSql();
        
        return CoinHistoryResource::collection($sourceHistory->get());
    }
}
