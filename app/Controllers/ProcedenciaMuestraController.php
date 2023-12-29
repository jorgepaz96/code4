<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProcedenciaMuestraController extends ResourceController
{
    use ResponseTrait;
    private $procedenciaMuestraModel;

    public function __construct() {
        $this->procedenciaMuestraModel = model('ProcedenciaMuestraModel');
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $des_nombre = $this->request->getVar('des_nombre');    
           
        $this->procedenciaMuestraModel->select('id, des_nombre, estado');
        (!isset($des_nombre)) ?: $this->procedenciaMuestraModel->like('des_nombre',$des_nombre,'after');
        $this->procedenciaMuestraModel->where('estado','1');
        $this->procedenciaMuestraModel->limit(5);
        $data = $this->procedenciaMuestraModel->findAll();        
         
        return $this->respond($data, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $this->procedenciaMuestraModel->select('id, des_nombre, estado');
        $this->procedenciaMuestraModel->where('id',$id);
        $this->procedenciaMuestraModel->where('estado','1');
        $data = $this->procedenciaMuestraModel->findAll();  

        if($data){
            return $this->respond($data,200);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
    }    

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getJSON();
       
        if ($this->procedenciaMuestraModel->save($data) === false) {
            return $this->failValidationErrors($this->procedenciaMuestraModel->errors(),"malo");
        }else {
            $insertedID = $this->procedenciaMuestraModel->getInsertID();
            $savedRecord = $this->procedenciaMuestraModel->find($insertedID);           
            return $this->respondCreated($savedRecord);                
        } 
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->procedenciaMuestraModel->find($id);       
        
        if ($data) {        
            $json = $this->request->getJSON();
            if($json){                
                $data->des_nombre = $json->des_nombre;
                $data->estado = $json->estado;
            }                        
            if (!$data->hasChanged()) {
                die('Nothing to change');
            }                   
            if ($this->procedenciaMuestraModel->save($data) === false) {
                return $this->failValidationErrors($this->procedenciaMuestraModel->errors(),"malo");
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

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $data = $this->procedenciaMuestraModel->find($id);

        if($data){
            $this->procedenciaMuestraModel->delete($id);
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
