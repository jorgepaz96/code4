<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\OrdenCabEntity;

class OrdenCabVistaModel extends Model
{
    protected $table = 'v_orden_principal';
    protected $primaryKey = 'id';
    protected $returnType = OrdenCabEntity::class;



    public function getOrdenCabs(
        $num_orden = '',
        $estado = '100',
        $anio = '',
        $estudio = '',
        $compania = '',
        $medico = '',
        $paciente = '',
        $sortField = 'num_orden',
        $sortOrder = 'asc',
        $offset = 0,
        $limit = 10
    ) {


        $this->select('*');
        $this->agregarFiltro(
            $num_orden,
            $estado,
            $anio,
            $estudio,
            $compania,
            $medico,
            $paciente,
            $sortField
        );

        $data = $this
            ->orderBy($sortField, $sortOrder)
            ->offset($offset)
            ->limit($limit)
            ->get()
            ->getResult();

        
        // echo $this->getLastQuery();
        $this->agregarFiltro(
            $num_orden,
            $estado,
            $anio,
            $estudio,
            $compania,
            $medico,
            $paciente,
            $sortField
        );
        $totalRecords = $this->countAllResults();
        return ["ordencabs" => $data, "totalRecords" => $totalRecords];
    }

    public function getOrdenCabsByID($id)
    {
        return $this
            ->select('*')                
            ->where('id', $id)
            ->first();
    }

    private function agregarFiltro(
        $num_orden = '',
        $estado = '100',
        $anio = '',
        $estudio = '',
        $compania = '',
        $medico = '',
        $paciente = '',
        $sortField = 'num_orden'
    ) {
        if (!empty ($num_orden)):
            $this->like($sortField, $num_orden, 'match');
        endif;

        if ($estado !== '100'):
            $this->where('estado', $estado);
        endif;

        if (!empty ($anio)):
            $this->where('anio', $anio);
        endif;

        if (!empty ($estudio)):
            $this->where('idestudio', $estudio);
        endif;

        if (!empty ($compania)):
            $this->where('idcompania', $compania);
        endif;

        if (!empty ($medico)):
            $this->where('idmedico', $medico);
        endif;

        if (!empty ($paciente)):
            $this->where('idpaciente', $paciente);
        endif;
    }



}