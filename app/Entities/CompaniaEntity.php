<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CompaniaEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'nombre_comercial' => null,
        'nombre_fiscal' => null,
        'idtipo_persona' => null,
        'num_ruc' => null,
        'email' => null,
        'cell' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null        
    ];
    
    
}
