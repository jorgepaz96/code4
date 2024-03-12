<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PacienteEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'idtipo_documento' => null,
        'des_num_documento' => null,
        'des_nombre' => null,
        'ape_pat' => null,
        'ape_mat' => null,
        'des_nombre_completo' => null,        
        'sexo' => null,
        'cell' => null,
        'telefono' => null,
        'email' => null,
        'fec_nacimiento' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null,            
    ];
    
    
}

