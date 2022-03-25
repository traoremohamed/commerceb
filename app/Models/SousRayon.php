<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $code_sr
 * @property string $code_ray
 * @property string $lib_sr
 * @property string $code_sra
 * @property int $niveau_sr
 * @property string $code_rgray
 * @property string $code_ndp
 * @property integer $id_p_sous_rayon
 * @property string $created_at
 * @property string $updated_at
 * @property Rayon $rayon
 */
class SousRayon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sous_rayon';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code_sr';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['code_ray', 'lib_sr', 'code_sra', 'niveau_sr', 'code_rgray', 'code_ndp', 'id_p_sous_rayon', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rayon()
    {
        return $this->belongsTo('App\Rayon', 'code_ray', 'code_ray');
    }
}
