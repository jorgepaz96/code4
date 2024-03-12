<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class MenbreteEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'des_nombre' => null,
        'des_ruta' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null        
    ];
    
    
}
