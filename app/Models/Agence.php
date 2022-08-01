<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_agce
 * @property string $lib_agce
 * @property boolean $flag_agce
 * @property boolean $flag_siege_agce
 * @property string $created_at
 * @property string $updated_at
 * @property string $taux_ristourne_cli
 * @property Client[] $clients
 */
class Agence extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agence';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_agce';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['lib_agce', 'flag_agce', 'flag_siege_agce', 'created_at', 'updated_at','taux_ristourne_cli'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany('App\Models\Client', 'num_agce', 'num_agce');
    }
}
