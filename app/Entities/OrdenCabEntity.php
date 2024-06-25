<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class OrdenCabEntity extends Entity
{
    protected $attributes = [
        'id' => null,
        'anio' => null,
        'idestudio' => null,
        'num_orden' => null,
        'idcompania' => null,
        'idmedico' => null,
        'idpaciente' => null,
        'estado_publicado' => null,
        'fecha_publicado' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null
    ];

}
