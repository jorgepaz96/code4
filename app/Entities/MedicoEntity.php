<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class MedicoEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'des_nombre' => null,
        'ape_pat' => null,
        'ape_mat' => null,
        'des_nombre_completo' => null,
        'idprofesion' => null,
        'sexo' => null,
        'cell' => null,
        'telefono' => null,
        'email' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null,            
    ];
    
    
}