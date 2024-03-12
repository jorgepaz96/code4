<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\MenbreteEntity;

class MenbreteModel extends Model
{
    protected $table = 'menbrete';
    protected $primaryKey = 'id';
    protected $returnType = MenbreteEntity::class;
    
    protected $allowedFields = ['des_nombre','des_ruta','estado'];

    // Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'aud_fecha_registra';
	protected $updatedField         = 'aud_fecha_actualiza';
	//protected $deletedField         = 'deleted_at';

     // Validation
    protected $validationRules      = [
	    'des_nombre' => 'required|max_length[250]|is_unique[muestra.des_nombre,id,{$id}]',
        'estado' => 'in_list[0,1]'
	];
	
    protected $skipValidation       = false;
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
    public function getMenbretes($des_nombre = '', $estado = '100', $sortField = 'des_nombre', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->select('id, des_nombre, des_ruta, estado');
        $this->agregarFiltro($des_nombre, $estado);
        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->agregarFiltro($des_nombre, $estado);
        $totalRecords = $this->countAllResults();

        return ["menbrete" => $data, "totalRecords" => $totalRecords];
    }
    public function getMenbreteById($id)
    {
        return $this->select('id, des_nombre, des_ruta, estado')
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