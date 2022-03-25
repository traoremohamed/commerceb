<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id_sit_mat
 * @property string $lib_sit_mat
 * @property boolean $flag_sit_mat
 * @property Client[] $clients
 */
class SituationMatri extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'situation_matri';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_sit_mat';

    /**
     * @var array
     */
    protected $fillable = ['lib_sit_mat', 'flag_sit_mat'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany('App\Client', 'id_sit_mat', 'id_sit_mat');
    }
}
