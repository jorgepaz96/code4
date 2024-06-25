<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\EstudioEntity;

class EstudioModel extends Model
{
    protected $table = 'estudio';
    protected $primaryKey = 'id';
    protected $returnType = EstudioEntity::class;
    
    protected $allowedFields = ['des_nombre','des_nombre_abrev','estado'];

    // Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'aud_fecha_registra';
	protected $updatedField         = 'aud_fecha_actualiza';
	//protected $deletedField         = 'deleted_at';

     // Validation
    protected $validationRules      = [
	    'des_nombre' => 'required|max_length[150]|is_unique[estudio.des_nombre,id,{$id}]',
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
    public function getEstudiosDespegable($des_nombre = '')
    {    
        $this->select('id, des_nombre');

        if (!empty($des_nombre)) {
            $this->like('des_nombre', $des_nombre, 'match');
        }
        $data = $this->orderBy('des_nombre', 'asc')
            ->limit(15)
            ->get()
            ->getResult();
        

        return ["estudios" => $data];
    }
    public function getEstudioDespegableById($id)
    {
        return $this->select('id, des_nombre')
            ->where('id', $id)
            ->first();
    }    
    
}