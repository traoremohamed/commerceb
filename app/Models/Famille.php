<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_fam
 * @property string $lib_fam
 * @property boolean $flag_fam
 * @property string $created_at
 * @property string $updated_at
 * @property SousFamille[] $sousFamilles
 */
class Famille extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'famille';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_fam';

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
    protected $fillable = ['lib_fam', 'flag_fam', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sousFamilles()
    {
        return $this->hasMany('App\Models\SousFamille', 'num_fam', 'num_fam');
    }
}
