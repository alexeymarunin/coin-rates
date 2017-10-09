<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Класс Coin
 *
 * @package App
 *
 * @property int $id
 * @property string $name
 */
class Coin extends Model
{
    protected $fillable = [ 'name' ];

    protected $table = 'coins';

}
