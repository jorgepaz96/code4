<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class OrdenMuestraEntity extends Entity
{
    protected $attributes = [
        'idordenexamen' => null,
        'idmuestra' => null,
        'idprocedenciamuestra' => null,
        'estado' => null,      
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null        
    ];
   

}