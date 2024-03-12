<?php
namespace App\Models;

use App\Entities\PacienteEntity;
use CodeIgniter\Model;

class PacienteModel extends Model
{
    protected $table = 'paciente';
    protected $primaryKey = 'id';
    protected $returnType = PacienteEntity::class;
    
    protected $allowedFields = ['idtipo_documento', 'des_num_documento', 'des_nombre', 'ape_pat', 'ape_mat', 'des_nombre_completo', 'sexo', 'cell', 'telefono', 'email', 'fec_nacimiento', 'estado'];

    // Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'aud_fecha_registra';
	protected $updatedField         = 'aud_fecha_actualiza';
	//protected $deletedField         = 'deleted_at';

     // Validation
    protected $validationRules      = [
        'des_num_documento' => [
            'label' => ' ',
            'rules' => 'required|is_unique[paciente.des_num_documento,id,{$id}]',
        ],
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
            'rules' => 'required|max_length[160]|is_unique[paciente.des_nombre_completo,id,{$id}]',
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

    public function getPacientes($des_nombre_completo = '', $estado = '100', $sortField = 'des_nombre_completo', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->select('paciente.id,                       
                        paciente.idtipo_documento,
                        paciente.des_num_documento,
                        paciente.des_nombre_completo,
                        paciente.sexo,
                        paciente.cell,
                        paciente.telefono,
                        paciente.email,
                        DATE_FORMAT(paciente.fec_nacimiento, "%d/%m/%Y") as fec_nacimiento,
                        paciente.estado,
                        tipo_documento.des_nombre_corto as td_des_nombre_corto')
            ->join('tipo_documento', 'tipo_documento.id = paciente.idtipo_documento');
        $this->agregarFiltro($des_nombre_completo, $estado, $sortField);
        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->agregarFiltro($des_nombre_completo, $estado, $sortField);
        $totalRecords = $this->countAllResults();

        return ["pacientes" => $data, "totalRecords" => $totalRecords];
    }
    public function getPacienteById($id)
    {
        return $this
            ->select('
                id,
                idtipo_documento as tipo_documento,
                des_num_documento,
                des_nombre,
                ape_pat,
                ape_mat,
                des_nombre_completo,
                sexo,
                cell,
                telefono,
                email,
                DATE_FORMAT(fec_nacimiento, "%d/%m/%Y") as fec_nacimiento,
                estado')                
            ->where('id', $id)
            ->first();
    }    
    public function getPacientesDespegable($des_nombre_completo = '')
    {    
        $this->select('id, des_nombre_completo');

        if (!empty($des_nombre_completo)) {
            $this->like('des_nombre_completo', $des_nombre_completo, 'match');
        }
        $data = $this->orderBy('des_nombre_completo', 'asc')
            ->limit(15)
            ->get()
            ->getResult();
        

        return ["pacientes" => $data];
    }
    public function getPacienteDespegableById($id)
    {
        return $this->select('id, des_nombre_completo')
            ->where('id', $id)
            ->first();
    }

    private function agregarFiltro($des_nombre_completo = '', $estado = '100', $sortField = 'des_nombre_completo')
    {
        if (!empty($des_nombre_completo)) :
            $this->like('paciente.'.$sortField, $des_nombre_completo, 'match');
        endif;

        if ($estado !== '100') :
            $this->where('paciente.estado', $estado);
        endif;
    }

    
    
}