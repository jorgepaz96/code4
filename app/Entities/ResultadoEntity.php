<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ResultadoEntity extends Entity
{
    // protected $datamap = [];
    protected $attributes = [
        'id' => null,        
        'des_resultado' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null,
        'idetiqueta' => null
    ];            
}

