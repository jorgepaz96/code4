<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class TrabajadorController extends ResourceController
{
    use ResponseTrait;
    private $trabajadorModel;

    public function __construct()
    {
        $this->trabajadorModel = model('TrabajadorModel');
    }

    public function index()
    {

        $des_nombre = $this->request->getGet('des_nombre_completo') ?? '';
        $estado = $this->request->getGet('estado') ?? '100';
        $jsonParam = $this->request->getGet('tablaData') ?? '{}';

        try {
            $jsonData = json_decode($jsonParam, true) ?? [];

            if (empty($jsonData)) {
                return $this->failValidationError('No se proporcionaron datos válidos');
            }

            $campoOrden = $jsonData['sortField'] ?? 'des_nombre';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';            

            $respuesta = $this->trabajadorModel->getTrabajadores(
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
            $data = $this->trabajadorModel->getTrabajadorById($id);
    
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
            $des_nombre_completo = trim($json->ape_pat).' '.trim($json->ape_mat).' '.trim($json->des_nombre);
            
            $data = [                
                'des_nombre' => $json->des_nombre,
                'ape_pat' => $json->ape_pat,
                'ape_mat' => $json->ape_mat,
                'des_nombre_completo' => $des_nombre_completo,
                'sexo' => $json->sexo->cod,
                'cell' => $json->cell,
                'email' => $json->email,
                'estado' => $json->estado,
            ]; 
            if ($this->trabajadorModel->save($json)):
                $insertedID = $this->trabajadorModel->getInsertID();
                $savedRecord = $this->trabajadorModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->trabajadorModel->errors(), 'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }


    public function update($id = null)
    {
        try {
            $data = $this->trabajadorModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failResourceExists('No se proporcionaron datos');
            else:
                $des_nombre_completo = trim($json->ape_pat).' '.trim($json->ape_mat).' '.trim($json->des_nombre);
                $data->fill([                    
                    'des_nombre' => $json->des_nombre ?? $data->des_nombre,
                    'ape_pat' => $json->ape_pat ?? $data->ape_pat,
                    'ape_mat' => $json->ape_mat ?? $data->ape_mat,
                    'des_nombre_completo' => $des_nombre_completo ?? $data->des_nombre_completo,                    
                    'sexo' => $json->sexo->cod ?? $data->sexo,
                    'cell' => $json->cell ?? $data->cell,                    
                    'email' => $json->email ?? $data->email,
                    'estado' => $json->estado ?? $data->estado   
                ]);
            endif;


            if (!$data->hasChanged()):
                return $this->failResourceExists('No se encontraron cambios');
            endif;

            if ($this->trabajadorModel->save($data)):
                $savedRecord = $this->trabajadorModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->trabajadorModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }


    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->trabajadorModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->trabajadorModel->cleanValidationRules;

            if ($this->trabajadorModel->save($data)):
                $savedRecord = $this->trabajadorModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->trabajadorModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
}