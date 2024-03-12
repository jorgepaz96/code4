<?php
namespace App\Models;

use App\Entities\TrabajadorEntity;
use CodeIgniter\Model;

class TrabajadorModel extends Model
{
    protected $table = 'trabajador';
    protected $primaryKey = 'id';
    protected $returnType = TrabajadorEntity::class;
    
    protected $allowedFields = ['des_nombre', 'ape_pat', 'ape_mat', 'des_nombre_completo', 'sexo', 'cell', 'email', 'estado'];

    // Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'aud_fecha_registra';
	protected $updatedField         = 'aud_fecha_actualiza';
	//protected $deletedField         = 'deleted_at';

     // Validation
    protected $validationRules      = [        
        'des_nombre' => [
            'label' => ' ',
            'rules' => 'required|max_length[100]',
        ],
        'ape_pat' => [
            'label' => ' ',
            'rules' => 'required|max_length[30]',
        ],
        'ape_mat' => [
            'label' => ' ',
            'rules' => 'required|max_length[30]',
        ],
        'des_nombre_completo' => [
            'label' => ' ',
            'rules' => 'required|max_length[160]|is_unique[trabajador.des_nombre_completo,id,{$id}]',
        ],
        'sexo' => [
            'label' => ' ',
            'rules' => 'required|in_list[M,F]',
        ],	    
        // 'cell' => [
        //     'label' => ' ',
        //     'rules' => 'numeric',
        // ],
        // 'telefono' => [
        //     'label' => ' ',
        //     'rules' => 'numeric',
        // ],        
        // 'email' => [
        //     'label' => ' ',
        //     'rules' => 'required|regex_match[/^[\p{L}\d_.-]+@[\p{L}\d.-]+\.[\p{L}]{2,}$/u]|max_length[100]|is_unique[compania.email,id,{$id}]',
        // ],               
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

    public function getTrabajadors($des_nombre_completo = '', $estado = '100', $sortField = 'des_nombre_completo', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->select('id,                
                des_nombre,
                ape_pat,
                ape_mat,
                des_nombre_completo,
                sexo,
                cell,                
                email,                
                estado');            
        $this->agregarFiltro($des_nombre_completo, $estado, $sortField);
        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->agregarFiltro($des_nombre_completo, $estado, $sortField);
        $totalRecords = $this->countAllResults();

        return ["trabajadores" => $data, "totalRecords" => $totalRecords];
    }
    public function getTrabajadorById($id)
    {
        return $this
            ->select('
                id,                
                des_nombre,
                ape_pat,
                ape_mat,
                des_nombre_completo,
                sexo,
                cell,                
                email,                
                estado')                
            ->where('id', $id)
            ->first();
    }    

    private function agregarFiltro($des_nombre_completo = '', $estado = '100', $sortField = 'des_nombre_completo')
    {
        if (!empty($des_nombre_completo)) :
            $this->like('trabajador.'.$sortField, $des_nombre_completo, 'match');
        endif;

        if ($estado !== '100') :
            $this->where('trabajador.estado', $estado);
        endif;
    }

    
    
}