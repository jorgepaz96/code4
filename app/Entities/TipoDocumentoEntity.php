<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class TipoDocumentoEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'des_nombre' => null,
        'des_nombre_corto' => null,
        'longitud' => null,
        'tipo_dato' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null        
    ];
    
    
}
