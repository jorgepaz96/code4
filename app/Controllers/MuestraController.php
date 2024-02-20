<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class MuestraController extends ResourceController
{
    use ResponseTrait;
    private $muestraModel;

    public function __construct() {
        $this->muestraModel = model('MuestraModel');
    }
    
    public function index()
    {       
        
        $des_nombre = $this->request->getVar('des_nombre');    
           
        $this->muestraModel->select('id, des_nombre, estado');
        (!isset($des_nombre)) ?: $this->muestraModel->like('des_nombre',$des_nombre,'after');
        $this->muestraModel->where('estado','1');
        $this->muestraModel->limit(5);
        $data = $this->muestraModel->findAll();        
         
        return $this->respond($data, 200);
    }
 
    // get single productW
    public function show($id = null)
    {
        $this->muestraModel->select('id, des_nombre, estado');
        $this->muestraModel->where('id',$id);
        $this->muestraModel->where('estado','1');
        $data = $this->muestraModel->findAll();  

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

        if ($this->muestraModel->save($data) === false) {
            return $this->failValidationErrors($this->muestraModel->errors(),"malo");
        }else {
            $insertedID = $this->muestraModel->getInsertID();
            $savedRecord = $this->muestraModel->find($insertedID);           
            return $this->respondCreated($savedRecord);                
        } 
    }
 
    
    public function update($id = null)
    {             
        $data = $this->muestraModel->find($id);       
        
        if ($data) {        
            $json = $this->request->getJSON();
            if($json){                
                $data->des_nombre = $json->des_nombre;
                $data->estado = $json->estado;
            }                        
            if (!$data->hasChanged()) {
                die('Nothing to change');
            }                   
            if ($this->muestraModel->save($data) === false) {
                return $this->failValidationErrors($this->muestraModel->errors(),"malo");
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
        $data = $this->muestraModel->find($id);

        if($data){
            $this->muestraModel->delete($id);
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