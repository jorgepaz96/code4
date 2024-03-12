<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ExamenController extends ResourceController
{
    use ResponseTrait;
    private $examenModel;

    public function __construct()
    {
        $this->examenModel = model('ExamenModel');
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

            $respuesta = $this->examenModel->getExamenes(
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
            $data = $this->examenModel->getExamenById($id);
    
            if (!$data) {
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            }
    
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
            if ($this->examenModel->save($json)):
                $insertedID = $this->examenModel->getInsertID();
                $savedRecord = $this->examenModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->examenModel->errors(), 'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }


    public function update($id = null)
    {
        try {
            $data = $this->examenModel->find($id);
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

            if ($this->examenModel->save($data)):
                $savedRecord = $this->examenModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->examenModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }


    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->examenModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->examenModel->cleanValidationRules;

            if ($this->examenModel->save($data)):
                $savedRecord = $this->examenModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->examenModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
}