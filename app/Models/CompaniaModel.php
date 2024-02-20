<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\CompaniaEntity;

class ExamenModel extends Model
{
    protected $table = 'compania';
    protected $primaryKey = 'id';
    protected $returnType = CompaniaEntity::class;
    
    protected $allowedFields = ['nombre_comercial','nombre_fiscal','idtipo_persona','num_ruc','email','cell','estado'];

    // Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'aud_fecha_registra';
	protected $updatedField         = 'aud_fecha_actualiza';
	//protected $deletedField         = 'deleted_at';

     // Validation
    protected $validationRules      = [
	    'nombre_comercial'      => 'required|max_length[150]|is_unique[compania.nombre_comercial,id,{$id}]',
        'nombre_fiscal'         => 'required|max_length[150]|is_unique[compania.nombre_fiscal,id,{$id}]',
        'num_ruc'               => 'required|exact_length[11]|is_unique[compania.num_ruc,id,{$id}]',
        'email'                 => 'required|valid_email|max_length[100]|is_unique[compania.nombre_fiscal,id,{$id}]',
        'cell'                  => 'required|exact_length[9]',
        'idtipo_persona'        => 'in_list[1,2]', // 1 : Natural , 2 : Juridica
        'estado'                => 'in_list[0,1]'
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