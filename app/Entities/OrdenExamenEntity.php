<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class OrdenExamenEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'idordencab' => null,
        'idexamen' => null,
        'cantidad' => null,
        'precio' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null
        
    ];

}