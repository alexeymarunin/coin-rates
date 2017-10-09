<?php

namespace App;

use Illuminate\Database\Eloquent\Concerns\QueriesRelationships;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Класс CoinHistory
 *
 * @package App
 *
 * @property int $id
 * @property int $coin_id
 * @property int $source_id
 * @property float $value
 * @property float $avg
 * @property float $rel
 * @property int $created_at
 * @property int $updated_at
 *
 * @method static Builder ofSource( $name ) Builder
 */
class CoinHistory extends Model
{
    protected $fillable = ['coin_id', 'source_id', 'value', 'avg', 'rel', 'created_at', 'updated_at'];

    protected $table = 'coins_history';

    /**
     * @param Builder $query
     * @param string $source
     *
     * @return Builder
     */
    public function scopeOfSource( $query, $source )
    {
        return $query->whereHas('source', function ($q) use ($source) {
            $q->where('name', $source);
        } );
    }
    
    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public static function average($coinCode)
    {
        return static::whereHas('coin', function ($query) use ($coinCode) {
            $query->where('code', $coinCode);
        })->get()->average('value');
    }
}
