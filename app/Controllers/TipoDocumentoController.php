<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class TipoDocumentoController extends ResourceController
{
    use ResponseTrait;
    private $tipoDocumentoModel;

    public function __construct() {
        $this->tipoDocumentoModel = model('TipoDocumentoModel');
    }
    public function show($id = null)
    {        
        try {
            $data = $this->tipoDocumentoModel->getTipoDocumentoById($id);
            
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
            $data = $this->tipoDocumentoModel->getTipoDocumentoTodos();                        
            return $this->respond(["tipodocumentos"=>$data], 200);
        } catch (\Exception $e) {            
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    
}