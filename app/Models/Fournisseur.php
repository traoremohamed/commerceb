<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_fourn
 * @property string $lib_fourn
 * @property string $adr_fourn
 * @property string $tel_fourn
 * @property string $cel_fourn
 * @property string $fax_fourn
 * @property string $mail_fourn
 * @property string $rccm_fourn
 * @property string $cpte_contr_fourn
 * @property boolean $flag_tva_fourn
 * @property boolean $flag_fourn
 * @property string $created_at
 * @property string $updated_at
 */
class Fournisseur extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fournisseur';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_fourn';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var array
     */
    protected $fillable = ['lib_fourn', 'adr_fourn', 'tel_fourn', 'cel_fourn', 'fax_fourn',
                            'mail_fourn', 'rccm_fourn', 'cpte_contr_fourn', 'flag_tva_fourn',
                            'flag_fourn', 'created_at', 'updated_at'];

}
