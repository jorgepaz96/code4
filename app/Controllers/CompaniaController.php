<?php
namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class CompaniaController extends ResourceController
{
    use ResponseTrait;
    private $companiaModel;

    public function __construct() {
        $this->companiaModel = model('CompaniaModel');
    }
    
    public function index()
    {   
        $nombre_comercial = $this->request->getVar('nombre_comercial');    
           
        $this->companiaModel->select('id, num_ruc, idtipo_persona, nombre_comercial, nombre_fiscal, email, cell, estado');
        (!isset($nombre_comercial)) ?: $this->companiaModel->like('nombre_comercial',$nombre_comercial,'after');
        $this->companiaModel->where('estado','1');
        $this->companiaModel->limit(5);
        $data = $this->companiaModel->findAll();        
         
        return $this->respond($data, 200);
    }
 
    // get single productW
    public function show($id = null)
    {
        $this->companiaModel->select('id, num_ruc, idtipo_persona, nombre_comercial, nombre_fiscal, email, cell, estado');
        $this->companiaModel->where('id',$id);
        $this->companiaModel->where('estado','1');
        $data = $this->companiaModel->findAll();  

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

        if ($this->companiaModel->save($data) === false) {
            return $this->failValidationErrors($this->companiaModel->errors(),"malo");
        }else {
            $insertedID = $this->companiaModel->getInsertID();
            $savedRecord = $this->companiaModel->find($insertedID);           
            return $this->respondCreated($savedRecord);                
        } 
    }
 
    
    public function update($id = null)
    {             
        $data = $this->companiaModel->find($id);
                
        if ($data) {        
            $json = $this->request->getJSON();
            if($json){                
                $data->num_ruc = $json->num_ruc;
                $data->idtipo_persona = $json->idtipo_persona;
                $data->nombre_comercial = $json->nombre_comercial;
                $data->nombre_fiscal = $json->nombre_fiscal;
                $data->email = $json->email;
                $data->cell = $json->cell;
                $data->estado = $json->estado;
            }                        
            if (!$data->hasChanged()) {
                die('Nothing to change');
            }                   
            if ($this->companiaModel->save($data) === false) {
                return $this->failValidationErrors($this->companiaModel->errors(),"malo");
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
        $data = $this->companiaModel->find($id);

        if($data){
            $this->companiaModel->delete($id);
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