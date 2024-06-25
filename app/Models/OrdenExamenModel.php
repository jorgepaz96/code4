<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\OrdenExamenEntity;

class OrdenExamenModel extends Model
{
    protected $table = 'orden_examen';
    protected $primaryKey = 'id';
    protected $returnType = OrdenExamenEntity::class;

    protected $allowedFields = ['idordencab','idexamen', 'cantidad', 'precio', 'estado'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'aud_fecha_registra';
    protected $updatedField = 'aud_fecha_actualiza';
    //protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules = [        
	    'idexamen' => [
            'label' => ' ',
            'rules' => 'required|is_not_unique[examen.id]',
        ],
        'cantidad' => [
            'label' => ' ',
            'rules' => 'required|is_natural',
        ],
        'precio' => [
            'label' => ' ',
            'rules' => 'required|decimal',
        ],        
        'estado'                => 'in_list[0,1]'
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;


    protected $beforeInsert = ['setUsuarioI'];
    protected $beforeUpdate = ['setUsuarioU'];


    protected function setUsuarioI(array $data)
    {
        $data["data"]['aud_usuario_registra'] = 'jpazm';
        return $data;

    }
    protected function setUsuarioU(array $data)
    {
        $data["data"]['aud_usuario_actualiza'] = 'jpazm';
        return $data;
    }
}