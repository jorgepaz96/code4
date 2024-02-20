<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UsuarioEntity extends Entity
{
    // protected $datamap = [];
    protected $attributes = [
        'id' => null,
        'username' => null,
        'email' => null,
        'password' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null        
    ];    
    // protected $casts   = [];
    
}

