<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_sousfam
 * @property float $num_fam
 * @property string $lib_sousfam
 * @property boolean $flag_sousfam
 * @property string $created_at
 * @property string $updated_at
 * @property Famille $famille
 * @property Produit[] $produits
 */
class SousFamille extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sous_famille';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_sousfam';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['num_fam', 'lib_sousfam', 'flag_sousfam', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function famille()
    {
        return $this->belongsTo('App\Models\Famille', 'num_fam', 'num_fam');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produits()
    {
        return $this->hasMany('App\Models\Produit', 'num_sousfam', 'num_sousfam');
    }
}
