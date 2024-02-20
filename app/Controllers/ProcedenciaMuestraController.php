<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProcedenciaMuestraController extends ResourceController
{
    use ResponseTrait;
    private $procedenciaMuestraModel;

    public function __construct()
    {
        $this->procedenciaMuestraModel = model('ProcedenciaMuestraModel');
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $des_nombre = $this->request->getGet('des_nombre') ?? '';
        $estado = $this->request->getGet('estado') ?? '100';
        $jsonParam = $this->request->getGet('tablaData') ?? '{}';

        try {            
            $jsonData = json_decode($jsonParam, true) ?? [];

            if (empty($jsonData)) :
                return $this->failValidationError('No se proporcionaron datos válidos');
            endif;
            // Consulta de data
            $campoOrden = $jsonData['sortField'] ?? 'des_nombre';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';
            
            $this->procedenciaMuestraModel->select('id, des_nombre, estado');
            
            if (!empty($des_nombre)) :
                $this->procedenciaMuestraModel->like('des_nombre', $des_nombre, 'match');
            endif;

            if ($estado !== '100') :
                $this->procedenciaMuestraModel->where('estado', $estado);
            endif;
            
            $data = $this->procedenciaMuestraModel
                ->orderBy($campoOrden, $tipoOrden)
                ->offset($jsonData['first'] ?? 0)
                ->limit($jsonData['rows'] ?? 10)
                ->get()
                ->getResult();
            
            // Consulta de total de registro
            if (isset($des_nombre)) :
                $this->procedenciaMuestraModel->like('des_nombre', $des_nombre, 'match');
            endif;

            if ($estado !== '100') :
                $this->procedenciaMuestraModel->where('estado', $estado);
            endif;
            $totalRecords = $this->procedenciaMuestraModel->countAllResults();

            $respuesta = ["procedenciamuestras" => $data, "totalRecords" => $totalRecords];
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        try {
            $data = $this->procedenciaMuestraModel
                ->select('id, des_nombre, estado')
                ->where('id', $id)
                ->first();

            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;
            $data->estado = $data->estado === '1' ? true : false;


            return $this->respond($data, 200);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    // create a product
    public function create()
    {        
        try {
            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failValidationError('No se proporcionaron datos');
            endif;
            if ($this->procedenciaMuestraModel->save($json)):
                $insertedID = $this->procedenciaMuestraModel->getInsertID();
                $savedRecord = $this->procedenciaMuestraModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->procedenciaMuestraModel->errors(),'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    
    public function update($id = null)
    {             
        try {
            $data = $this->procedenciaMuestraModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failResourceExists('No se proporcionaron datos');
            else:
                $data->fill([
                    'des_nombre' => $json->des_nombre ?? $data->des_nombre,                    
                    'estado' => $json->estado ?? $data->estado
                ]);
            endif;


            if (!$data->hasChanged()):                
                return $this->failResourceExists('No se encontraron cambios');
            endif;

            if ($this->procedenciaMuestraModel->save($data)):
                $savedRecord = $this->procedenciaMuestraModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->procedenciaMuestraModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

        
    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->procedenciaMuestraModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->procedenciaMuestraModel->cleanValidationRules;


            if ($this->procedenciaMuestraModel->save($data)):
                $savedRecord = $this->procedenciaMuestraModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->procedenciaMuestraModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }  
    }
}
