<?php
namespace App\Controllers;

use App\Models\OrdenCabModel;
use App\Models\OrdenExamenModel;
use App\Models\OrdenMuestraModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Dompdf\Dompdf;
use Dompdf\Options;


class OrdenCabController extends ResourceController
{
    use ResponseTrait;
    private $ordenCabModel;

    public function __construct()
    {

        $this->ordenCabModel = model('OrdenCabModel');

    }

    public function index()
    {
        $ordenCabVistaModel = model('OrdenCabVistaModel');

        $jsonParam = $this->request->getGet('tablaData') ?? '{}';

        $num_orden = $this->request->getGet('num_orden') ?? '';
        $estado = $this->request->getGet('estado') ?? '100';
        $anio = $this->request->getGet('anio') ?? '';
        $estudio = $this->request->getGet('estudio') ?? '';
        $compania = $this->request->getGet('compania') ?? '';
        $medico = $this->request->getGet('medico') ?? '';
        $paciente = $this->request->getGet('paciente') ?? '';

        try {
            $jsonData = json_decode($jsonParam, true) ?? [];

            if (empty($jsonData)) {
                return $this->failValidationError('No se proporcionaron datos válidos');
            }

            $campoOrden = $jsonData['sortField'] ?? 'num_orden';
            $tipoOrden = $jsonData['sortOrder'] == '1' ? 'asc' : 'desc';

            $respuesta = $ordenCabVistaModel->getOrdenCabs(
                $num_orden,
                $estado,
                $anio,
                $estudio,
                $compania,
                $medico,
                $paciente,
                $campoOrden,
                $tipoOrden,
                $jsonData['first'] ?? 0,
                $jsonData['rows'] ?? 10
            );

            return $this->respond($respuesta, 200);
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    // get single productW
    public function show($id = null)
    {
        $ordenCabVistaModel = model('OrdenCabVistaModel');
        try {
            $data = $ordenCabVistaModel->getOrdenCabsByID($id);
            if (!$data) {
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            }
            return $this->respond($data, 200);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }

    // create a product
    public function create()
    {
        $db = db_connect();


        $ordenCabModel = new OrdenCabModel();
        $ordenExamenModel = new OrdenExamenModel();
        $ordenMuestraModel = new OrdenMuestraModel();

        $validationErrorsCab = [];
        $dataResultado = [];

        $datacab = $this->request->getJSON();
        foreach ($datacab as $indexCab => $valueCab) {
            $ordenCabData = [
                "anio" => '2024',
                "idestudio" => ($valueCab->estudio) ? $valueCab->estudio->id : '',
                "num_orden" => '',
                "idcompania" => (isset($valueCab->compania)) ? $valueCab->compania->id : '',
                "idmedico" => (isset($valueCab->medico)) ? $valueCab->medico->id : '',
                "idpaciente" => (isset($valueCab->paciente)) ? $valueCab->paciente->id : '',
                "estado" => '1'
            ];

            if (!$ordenCabModel->validate($ordenCabData)) {
                $errors = $ordenCabModel->errors();
                $errors['uniqueID'] = $valueCab->uniqueID;
                $validationErrorsCab[] = ['ordenCab' => $errors];
            }

            foreach ($valueCab->ordenExamen as $indexExam => $valueExamen) {
                $ordenExamenData = [
                    "idexamen" => (isset($valueExamen->examen)) ? $valueExamen->examen->id : '',
                    "cantidad" => $valueExamen->cantidad,
                    "precio" => $valueExamen->precio,
                    "estado" => '1'
                ];

                if (!$ordenExamenModel->validate($ordenExamenData)) {
                    $errors = $ordenExamenModel->errors();
                    $errors['uniqueID'] = $valueExamen->uniqueID;
                    $validationErrorsCab[$indexCab]['ordenExamen'][] = ['ordenExamen' => $errors];
                }

                foreach ($valueExamen->ordenMuestra as $valueMuestra) {
                    $ordenMuestraData = [
                        "idmuestra" => (isset($valueMuestra->muestra)) ? $valueMuestra->muestra->id : '',
                        "idprocedenciamuestra" => (isset($valueMuestra->procedenciamuestra)) ? $valueMuestra->procedenciamuestra->id : '',
                        "estado" => '1'
                    ];
                    if (!$ordenMuestraModel->validate($ordenMuestraData)) {
                        $errors = $ordenMuestraModel->errors();
                        $errors['uniqueID'] = $valueMuestra->uniqueID;
                        $validationErrorsCab[$indexCab]['ordenExamen'][$indexExam]['ordenMuestra'][] = ['ordenMuestra' => $errors];
                    }
                }
            }
        }

        if (!empty($validationErrorsCab)) {
            return $this->failValidationErrors($validationErrorsCab);
        }




        foreach ($datacab as $valueCab) {
            $db->transBegin();
            $ordenCabData = [
                "anio" => '2024',
                "idestudio" => ($valueCab->estudio) ? $valueCab->estudio->id : '',
                "num_orden" => '',
                "idcompania" => (isset($valueCab->compania)) ? $valueCab->compania->id : '',
                "idmedico" => (isset($valueCab->medico)) ? $valueCab->medico->id : '',
                "idpaciente" => (isset($valueCab->paciente)) ? $valueCab->paciente->id : '',
                "estado" => '1'                
            ];
            $idOrdenCab = $ordenCabModel->insert($ordenCabData);

            foreach ($valueCab->ordenExamen as $valueExamen) {
                // datos del examen
                $ordenExamenData = [
                    "idordencab" => $idOrdenCab,
                    "idexamen" => (isset($valueExamen->examen)) ? $valueExamen->examen->id : '',
                    "cantidad" => $valueExamen->cantidad,
                    "precio" => $valueExamen->precio,
                    "estado" => '1'
                ];
                $idExamen = $ordenExamenModel->insert($ordenExamenData);
                // echo json_encode($data);                
                foreach ($valueExamen->ordenMuestra as $valueMuestra) {
                    // datos de la muestra
                    $ordenMuestraData = [
                        "idordenexamen" => $idExamen,
                        "idmuestra" => (isset($valueMuestra->muestra)) ? $valueMuestra->muestra->id : '',
                        "idprocedenciamuestra" => (isset($valueMuestra->procedenciamuestra)) ? $valueMuestra->procedenciamuestra->id : '',
                        "estado" => '1'
                    ];
                    $ordenMuestraModel->insert($ordenMuestraData);
                }
            }
            if ($db->transStatus()) {
                $db->transCommit();
                if ($idOrdenCab) {
                    $data = $ordenCabModel->find($idOrdenCab);
                    $resultado = [
                        "uniqueID" => $valueCab->uniqueID,
                        "num_orden" => $data->num_orden
                    ];
                    array_push($dataResultado, $resultado);
                }
            } else {
                $db->transRollback();
            }

        }

        if ($dataResultado) {
            $data = [
                "respuesta" => "Transacciones exitosas",
                "data" => $dataResultado
            ];
            return $this->respond($data, 200);
        } else {
            $data = [
                "respuesta" => "Transacciones incorrecta",
                "data" => $dataResultado
            ];
            return $this->respond($data, 404);
        }



        // try {
        //     $json = $this->request->getJSON();
        //     if (empty ($json)):
        //         return $this->failValidationError('No se proporcionaron datos');
        //     endif;
        //     if ($this->muestraModel->save($json)):
        //         $insertedID = $this->muestraModel->getInsertID();
        //         $savedRecord = $this->muestraModel->find($insertedID);
        //         return $this->respondCreated($savedRecord);
        //     else:
        //         return $this->failValidationErrors($this->muestraModel->errors(), 'Validación de formulario');
        //     endif;
        // } catch (\Exception $e) {
        //     return $this->failServerError('Ha ocurrido un error en el servidor');
        // }
    }


    public function update($id = null)
    {
        try {
            $data = $this->muestraModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $json = $this->request->getJSON();
            if (empty($json)):
                return $this->failResourceExists('No se proporcionaron datos');
            else:
                $data->fill([
                    'des_nombre' => $json->des_nombre ?? $data->des_nombre,
                    'estado' => $json->estado ?? $data->estado
                ]);
            endif;


            if (!$data->hasChanged()):
                return $this->failResourceExists('No se encontraron cambios');
            endif;

            if ($this->muestraModel->save($data)):
                $savedRecord = $this->muestraModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->muestraModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }


    }

    // delete product
    public function delete($id = null)
    {
        try {
            $data = $this->ordenCabModel->find($id);
            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            $data->estado = '0';

            if (!$data->hasChanged()):
                return $this->failValidationError('No se encontraron cambios');
            endif;

            $this->ordenCabModel->cleanValidationRules;


            if ($this->ordenCabModel->save($data)):
                $savedRecord = $this->ordenCabModel->find($id);
                return $this->respondUpdated($savedRecord);
            else:
                return $this->failValidationErrors($this->ordenCabModel->errors());
            endif;
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
    public function printOrden($id = null)
    {
        try {
            $dompdf = new Dompdf();
            $data = [
                'titulo' => 'P-2023-00001',
                'contenido' => '¡Hola, mundo!',
                'id' => $id
            ];
            $html = view('menbrete/index', $data);
            // Se establecen las opciones para Dompdf
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);

            $dompdf->setOptions($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait'); // 'portrait' para orientación vertical, 'landscape' para orientación horizontal
            $dompdf->render();
            $output = $dompdf->output();
            $pdfBase64 = base64_encode($output);

            return $this->respond(['des_informe' => $pdfBase64], 200);
        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
    public function getCelularEnvio($id, $envio)
    {

        try {
            $data = $this->ordenCabModel->getCelularEnvio($id, $envio);

            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            return $this->respond($data, 200);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
    public function getEmailEnvio($id, $envio)
    {

        try {
            $data = $this->ordenCabModel->getEmailEnvio($id, $envio);

            if (!$data):
                return $this->failNotFound('Registro no se encuentra en la base de datos');
            endif;

            return $this->respond($data, 200);

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
    public function enviarEmail()
    {
        try {
            $email = \Config\Services::email();

            $email->setTo('destinatario@email.com');
            $email->setFrom('pazmissael@gmail.com', 'missael');
            $email->setSubject('Correo de prueba');
            $email->setMessage('Este es un correo de prueba.');

            if ($email->send()) {
                return $this->respond(['response' => 'Correo enviado exitosamente'], 200);
            } else {
                // echo 'Error al enviar el correo: ' . $email->printDebugger(['headers']);
                return $this->failServerError('Error al enviar el correo');
            }

        } catch (\Exception $e) {
            return $this->failServerError('Ha ocurrido un error en el servidor');
        }
    }
}