<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $num_reg
 * @property float $num_fact
 * @property int $num_mpaie
 * @property float $montant_ttc_reg
 * @property string $created_at
 * @property string $updated_at
 */
class Reglement extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'reglement';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_reg';

    /**
     * @var array
     */
    protected $fillable = ['num_fact', 'num_mpaie', 'montant_ttc_reg', 'created_at', 'updated_at'];

}
