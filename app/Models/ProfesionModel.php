<?php
namespace App\Models;

use App\Entities\ProfesionEntity;
use CodeIgniter\Model;


class ProfesionModel extends Model
{
    protected $table = 'profesion';
    protected $primaryKey = 'id';
    protected $returnType = ProfesionEntity::class;
    
    protected $allowedFields = ['des_nombre','estado'];

    public function getProfesionById($id)
    {
        return $this->select('id, des_nombre, abrev_m, abrev_f, estado')
            ->where('id', $id)
            ->first();
    }
    public function getProfesionTodos()
    {
        return $this
            ->select('id, des_nombre, abrev_m, abrev_f, estado')                
            ->get()
            ->getResult();
    }  
    
}