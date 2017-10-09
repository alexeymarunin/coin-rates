<?php

namespace App\Http\Resources;

use App\Coin;
use App\Source;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Класс CoinHistoryResource
 *
 * @package App\Http\Resources
 *
 * @property Coin $coin
 * @property Source $source
 * @property float $value
 * @property float $avg
 * @property float $rel
 * @property string $created_at
 * @property string $updated_at
 */
class CoinHistoryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->coin->name,
            'source' => $this->source->name,
            'value' => $this->value,
            'avg' => $this->avg,
            'rel' => $this->rel,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
        ];
    }
}
