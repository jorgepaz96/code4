<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Entities\ExamenEntity;

class ExamenController extends ResourceController
{
    use ResponseTrait;
    private $examenModel;

    public function __construct() {
        $this->examenModel = model('ExamenModel');
    }
    
    public function index()
    {       
        
        $des_nombre = $this->request->getVar('des_nombre');    
           
        $this->examenModel->select('id, des_nombre, estado');
        (!isset($des_nombre)) ?: $this->examenModel->like('des_nombre',$des_nombre,'after');
        $this->examenModel->where('estado','1');
        $this->examenModel->limit(5);
        $data = $this->examenModel->findAll();        
         
        return $this->respond($data, 200);
    }
 
    // get single productW
    public function show($id = null)
    {
        $this->examenModel->select('id, des_nombre, estado');
        $this->examenModel->where('id',$id);
        $this->examenModel->where('estado','1');
        $data = $this->examenModel->findAll();  

        if($data){
            return $this->respond($data,200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }
 
    // create a product
    public function create()
    {        
        $data = $this->request->getJSON();

        if ($this->examenModel->save($data) === false) {
            return $this->failValidationErrors($this->examenModel->errors(),"malo");
        }else {
            $insertedID = $this->examenModel->getInsertID();
            $savedRecord = $this->examenModel->find($insertedID);           
            return $this->respondCreated($savedRecord);                
        } 
    }
 
    
    public function update($id = null)
    {             
        $data = $this->examenModel->find($id);       
        
        if ($data) {        
            $json = $this->request->getJSON();
            if($json){                
                $data->des_nombre = $json->des_nombre;
                $data->estado = $json->estado;
            }                        
            if (!$data->hasChanged()) {
                die('Nothing to change');
            }                   
            if ($this->examenModel->save($data) === false) {
                return $this->failValidationErrors($this->examenModel->errors(),"malo");
            }else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => [
                        'success' => 'Data Updated'
                    ]
                ];
                return $this->respondUpdated($response);            
            }
        }else{
            echo "no existe";
        }

        
    }

    // delete product
    public function delete($id = null)
    {
        $data = $this->examenModel->find($id);

        if($data){
            $this->examenModel->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }    
    }
}