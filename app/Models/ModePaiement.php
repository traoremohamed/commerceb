<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $num_mpaie
 * @property string $lib_mpaie
 * @property boolean $flag_taxe
 * @property string $created_at
 * @property string $updated_at
 */
class ModePaiement extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mode_paiement';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_mpaie';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['lib_mpaie', 'flag_taxe', 'created_at', 'updated_at'];

}
