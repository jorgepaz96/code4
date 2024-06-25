<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ExamenEntity;

class ExamenModel extends Model
{
    protected $table = 'examen';
    protected $primaryKey = 'id';
    protected $returnType = ExamenEntity::class;

    protected $allowedFields = ['des_nombre', 'estado'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'aud_fecha_registra';
    protected $updatedField = 'aud_fecha_actualiza';
    //protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules = [
        'des_nombre' => [
            'label' => ' ',
            'rules' => 'required|max_length[150]|is_unique[examen.des_nombre,id,{$id}]'
        ],
        'estado' => 'in_list[0,1]'
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

    public function getExamenes($des_nombre = '', $estado = '100', $sortField = 'des_nombre', $sortOrder = 'asc', $offset = 0, $limit = 10)
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

        return ["examenes" => $data, "totalRecords" => $totalRecords];
    }
    public function getExamenById($id)
    {
        return $this->select('id, des_nombre, estado')
            ->where('id', $id)
            ->first();
    }

    public function getExamenesDespegable($des_nombre = '')
    {    
        $this->select('id, des_nombre');

        if (!empty($des_nombre)) {
            $this->like('des_nombre', $des_nombre, 'match');
        }
        $data = $this->orderBy('des_nombre', 'asc')
            ->limit(15)
            ->get()
            ->getResult();
        

        return ["examenes" => $data];
    }
    public function getExamenDespegableById($id)
    {
        return $this->select('id, des_nombre')
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