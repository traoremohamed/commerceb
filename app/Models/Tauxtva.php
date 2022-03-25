<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $val_taxe
 */
class Tauxtva extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tauxtva';

    /**
     * @var array
     */
    protected $fillable = ['val_taxe'];

}
