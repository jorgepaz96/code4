<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class MenbreteController extends ResourceController
{
    use ResponseTrait;
    private $menbreteModel;

    public function __construct() {
        $this->menbreteModel = model('MenbreteModel');
    }
    
    public function index()
    {       
        
        $des_nombre = $this->request->getGet('des_nombre') ?? '';
        $estado = $this->request->getGet('estado') ?? '100';
        $jsonParam = $this->request->getGet('tablaData') ?? '{}';

        try {
            $jsonData = json_decode($jsonParam, true) ?? [];

            if (empty($jsonData)) {
                return $this->failValidationError('No se proporcionaron datos válidos');
            }

            $campoOrden = $jsonData['sortField'] ?? 'des_nombre';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';            

            $respuesta = $this->menbreteModel->getMenbretes(
                $des_nombre,
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
            $data = $this->menbreteModel->getMenbreteById($id);
    
            if (!$data) {
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            }
            $data->estado = $data->estado === '1' ? true : false;
    
            return $this->respond($data, 200);
    
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    // create a product
    public function create()
    {      
        $file = $this->request->getFile('image');
        // var_dump($file->getSize());
        // exit;
        if ($file->isValid() && $file->getSize() < 800000) {
            $newName = $file->getRandomName();
            $path = 'public/uploads/menbrete/';
            // ROOTPATH
            // WRITABLE
            if ($file->move(ROOTPATH. $path,$newName)) {
                $data = [
                    'name' => $newName,
                    'path' => $path
                ];

                

                return $this->respondCreated(['message' => $path.$newName]);
            } else {
                return $this->fail('Error al subir la imagen');
            }
        } else {
            return $this->fail('La imagen es inválida o excede el tamaño máximo permitido');
        }
        return;
        try {
            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failValidationError('No se proporcionaron datos');
            endif;
            if ($this->menbreteModel->save($json)):
                $insertedID = $this->menbreteModel->getInsertID();
                $savedRecord = $this->menbreteModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->menbreteModel->errors(),'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    
    public function update($id = null)
    {             
        try {
            $data = $this->menbreteModel->find($id);
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

            if ($this->menbreteModel->save($data)):
                $savedRecord = $this->menbreteModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->menbreteModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

        
    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->menbreteModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->menbreteModel->cleanValidationRules;


            if ($this->menbreteModel->save($data)):
                $savedRecord = $this->menbreteModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->menbreteModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }  
    }
}