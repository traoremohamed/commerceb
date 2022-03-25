<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_cli
 * @property float $num_agce
 * @property int $num_typecli
 * @property string $code_cli
 * @property string $nom_cli
 * @property string $prenom_cli
 * @property string $local_cli
 * @property string $adresse_cli
 * @property string $tel_cli
 * @property string $mail_cli
 * @property string $cel_cli
 * @property string $rccm_cli
 * @property string $cpte_contr_cli
 * @property string $adresse_geo_cli
 * @property string $fax_cli
 * @property boolean $flag_cli
 * @property integer $tva_cli
 * @property string $created_at
 * @property string $updated_at
 * @property TypeClient $typeClient
 * @property Agence $agence
 */
class Client extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'client';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_cli';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['num_agce', 'num_typecli', 'code_cli', 'nom_cli', 'prenom_cli', 'local_cli', 'adresse_cli', 'tel_cli', 'mail_cli', 'cel_cli', 'rccm_cli', 'cpte_contr_cli', 'adresse_geo_cli', 'fax_cli', 'flag_cli', 'tva_cli', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeClient()
    {
        return $this->belongsTo('App\Models\TypeClient', 'num_typecli', 'num_typecli');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agence()
    {
        return $this->belongsTo('App\Models\Agence', 'num_agce', 'num_agce');
    }
}
