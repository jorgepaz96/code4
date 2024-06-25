<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<title><?= $titulo ?></title>
	<style>
		* {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 11px;
		}

		html {
			margin: 0px;
		}

		.tipo_sobre_1 {
			/*sobre chico*/

			padding: 195px 60px 100px 120px;
			/*background-image: url("./assets/dist/img/photo3.jpg");*/
		}


		.tipo_sobre_2 {
			/*sobre grande*/

			padding: 260px 60px 100px 120px;
			/*background-image: url("./assets/dist/img/photo3.jpg");*/
		}

		#text-det table ul,
		#text-det table p {
			/*background: #234;	*/
			margin-bottom: 5px;
			margin-top: 0px;
		}

		#text-det table td {
			padding: 3px 0px 3px 0px;
		}

		table {
			border-collapse: collapse;
			/*border: 1px solid black;*/
		}

		#text-det table ul {
			padding-left: 12px;
		}

		/*.table_con_borde ,.table_con_borde th,.table_con_borde td {
			border: 1px solid black;
		}*/
		.fr-highlighted {
			border: 1px solid black;
		}

		body {
			
			background-image: url('<?= 'http://desarrollo.test/sistema_lab/backend/public/uploads/menbrete/1710192891_07efaefa3f8a61e3d5b7.jpg' ?>');
			background-size: cover;
			/* Ajustar la imagen al tamaño del cuerpo */
			background-repeat: no-repeat;
			
			/* Evitar la repetición de la imagen */
		}
	</style>

</head>

<body class="tipo_sobre_1 tipo_formato_1">
	<div id="container">
		<div id="text-cab">


			<div style="text-align: right"><strong>

				</strong></div>
			<table class="" style="width: 100%;">

				<tr>
					<td style="width: 28.3208%;">
						<strong>PACIENTE</strong>
					</td>
					<td style="width: 71.5957%;">


					</td>
				</tr>
				<tr>
					<td style="width: 28.3208%;">
						<strong>MUESTRA</strong>
					</td>
					<td style="width: 71.5957%;">



					</td>
				</tr>

				<tr>
					<td style="width: 28.3208%;">
						<strong>EXAMEN SOLICITADO</strong>
					</td>
					<td style="width: 71.5957%;">

					</td>
				</tr>
				<tr>
					<td style="width: 28.3208%;">
						<strong>INDICADO POR</strong>
					</td>
					<td style="width: 71.5957%;">

					</td>
				</tr>

				<tr>
					<td style="width: 28.3208%;">
						<strong>FECHA DE ENTREGA</strong>
					</td>
					<td style="width: 71.5957%;">

					</td>
				</tr>

			</table>
		</div>
		<hr>
		<div id="text-det">
			<?= $contenido.' '.$id ?>
		</div>


	</div>
</body>

</html>