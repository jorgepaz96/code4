<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ProcedenciaMuestraEntity;

class ProcedenciaMuestraModel extends Model
{
    protected $table            = 'procedencia_muestra';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = ProcedenciaMuestraEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['des_nombre','estado'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'aud_fecha_registra';
    protected $updatedField  = 'aud_fecha_actualiza';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'des_nombre' => [
            'label' => ' ',
            'rules' => 'required|max_length[100]|is_unique[procedencia_muestra.des_nombre,id,{$id}]',
        ],	    
        'estado' => 'in_list[0,1]'
	];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setUsuarioI'];     
    protected $afterInsert    = [];
    protected $beforeUpdate = ['setUsuarioU'];  
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


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
