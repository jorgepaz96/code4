<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class EstudioController extends ResourceController
{
    use ResponseTrait;
    private $estudioModel;

    public function __construct() {
        $this->estudioModel = model('EstudioModel');
    } 
    
    public function listaDespegableById($id = null)
    {        
        try {
            $data = $this->estudioModel->getEstudioDespegableById($id);

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
            $respuesta = $this->estudioModel->getEstudiosDespegable($des_nombre);
            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {            
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }

    }
}