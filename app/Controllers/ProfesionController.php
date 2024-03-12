<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProfesionController extends ResourceController
{
    use ResponseTrait;
    private $profesionModel;

    public function __construct() {
        $this->profesionModel = model('ProfesionModel');
    }
    
    // public function index()
    // {
    //     try { 
    //         $data = $this->profesionModel
    //             ->select('id, des_nombre')                
    //             ->get()
    //             ->getResult();
            
    //         $totalRecords = $this->tipoPersonaModel->countAllResults();

    //         $respuesta = ["tipopersonas" => $data, "totalRecords" => $totalRecords];
    //         return $this->respond($respuesta, 200);
    //     } catch (\Exception $e) {            
    //         return $this->failServerError('Ha ocurrido un error en el servidor');
    //     }
    // } 
    
    public function show($id = null)
    {        
        try {
            $data = $this->profesionModel->getProfesionById($id);
            
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
            $data = $this->profesionModel->getProfesionTodos();                        
            return $this->respond(["profesiones"=>$data], 200);
        } catch (\Exception $e) {            
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
 
    
}