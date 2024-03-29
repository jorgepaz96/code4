<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class CompaniaController extends ResourceController
{
    use ResponseTrait;
    private $companiaModel;
    private $tipoPersonaModel;

    public function __construct() {
        $this->companiaModel = model('CompaniaModel');
        $this->tipoPersonaModel = model('TipoPersonaModel');
    }
    
    public function index()
    {   
        $nombre_comercial = $this->request->getGet('nombre_comercial') ?? '';
        $estado = $this->request->getGet('estado') ?? '100';
        $jsonParam = $this->request->getGet('tablaData') ?? '{}';


        try {            
            $jsonData = json_decode($jsonParam, true) ?? [];

            if (empty($jsonData)) {
                return $this->failValidationError('No se proporcionaron datos válidos');
            }

            $campoOrden = $jsonData['sortField'] ?? 'nombre_comercial';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';            

            $respuesta = $this->companiaModel->getCompanias(
                $nombre_comercial,
                $estado,
                $campoOrden,
                $tipoOrden,
                $jsonData['first'] ?? 0,
                $jsonData['rows'] ?? 10
            );
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {            
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
        
    }
 
    // get single productW
    public function show($id = null)
    {        
        try {
            $data = $this->companiaModel->getCompaniaById($id);    
            if (!$data) {
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            }
            $data->estado = $data->estado === '1' ? true : false;
            $data->tipopersona = $this->tipoPersonaModel->getTipoPersonaById($data->tipopersona);
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
            $data = [
                'num_ruc' => $json->num_ruc,
                'idtipo_persona' => $json->tipopersona->id,
                'nombre_comercial' => $json->nombre_comercial,
                'nombre_fiscal' => $json->nombre_fiscal,
                'email' => $json->email,
                'cell' => $json->cell,
                'estado' => $json->estado,                
            ];                       
            if ($this->companiaModel->save($data)):
                $insertedID = $this->companiaModel->getInsertID();
                $savedRecord = $this->companiaModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->companiaModel->errors(),'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }       
    }
 
    
    public function update($id = null)
    {
        try {
            $data = $this->companiaModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failResourceExists('No se proporcionaron datos');
            else:
                $data->fill([
                    'num_ruc' => $json->num_ruc ?? $data->num_ruc,
                    'idtipo_persona' => $json->tipopersona->id ?? $data->idtipo_persona,
                    'nombre_comercial' => $json->nombre_comercial ?? $data->nombre_comercial,
                    'nombre_fiscal' => $json->nombre_fiscal ?? $data->nombre_fiscal,
                    'email' => $json->email ?? $data->email,
                    'cell' => $json->cell ?? $data->cell,                    
                    'estado' => $json->estado ?? $data->estado
                ]);
            endif;


            if (!$data->hasChanged()):                
                return $this->failResourceExists('No se encontraron cambios');
            endif;

            if ($this->companiaModel->save($data)):
                $savedRecord = $this->companiaModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->companiaModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }        
    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->companiaModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->companiaModel->cleanValidationRules;

            if ($this->companiaModel->save($data)):
                $savedRecord = $this->companiaModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->companiaModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }           
    }
    private function getTipoPersona($id = null):object | null{
        $tipoPersonaModel = model('TipoPersonaModel');
        $data = $tipoPersonaModel
                ->select('id, des_nombre')
                ->where('id', $id)
                ->first();
        return $data;
    }
    public function listaDespegableById($id = null)
    {        
        try {
            $data = $this->companiaModel->getCompaniaDespegableById($id);

            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;
            return $this->respond($data, 200);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }
    public function listaDespegable()
    {
        
        $nombre_comercial = $this->request->getGet('nombre_comercial') ?? '';        

        try {
            $respuesta = $this->companiaModel->getCompaniasDespegable($nombre_comercial);
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }
}