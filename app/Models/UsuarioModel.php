<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\UsuarioEntity;

class UsuarioModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = UsuarioEntity::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['username', 'email', 'password', 'estado'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'aud_fecha_registra';
    protected $updatedField = 'aud_fecha_actualiza';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'username' => 'required|max_length[15]|is_unique[usuario.username,id,{$id}]',
        'email' => 'required|valid_email|max_length[150]|is_unique[usuario.email,id,{$id}]',
        'password' => 'required|min_length[5]|max_length[255]',
        'estado' => 'in_list[0,1]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setUsuarioI','beforeInsert'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['setUsuarioU','beforeUpdate'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    
    function beforeInsert(array $data) : array {        
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    function beforeUpdate(array $data) : array {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    private function getUpdatedDataWithHashedPassword(array $data) : array {
        if (isset($data['data']['password'])) {
            $plainTextPassword = $data['data']['password'];
            $data['data']['password'] = password_hash($plainTextPassword,PASSWORD_BCRYPT);
        }
        return $data;
    }

    function findUserByEmailAddress(string $emailAddress) {
        $usuario = $this->asArray()->where(['email'=>$emailAddress])->first();
        if (!$usuario) {
            throw new \Exception('Usuario no existe');
        }
        return $usuario;
    }


    protected function setUsuarioI(array $data)
    {
        $data["data"]['aud_usuario_registra'] = 'jpazm';
        return $data;
    }
    protected function setUsuarioU(array $data)
    {
        $data["data"]['aud_usuario_actualiza'] = 'jpazm';
        return $data;
    }
}
