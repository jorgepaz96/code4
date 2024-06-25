<?php

namespace App\Controllers;

use App\Models\UserModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends BaseController
{
    public function index()
    {
        // Se crea una instancia de Dompdf
        $dompdf = new Dompdf();

        // Se carga el contenido HTML
        $html = view('menbrete/index');

        // Se establecen las opciones para Dompdf
        $options = new Options();
		$options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        // Se aplica las opciones a Dompdf
        $dompdf->setOptions($options);

        // Se carga el contenido HTML en Dompdf
        $dompdf->loadHtml($html);
		
		// Se establece el tamaño del papel como A4
		$dompdf->setPaper('A4', 'portrait'); // 'portrait' para orientación vertical, 'landscape' para orientación horizontal

		// Se renderiza el PDF
        $dompdf->render();

        // Se muestra el PDF en el navegador
        // Obtener el contenido del PDF como cadena
        $output = $dompdf->output();

        // Convertir el contenido del PDF a base64
        $pdfBase64 = base64_encode($output);

        // Retornar el PDF en base64
        return $pdfBase64;

    }

}
