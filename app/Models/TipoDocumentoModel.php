<?php
namespace App\Models;

use App\Entities\TipoDocumentoEntity;
use CodeIgniter\Model;


class TipoDocumentoModel extends Model
{
    protected $table = 'tipo_documento';
    protected $primaryKey = 'id';
    protected $returnType = TipoDocumentoEntity::class;
    
    protected $allowedFields = ['des_nombre','estado'];

    public function getTipoDocumentoById($id)
    {
        return $this->select('id, des_nombre, des_nombre_corto, estado')
            ->where('id', $id)
            ->first();
    }
    public function getTipoDocumentoTodos()
    {
        return $this
            ->select('id, des_nombre, des_nombre_corto, estado')                
            ->get()
            ->getResult();
    }  
    
}