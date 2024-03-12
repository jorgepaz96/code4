<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class OrdenController extends ResourceController
{
    use ResponseTrait;
    private $muestraModel;

    public function __construct() {
        $this->muestraModel = model('MuestraModel');
    }
    
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
            
            $this->muestraModel->select('id, des_nombre, estado');
            
            if (!empty($des_nombre)) :
                $this->muestraModel->like('des_nombre', $des_nombre, 'match');
            endif;

            if ($estado !== '100') :
                $this->muestraModel->where('estado', $estado);
            endif;
            
            $data = $this->muestraModel
                ->orderBy($campoOrden, $tipoOrden)
                ->offset($jsonData['first'] ?? 0)
                ->limit($jsonData['rows'] ?? 10)
                ->get()
                ->getResult();
            
            // Consulta de total de registro
            if (isset($des_nombre)) :
                $this->muestraModel->like('des_nombre', $des_nombre, 'match');
            endif;

            if ($estado !== '100') :
                $this->muestraModel->where('estado', $estado);
            endif;
            $totalRecords = $this->muestraModel->countAllResults();

            $respuesta = ["ordenes" => $data, "totalRecords" => $totalRecords];
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    // get single productW
    public function show($id = null)
    {
        try {
            $data = $this->muestraModel
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
            if ($this->muestraModel->save($json)):
                $insertedID = $this->muestraModel->getInsertID();
                $savedRecord = $this->muestraModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->muestraModel->errors(),'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    
    public function update($id = null)
    {             
        try {
            $data = $this->muestraModel->find($id);
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

            if ($this->muestraModel->save($data)):
                $savedRecord = $this->muestraModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->muestraModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

        
    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->muestraModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->muestraModel->cleanValidationRules;


            if ($this->muestraModel->save($data)):
                $savedRecord = $this->muestraModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->muestraModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }  
    }
}