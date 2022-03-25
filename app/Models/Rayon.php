<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $code_ray
 * @property float $code_fa
 * @property string $code_ray_mig
 * @property string $lib_ray
 * @property int $resp_ray
 * @property int $niveau_ray
 * @property integer $id_p_rayon
 * @property string $created_at
 * @property string $updated_at
 * @property Famille $famille
 */
class Rayon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rayon';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code_ray';

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
    protected $fillable = ['code_fa', 'code_ray_mig', 'lib_ray', 'resp_ray', 'niveau_ray', 'id_p_rayon', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function famille()
    {
        return $this->belongsTo('App\Famille', 'code_fa', 'num_fam');
    }
}
