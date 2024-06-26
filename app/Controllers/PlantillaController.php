<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class PlantillaController extends ResourceController
{
    use ResponseTrait;
    private $plantillaModel;    

    public function __construct()
    {
        $this->plantillaModel = model('PlantillaModel');
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

            if (empty($jsonData)) {
                return $this->failValidationError('No se proporcionaron datos válidos');
            }

            $campoOrden = $jsonData['sortField'] ?? 'des_nombre';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';            

            $respuesta = $this->plantillaModel->getPlantillas(
                $des_nombre,
                $estado,
                $campoOrden,
                $tipoOrden,
                $jsonData['first'] ?? 0,
                $jsonData['rows'] ?? 10
            );

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
            $data = $this->plantillaModel->getPlantillaById($id);

            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;
            $data->estado = $data->estado === '1' ? true : false;

            return $this->respond($data, 200);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {        
        try {
            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failValidationError('No se proporcionaron datos');
            endif;
            if ($this->plantillaModel->save($json)):
                $insertedID = $this->plantillaModel->getInsertID();
                $savedRecord = $this->plantillaModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->plantillaModel->errors(),'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        try {
            $data = $this->plantillaModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failResourceExists('No se proporcionaron datos');
            else:
                $data->fill([
                    'des_nombre' => $json->des_nombre ?? $data->des_nombre,
                    'des_plantilla' => $json->des_plantilla ?? $data->des_plantilla,
                    'estado' => $json->estado ?? $data->estado
                ]);
            endif;


            if (!$data->hasChanged()):                
                return $this->failResourceExists('No se encontraron cambios');
            endif;

            if ($this->plantillaModel->save($data)):
                $savedRecord = $this->plantillaModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->plantillaModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }



    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        try {
            $data = $this->plantillaModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->plantillaModel->cleanValidationRules;


            if ($this->plantillaModel->save($data)):
                $savedRecord = $this->plantillaModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->plantillaModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
    public function listaDespegableById($id = null)
    {        
        try {
            $data = $this->plantillaModel->getPlantillaDespegableById($id);

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
        $des_nombre = $this->request->getGet('des_nombre') ?? '';        

        try {
            $respuesta = $this->plantillaModel->getPlantillasDespegable($des_nombre);
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }
}
