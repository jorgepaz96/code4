<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PlantillaEntity extends Entity
{
    // protected $datamap = [];
    protected $attributes = [
        'id' => null,
        'des_nombre' => null,
        'des_plantilla' => null,
        'estado' => null,
        'aud_fecha_registra' => null,
        'aud_usuario_registra' => null,
        'aud_fecha_actualiza' => null,
        'aud_usuario_actualiza' => null        
    ];    
    // protected $casts   = [];
    protected function setDes_Nombre(string $des_nombre): PlantillaEntity
    {        
        $this->attributes['des_nombre'] = strtoupper($des_nombre);
        return $this;
    }
}

