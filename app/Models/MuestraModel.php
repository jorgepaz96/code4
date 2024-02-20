<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\MuestraEntity;

class MuestraModel extends Model
{
    protected $table = 'muestra';
    protected $primaryKey = 'id';
    protected $returnType = MuestraEntity::class;
    
    protected $allowedFields = ['des_nombre','estado'];

    // Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'aud_fecha_registra';
	protected $updatedField         = 'aud_fecha_actualiza';
	//protected $deletedField         = 'deleted_at';

     // Validation
    protected $validationRules      = [
	    'des_nombre' => 'required|max_length[150]|is_unique[muestra.des_nombre,id,{$id}]',
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
    
}