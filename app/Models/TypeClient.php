<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $num_typecli
 * @property string $lib_typecli
 * @property boolean $flag_typecli
 * @property string $created_at
 * @property string $updated_at
 * @property Client[] $clients
 */
class TypeClient extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'type_client';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_typecli';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['lib_typecli', 'flag_typecli', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany('App\Models\Client', 'num_typecli', 'num_typecli');
    }
}
