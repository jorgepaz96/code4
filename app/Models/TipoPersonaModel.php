<?php
namespace App\Models;

use App\Entities\TipoPersonaEntity;
use CodeIgniter\Model;


class TipoPersonaModel extends Model
{
    protected $table = 'tipo_persona';
    protected $primaryKey = 'id';
    protected $returnType = TipoPersonaEntity::class;
    
    protected $allowedFields = ['des_nombre','estado'];

    public function getTipoPersonaById($id)
    {
        return $this->select('id, des_nombre, estado')
            ->where('id', $id)
            ->first();
    }
    public function getTipoPersonaTodos()
    {
        return $this
            ->select('id, des_nombre, estado')                
            ->get()
            ->getResult();
    }  
    
}