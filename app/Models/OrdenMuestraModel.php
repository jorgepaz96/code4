<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\OrdenMuestraEntity;

class OrdenMuestraModel extends Model
{
    protected $table = 'orden_muestra';
    protected $primaryKey = 'id';
    protected $returnType = OrdenMuestraEntity::class;

    protected $allowedFields = ['idordenexamen','idmuestra', 'idprocedenciamuestra', 'estado'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'aud_fecha_registra';
    protected $updatedField = 'aud_fecha_actualiza';
    //protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules = [        
	    'idmuestra' => [
            'label' => ' ',
            'rules' => 'required|is_not_unique[muestra.id]',
        ],
        'idprocedenciamuestra' => [
            'label' => ' ',
            'rules' => 'required|is_not_unique[procedencia_muestra.id]',
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