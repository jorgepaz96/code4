<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class TipoPersonaController extends ResourceController
{
    use ResponseTrait;
    private $tipoPersonaModel;

    public function __construct() {
        $this->tipoPersonaModel = model('TipoPersonaModel');
    }
    
    public function index()
    {
        try { 
            $data = $this->tipoPersonaModel
                ->select('id, des_nombre')                
                ->get()
                ->getResult();
            
            $totalRecords = $this->tipoPersonaModel->countAllResults();

            $respuesta = ["tipopersonas" => $data, "totalRecords" => $totalRecords];
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {            
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    } 
    
    public function show($id = null)
    {        
        try {
            $data = $this->tipoPersonaModel->getTipoPersonaById($id);
            
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            return $this->respond($data, 200);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
    public function getTodos() {        
        try { 
            $data = $this->tipoPersonaModel->getTipoPersonaTodos();                        
            return $this->respond(["tipopersonas"=>$data], 200);
        } catch (\Exception $e) {            
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    
}