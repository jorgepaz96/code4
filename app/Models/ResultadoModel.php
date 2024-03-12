<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ResultadoEntity;

class ResultadoModel extends Model
{
    protected $table = 'resultado';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ResultadoEntity::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['des_resultado', 'idetiqueta', 'estado'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'aud_fecha_registra';
    protected $updatedField = 'aud_fecha_actualiza';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
                
        'estado' => 'in_list[0,1]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setUsuarioI'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['setUsuarioU'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];


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

    public function getResultados($idetiqueta = '',$des_resultado = '', $estado = '100', $sortField = 'des_resultado', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->select('resultado.id, resultado.des_resultado, resultado.idetiqueta, etiqueta.des_nombre as e_des_nombre , resultado.estado')
        ->join('etiqueta', 'etiqueta.id = resultado.idetiqueta');
        $this->agregarFiltro($idetiqueta,$estado);
        $data = $this            
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->agregarFiltro($idetiqueta,$estado);
        $totalRecords = $this->countAllResults();

        return ["resultados" => $data, "totalRecords" => $totalRecords];
    }
    public function getResultadoById($id)
    {
        return $this->select('id, des_resultado, idetiqueta as etiqueta, estado')
            ->where('id', $id)
            ->first();
    }

    public function getResultadosDespegable($des_resultado = '')
    {    
        $this->select('id, des_resultado');

        if (!empty($des_resultado)) {
            $this->like('des_resultado', $des_resultado, 'match');
        }
        $data = $this->limit(15)
            ->get()
            ->getResult();
        

        return ["resultados" => $data];
    }
    public function getResultadoDespegableById($id)
    {
        return $this->select('id, des_resultado')
            ->where('id', $id)
            ->first();
    }

    private function agregarFiltro($idetiqueta = '', $estado = '100')
    {  
        if (!empty($idetiqueta)) {
            $this->where('resultado.idetiqueta', $idetiqueta);
        }
        if ($estado !== '100') {
            $this->where('resultado.estado', $estado);
        }
    }
}
