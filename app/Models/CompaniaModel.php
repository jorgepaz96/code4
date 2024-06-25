<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\CompaniaEntity;

class CompaniaModel extends Model
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
        'nombre_comercial' => [
            'label' => ' ',
            'rules' => 'required|max_length[150]|is_unique[compania.nombre_comercial,id,{$id}]',
        ],
	    'nombre_fiscal' => [
            'label' => ' ',
            'rules' => 'required|max_length[150]|is_unique[compania.nombre_fiscal,id,{$id}]',
        ],
        'num_ruc' => [
            'label' => ' ',
            'rules' => 'required|numeric|exact_length[11]|is_unique[compania.num_ruc,id,{$id}]',
        ],        
        'email' => [
            'label' => ' ',
            'rules' => 'required|regex_match[/^[\p{L}\d_.-]+@[\p{L}\d.-]+\.[\p{L}]{2,}$/u]|max_length[100]|is_unique[compania.email,id,{$id}]',
        ],
        'cell' => [
            'label' => ' ',
            'rules' => 'required|numeric|exact_length[9]',
        ],
        'idtipo_persona' => [
            'label' => ' ',
            'rules' => 'is_not_unique[tipo_persona.id]', // 1 : Natural , 2 : Juridica
        ],        
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

    public function getCompanias($nombre_comercial = '', $estado = '100', $sortField = 'nombre_comercial', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->select('compania.id, compania.num_ruc, compania.nombre_comercial, compania.nombre_fiscal, compania.email, compania.cell, compania.estado, tipo_persona.des_nombre as tp_des_nombre')
            ->join('tipo_persona', 'tipo_persona.id = compania.idtipo_persona');
        $this->agregarFiltro($nombre_comercial, $estado, $sortField);
        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->agregarFiltro($nombre_comercial, $estado, $sortField);
        $totalRecords = $this->countAllResults();

        return ["companias" => $data, "totalRecords" => $totalRecords];
    }
    public function getCompaniaById($id)
    {
        return $this
            ->select('id, num_ruc, idtipo_persona as tipopersona, nombre_comercial, nombre_fiscal, email, cell, estado')                
            ->where('id', $id)
            ->first();
    }
    public function getCompaniasDespegable($nombre_comercial = '')
    {    
        $this->select('id, nombre_comercial, idperfiltarifario');

        if (!empty($nombre_comercial)) {
            $this->like('nombre_comercial', $nombre_comercial, 'match');
        }
        $data = $this->orderBy('nombre_comercial', 'asc')
            ->limit(15)
            ->get()
            ->getResult();
        

        return ["companias" => $data];
    }
    public function getCompaniaDespegableById($id)
    {
        return $this->select('id, nombre_comercial')
            ->where('id', $id)
            ->first();
    }    

    private function agregarFiltro($nombre_comercial = '', $estado = '100', $sortField = 'nombre_comercial')
    {
        if (!empty($nombre_comercial)) :
            $this->like('compania.'.$sortField, $nombre_comercial, 'match');
        endif;

        if ($estado !== '100') :
            $this->where('compania.estado', $estado);
        endif;
    }

    
    
}