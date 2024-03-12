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

    public function getProcedenciaMuestras($des_nombre = '', $estado = '100', $sortField = 'des_nombre', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->select('id, des_nombre, estado');
        $this->agregarFiltro($des_nombre, $estado);
        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->agregarFiltro($des_nombre, $estado);
        $totalRecords = $this->countAllResults();

        return ["procedenciamuestras" => $data, "totalRecords" => $totalRecords];
    }
    public function getProcedenciaMuestraById($id)
    {
        return $this->select('id, des_nombre, estado')
            ->where('id', $id)
            ->first();
    }    

    private function agregarFiltro($des_nombre = '', $estado = '100')
    {
        if (!empty($des_nombre)) {
            $this->like('des_nombre', $des_nombre, 'match');
        }

        if ($estado !== '100') {
            $this->where('estado', $estado);
        }
    }
}
