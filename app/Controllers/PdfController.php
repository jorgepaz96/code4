<?php

namespace App\Controllers;

use App\Models\UserModel;
use Dompdf\Dompdf;

class PdfController extends BaseController
{
    public function index()
    {
        $dompdf = new Dompdf();
        $dompdf->loadHTML('
        <table style="width: 100%;">
	<tbody>
		<tr>
			<td style="width: 100%; background-color: rgb(239, 239, 239);" class="fr-highlighted fr-thick">
				<div data-empty="true" style="text-align: center;"><strong><u>DIAGNOSTICO CITOLOGICO</u></strong></div>
			</td>
		</tr>
	</tbody>
</table>
<hr>

<table style="width: 100%;">
	<tbody>
		<tr>
			<td style="width: 99.9424%; background-color: rgb(239, 239, 239);" colspan="2" class="fr-highlighted fr-thick">

				<p>
					<br>
				</p>
				<div style="text-align: center;"><strong><span style="font-size: 14px;">PRUEBA</span></strong></div>

				<p>
					<br>
				</p>
			</td>
		</tr>
		<tr>
			<td style="width: 50.0000%;">
				<br>
			</td>
			<td style="width: 50.0000%;">
				<br>
			</td>
		</tr>
		<tr>
			<td style="width: 50.0000%;">
				<br>
			</td>
			<td style="width: 50.0000%;">
				<br>
			</td>
		</tr>
		<tr>
			<td style="width: 50.0000%;">
				<br>
			</td>
			<td style="width: 50.0000%;">
				<br>
			</td>
		</tr>
		<tr>
			<td style="width: 50.0000%;">
				<br>
			</td>
			<td style="width: 50.0000%;">
				<br>
			</td>
		</tr>
		<tr>
			<td style="width: 50.0000%;">
				<br>
			</td>
			<td style="width: 50.0000%;">
				<br>
			</td>
		</tr>
		<tr>
			<td style="width: 50.0000%;">
				<br>
			</td>
			<td style="width: 50.0000%;">
				<br>
			</td>
		</tr>
	</tbody>
</table>

    
    
');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('defaultMediaType', 'all');
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->render();
        $dompdf->stream('documentos.pdf', ['Attachment' => false]);

    }

}
