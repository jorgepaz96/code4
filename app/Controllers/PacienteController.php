<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class PacienteController extends ResourceController
{
    use ResponseTrait;
    private $pacienteModel;
    private $tipoDocumentoModel;

    public function __construct() {
        $this->pacienteModel = model('PacienteModel');
        $this->tipoDocumentoModel = model('TipoDocumentoModel');
    }
    
    public function index()
    {   
        $des_nombre_completo = $this->request->getGet('des_nombre_completo') ?? '';
        $estado = $this->request->getGet('estado') ?? '100';
        $jsonParam = $this->request->getGet('tablaData') ?? '{}';

        try {            
            $jsonData = json_decode($jsonParam, true) ?? [];
            
            if (empty($jsonData)) {
                return $this->failValidationError('No se proporcionaron datos válidos');
            }
            
            $campoOrden = $jsonData['sortField'] ?? 'des_nombre_completo';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';            
            
            $respuesta = $this->pacienteModel->getPacientes(
                $des_nombre_completo,
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
            $data = $this->pacienteModel->getPacienteById($id);    
            if (!$data) {
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            }
            $data->estado = $data->estado === '1' ? true : false;            
            $data->tipo_documento = $this->tipoDocumentoModel->getTipoDocumentoById($data->tipo_documento);
            
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
            if ($json->fec_nacimiento) {
                $fecha = $json->fec_nacimiento;
                $date = new \DateTime($fecha);
                $fec_nacimiento = $date->format('Y-m-d');                
            }else{
                $fec_nacimiento = null;
            }            
            $data = [                
                'des_nombre' => $json->des_nombre,
                'ape_pat' => $json->ape_pat,
                'ape_mat' => $json->ape_mat,
                'des_nombre_completo' => $des_nombre_completo,
                'idtipo_documento' => $json->tipo_documento->id,
                'des_num_documento' => $json->des_num_documento,
                'sexo' => $json->sexo->cod,
                'cell' => $json->cell,
                'telefono' => $json->telefono,
                'email' => $json->email,
                'fec_nacimiento' => $fec_nacimiento,
                'estado' => $json->estado,
            ];                                 
            if ($this->pacienteModel->save($data)):
                $insertedID = $this->pacienteModel->getInsertID();
                $savedRecord = $this->pacienteModel->find($insertedID);
                return $this->respondCreated($savedRecord);
            else:
                return $this->failValidationErrors($this->pacienteModel->errors(),'Validación de formulario');
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }       
    }
 
    
    public function update($id = null)
    {
        try {
            $data = $this->pacienteModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failResourceExists('No se proporcionaron datos');
            else:
                $des_nombre_completo = trim($json->ape_pat).' '.trim($json->ape_mat).' '.trim($json->des_nombre);
                if ($json->fec_nacimiento) {
                    $fecha = $json->fec_nacimiento;
                    $date = new \DateTime($fecha);
                    $fec_nacimiento = $date->format('Y-m-d');                
                }else{
                    $fec_nacimiento = null;
                }  

                $data->fill([
                    'des_nombre' => $json->des_nombre ?? $data->des_nombre,
                    'ape_pat' => $json->ape_pat ?? $data->ape_pat,
                    'ape_mat' => $json->ape_mat ?? $data->ape_mat,
                    'des_nombre_completo' => $des_nombre_completo ?? $data->des_nombre_completo,
                    'idtipo_documento' => $json->tipo_documento->id ?? $data->idtipo_documento,
                    'des_num_documento' => $json->des_num_documento ?? $data->des_num_documento,
                    'sexo' => $json->sexo->cod ?? $data->sexo,
                    'cell' => $json->cell ?? $data->cell,
                    'telefono' => $json->telefono ?? $data->telefono,
                    'email' => $json->email ?? $data->email,
                    'fec_nacimiento' => $fec_nacimiento ?? $data->fec_nacimiento,
                    'estado' => $json->estado ?? $data->estado                              
                ]);
            endif;


            if (!$data->hasChanged()):                
                return $this->failResourceExists('No se encontraron cambios');
            endif;

            if ($this->pacienteModel->save($data)):
                $savedRecord = $this->pacienteModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->pacienteModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }        
    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->pacienteModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->pacienteModel->cleanValidationRules;

            if ($this->pacienteModel->save($data)):
                $savedRecord = $this->pacienteModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->pacienteModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }           
    }
    public function listaDespegableById($id = null)
    {        
        try {
            $data = $this->pacienteModel->getPacienteDespegableById($id);

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
        $des_nombre_completo = $this->request->getGet('des_nombre_completo') ?? '';        
        try {
            $respuesta = $this->pacienteModel->getPacientesDespegable($des_nombre_completo);
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }
} 