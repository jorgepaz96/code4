<?php
namespace App\Models;

use App\Entities\MedicoEntity;
use CodeIgniter\Model;

class MedicoModel extends Model
{
    protected $table = 'medico';
    protected $primaryKey = 'id';
    protected $returnType = MedicoEntity::class;
    
    protected $allowedFields = ['des_nombre', 'ape_pat', 'ape_mat', 'des_nombre_completo', 'idprofesion', 'sexo', 'cell', 'telefono', 'email', 'estado'];

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
            'rules' => 'required|max_length[160]|is_unique[medico.des_nombre_completo,id,{$id}]',
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

    public function getMedicos($des_nombre_completo = '', $estado = '100', $sortField = 'des_nombre_completo', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->select('medico.id,                       
                        medico.des_nombre_completo,
                        medico.idprofesion,
                        medico.sexo,
                        medico.cell,
                        medico.telefono,
                        medico.email,
                        medico.estado,
                        profesion.des_nombre as p_des_nombre')
            ->join('profesion', 'profesion.id = medico.idprofesion');
        $this->agregarFiltro($des_nombre_completo, $estado, $sortField);
        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->agregarFiltro($des_nombre_completo, $estado, $sortField);
        $totalRecords = $this->countAllResults();

        return ["medicos" => $data, "totalRecords" => $totalRecords];
    }
    public function getMedicoById($id)
    {
        return $this
            ->select('id, des_nombre, ape_pat, ape_mat, des_nombre_completo, idprofesion as profesion, sexo, cell, telefono, email, estado')                
            ->where('id', $id)
            ->first();
    }
    
    public function getMedicosDespegable($des_nombre_completo = '')
    {    
        $this->select('id, des_nombre_completo');

        if (!empty($des_nombre_completo)) {
            $this->like('des_nombre_completo', $des_nombre_completo, 'match');
        }
        $data = $this->orderBy('des_nombre_completo', 'asc')
            ->limit(15)
            ->get()
            ->getResult();
        

        return ["medicos" => $data];
    }
    public function getMedicoDespegableById($id)
    {
        return $this->select('id, des_nombre_completo')
            ->where('id', $id)
            ->first();
    }

    private function agregarFiltro($des_nombre_completo = '', $estado = '100', $sortField = 'des_nombre_completo')
    {
        if (!empty($des_nombre_completo)) :
            $this->like('medico.'.$sortField, $des_nombre_completo, 'match');
        endif;

        if ($estado !== '100') :
            $this->where('medico.estado', $estado);
        endif;
    }

    
    
}