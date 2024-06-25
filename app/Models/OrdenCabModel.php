<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\OrdenCabEntity;

class OrdenCabModel extends Model
{
    protected $table = 'orden_cab';
    protected $primaryKey = 'id';
    protected $returnType = OrdenCabEntity::class;

    protected $allowedFields = [
        'anio',
        'idestudio',
        'num_orden',
        'idcompania',
        'idmedico',
        'idpaciente',
        'estado'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'aud_fecha_registra';
    protected $updatedField = 'aud_fecha_actualiza';
    //protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules = [
        'anio' => [
            'label' => ' ',
            'rules' => 'required',
        ],
	    'idestudio' => [
            'label' => ' ',
            'rules' => 'required|is_not_unique[estudio.id]',
        ],
        'idcompania' => [
            'label' => ' ',
            'rules' => 'required|is_not_unique[compania.id]',
        ],
        'idmedico' => [
            'label' => ' ',
            'rules' => 'required|is_not_unique[medico.id]',
        ],
        'idpaciente' => [
            'label' => ' ',
            'rules' => 'required|is_not_unique[paciente.id]',
        ],
        'estado'                => 'in_list[0,1]'
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

    public function validarRegistros($registros)
    {
        $validationErrors = [];

        foreach ($registros as $index => $registro) {
            $validation = \Config\Services::validation();
            $validation->setRules($this->validationRules);

            // Ejecuta la validación para el registro actual
            if (!$validation->run($registro)) {
                // Si hay errores de validación, guarda los errores para este registro
                $validationErrors[$index] = $validation->getErrors();
            }
        }

        return $validationErrors;
    }

    public function getOrdenCabs($num_orden = '', $estado = '100', $sortField = 'num_orden', $sortOrder = 'asc', $offset = 0, $limit = 10)
    {
        $this->from('v_orden_principal'); // Nombre de la vista
        $this->select('estado');
        $this->agregarFiltro($num_orden, $estado, $sortField);
        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        $this->from('v_orden_principal'); // Nombre de la vista
        $this->agregarFiltro($num_orden, $estado, $sortField);
        $totalRecords = $this->countAllResults();
        return ["ordencabs" => $data, "totalRecords" => $totalRecords];
    }
    public function getOrdenCabById($id)
    {
        return $this
            ->select('id, num_ruc, idtipo_persona as tipopersona, nombre_comercial, nombre_fiscal, email, cell, estado')
            ->where('id', $id)
            ->first();
    }
    public function getOrdenCabsDespegable($nombre_comercial = '')
    {
        $this->select('id, nombre_comercial');

        if (!empty ($nombre_comercial)) {
            $this->like('nombre_comercial', $nombre_comercial, 'match');
        }
        $data = $this->orderBy('nombre_comercial', 'asc')
            ->limit(15)
            ->get()
            ->getResult();


        return ["companias" => $data];
    }
    public function getOrdenCabDespegableById($id)
    {
        return $this->select('id, nombre_comercial')
            ->where('id', $id)
            ->first();
    }

    public function getCelularEnvio($id, $envio)
    {
        switch ($envio) {
            case '1':
                return $this->select('paciente.cell')
                    ->join('paciente', 'paciente.id = orden_cab.idpaciente')
                    ->where('orden_cab.id', $id)
                    ->first();                
            case '2':
                return $this->select('medico.cell')
                    ->join('medico', 'medico.id = orden_cab.idmedico')
                    ->where('orden_cab.id', $id)
                    ->first();                
            case '3':
                return $this->select('compania.cell')
                    ->join('compania', 'compania.id = orden_cab.idcompania')
                    ->where('orden_cab.id', $id)
                    ->first();               
        }
    }

    public function getEmailEnvio($id, $envio)
    {
        switch ($envio) {
            case '1':
                return $this->select('paciente.email')
                    ->join('paciente', 'paciente.id = orden_cab.idpaciente')
                    ->where('orden_cab.id', $id)
                    ->first();                
            case '2':
                return $this->select('medico.email')
                    ->join('medico', 'medico.id = orden_cab.idmedico')
                    ->where('orden_cab.id', $id)
                    ->first();                
            case '3':
                return $this->select('compania.email')
                    ->join('compania', 'compania.id = orden_cab.idcompania')
                    ->where('orden_cab.id', $id)
                    ->first();               
        }
    }

    private function agregarFiltro($num_orden = '', $estado = '100', $sortField = 'num_orden')
    {
        if (!empty ($num_orden)):
            $this->like($sortField, $num_orden, 'match');
        endif;

        if ($estado !== '100'):
            $this->where('estado', $estado);
        endif;
    }



}