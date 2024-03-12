<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ResultadoController extends ResourceController
{
    use ResponseTrait;
    private $resultadoModel;
    private $etiquetaModel;

    public function __construct()
    {
        $this->resultadoModel = model('ResultadoModel');
        $this->etiquetaModel = model('EtiquetaModel');
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $des_resultado = $this->request->getGet('des_resultado') ?? '';
        $idetiqueta = $this->request->getGet('idetiqueta') ?? '';
        $estado = $this->request->getGet('estado') ?? '100';
        $jsonParam = $this->request->getGet('tablaData') ?? '{}';

        try {
            $jsonData = json_decode($jsonParam, true) ?? [];

            if (empty($jsonData)) {
                return $this->failValidationError('No se proporcionaron datos válidos');
            }

            $campoOrden = $jsonData['sortField'] ?? 'des_resultado';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';

            $respuesta = $this->resultadoModel->getResultados(
                $idetiqueta,
                $des_resultado,
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
            $data = $this->resultadoModel->getResultadoById($id);

            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;
            $data->estado = $data->estado === '1' ? true : false;
            $data->etiqueta = $this->etiquetaModel->getEtiquetaDespegableById($data->etiqueta);
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
            $data = [
                'des_resultado' => $json->des_resultado,
                'idetiqueta' => $json->etiqueta->id,
                'estado' => $json->estado,
            ];
            if ($this->resultadoModel->save($data)):
                $insertedID = $this->resultadoModel->getInsertID();
                $savedRecord = $this->resultadoModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->resultadoModel->errors(), 'Validación de formulario');
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
            $data = $this->resultadoModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failResourceExists('No se proporcionaron datos');
            else:
                $data->fill([
                    'des_resultado' => $json->des_resultado ?? $data->des_resultado,
                    'idetiqueta' => $json->etiqueta->id ?? $data->idetiqueta,
                    'estado' => $json->estado ?? $data->estado,
                ]);
            endif;


            if (!$data->hasChanged()):
                return $this->failResourceExists('No se encontraron cambios');
            endif;

            if ($this->resultadoModel->save($data)):
                $savedRecord = $this->resultadoModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->resultadoModel->errors());
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
            $data = $this->resultadoModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->resultadoModel->cleanValidationRules;


            if ($this->resultadoModel->save($data)):
                $savedRecord = $this->resultadoModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->resultadoModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
    public function listaDespegableById($id = null)
    {
        try {
            $data = $this->resultadoModel->getResultadoDespegableById($id);

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

        $des_resultado = $this->request->getGet('des_resultado') ?? '';

        try {
            $respuesta = $this->resultadoModel->getResultadosDespegable($des_resultado);
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }
}
