<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar Reunión de Vinculación';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
$id_user = $user['id_user'];


if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 6) {
    page_require_level_exacto(6);
}

if ($nivel_user == 24) {
    page_require_level_exacto(24);
}

if ($nivel_user > 2 && $nivel_user < 6) :
    redirect('home.php');
endif;
if ($nivel_user > 6 && $nivel_user < 24) :
    redirect('home.php');
endif;
if ($nivel_user > 24 && $nivel_user < 53) :
    redirect('home.php');
endif;

$id_folio = last_id_folios_general();
$inticadores_pat = find_all_pat_area(39,'reuniones_vinculacion');
?>
<?php header('Content-type: text/html; charset=utf-8');

if (isset($_POST['add_reuniones_vinculacion'])) {

    if (empty($errors)) {
        $nombre_reunion   = remove_junk($db->escape($_POST['nombre_reunion']));
        $fecha_reunion   = remove_junk($db->escape($_POST['fecha_reunion']));
        $lugar_reunion   = remove_junk(($db->escape($_POST['lugar_reunion'])));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $numero_asistentes   = remove_junk($db->escape($_POST['numero_asistentes']));
        $alcances_acuerdos   = remove_junk(($db->escape($_POST['alcances_acuerdos']))); 
        $observaciones   = remove_junk($db->escape($_POST['observaciones']));
        $id_indicadores_pat   = remove_junk($db->escape($_POST['id_indicadores_pat']));

		$nombre_participante = $_POST['nombre_participante'];	
		$procedencia_participante = $_POST['procedencia_participante'];	
       

        if (count($id_folio) == 0) {
            $nuevo_id_folio = 1;
            $no_folio1 = sprintf('%04d', 1);
        } else {
            foreach ($id_folio as $nuevo) {
                $nuevo_id_folio = (int) $nuevo['contador'] + 1;
                $no_folio1 = sprintf('%04d', (int) $nuevo['contador'] + 1);
            }
        }
        //Se crea el número de folio
        $year = date("Y");
        // Se crea el folio de capacitacion
        $folio = 'CEDH/' . $no_folio1 . '/' . $year . '-RVIN';

        $folio_carpeta = 'CEDH-' . $no_folio1 . '-' . $year . '-RVIN';
        $carpeta = 'uploads/reuniones_vinculacion/' . $folio_carpeta;

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $lista_asistencia = $_FILES['lista_asistencia']['name'];
        $size = $_FILES['lista_asistencia']['size'];
        $type = $_FILES['lista_asistencia']['type'];
        $temp = $_FILES['lista_asistencia']['tmp_name'];

        $move =  move_uploaded_file($temp, $carpeta . "/" . $lista_asistencia);
		
		/*creo archivo index para que no se muestre el Index Of*/
		$source = 'uploads/index.php';
		if (copy($source, $carpeta.'/index.php')) {
			echo "El archivo ha sido copiado exitosamente.";
		} else {
			echo "Ha ocurrido un error al copiar el archivo.";
		}


        $dbh1 = new PDO('mysql:host=localhost;dbname=suigcedh', 'suigcedh', '9DvkVuZ915H!');	
		
            $query = "INSERT INTO reuniones_vinculacion (";
            $query .= "folio,nombre_reunion,fecha_reunion,lugar_reunion,modalidad,numero_asistentes,alcances_acuerdos,observaciones,lista_asistencia,id_indicadores_pat,id_user_creador,fecha_creacion";
            $query .= ") VALUES (";
            $query .= " '{$folio}','{$nombre_reunion}','{$fecha_reunion}','{$lugar_reunion}','{$modalidad}','{$numero_asistentes}','{$alcances_acuerdos}','{$observaciones}','{$lista_asistencia}','{$id_indicadores_pat}','{$id_user}',NOW()";
            $query .= ")";
		if ($dbh1->query($query)){
			$id_reuniones_vinculacion = $dbh1->lastInsertId();
			
		}		
		
            $query2 = "INSERT INTO folios (";
            $query2 .= "folio, contador";
            $query2 .= ") VALUES (";
            $query2 .= " '{$folio}','{$no_folio1}'";
            $query2 .= ")";
			
		
        for ($i = 0; $i < sizeof($nombre_participante); $i = $i + 1) {
			if($nombre_participante[$i] !== ''){
				$query = "INSERT INTO rel_reuniones_vinculacion_asistentes (id_reuniones_vinculacion, nombre_participante,procedencia_participante) VALUES($id_reuniones_vinculacion, '$nombre_participante[$i]','$procedencia_participante[$i]');";       
				if ($db->query($query)) {
					//echo "<p>Denuncia-denunciado insertada con éxito</p>";		
				 }
			}
		}
	
		
            //success
            $session->msg('s', " La Reunión de Vinculación ha sido agregada con éxito. Folio ".$folio);
            insertAccion($user['id_user'], '"'.$user['username'].'" agregó una Reunión de Vinculación. Folio '.$folio.' .', 1);
            redirect('reuniones_vinculacion.php', false);        
    } else {
        $session->msg("d", $errors);
        redirect('add_reuniones_vinculacion.php', false);
    }
}
?>

<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';				
				html += '	<div class="col-md-4">';
				html += '					<div class="form-group">';
				html += '						<input type="text" class="form-control" name="nombre_participante[]" >';
				html += '					</div>';
				html += '				</div>';
				html += '	<div class="col-md-4">';
				html += '					<div class="form-group">';
				html += '						<input type="text" class="form-control" name="procedencia_participante[]" >';
				html += '					</div>';
				html += '				</div>';
				html += '	<div class="col-md-4">';
				html += '	<button type="button" class="btn btn-outline-danger" id="removeRow" > ';
				html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
				html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
				html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
				html += '		</svg>';
				html += '  	</button>';			
				html += '	</div> <br><br>';
				html += '</div> ';

				$('#newRow').append(html);
		});
		
		
		
		$(document).on('click', '#removeRow', function() {
				$(this).closest('#inputFormRow').remove();
			});
			
	
	
	});
	
	
</script>
<?php 
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar Reunión de Vinculación</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_reuniones_vinculacion.php" enctype="multipart/form-data">
                <div class="row">
                   
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_reunion">Fecha de la Reunión</label><br>
                            <input type="date" class="form-control" name="fecha_reunion" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombre_reunion">Nombre de la Reunión</label>
                            <input type="text" class="form-control" name="nombre_reunion" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar_reunion">Lugar  de la Reunión</label>
                            <input type="text" class="form-control" name="lugar_reunion" required>
                        </div>
                    </div>					
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad" required>
                                <option value="">Escoge una opción</option>
                                <option value="Presencial">Presencial</option>
                                <option value="En línea">En línea</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="numero_asistentes">No. de asistentes</label>
                            <input type="number" min="1" class="form-control" max="1000" name="numero_asistentes" required>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="id_indicadores_pat">Definición del Indicador</label>
                            <select class="form-control form-select" name="id_indicadores_pat" required>
                                <option value="">Selecciona Indicador</option>
                                <?php foreach ($inticadores_pat as $datos) : ?>
                                    <option  value="<?php echo $datos['id_indicadores_pat']; ?>"><?php echo ucwords($datos['definicion_indicador']); ?></option>									
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <span>
                                <label for="lista_asistencia">Lista de Asistencia</label>
                                <input id="lista_asistencia" type="file" accept="application/pdf" class="form-control" name="lista_asistencia" required>
                            </span>
                        </div>
                    </div>					
                </div>

      

                <div class="row">              
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="alcances_acuerdos">Avances y Acuerdos</label>
                            <textarea class="form-control" name="alcances_acuerdos" id="alcances_acuerdos" cols="10" rows="5"></textarea>
                        </div>
                    </div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones" cols="10" rows="5"></textarea>
                        </div>
                    </div>
				</div>

      

                <div class="row">
					 
					<table style="color:#3a3d44; margin-top: -10px; page-break-after:always;" >
						<tr>
							<td style="width: 50%;">
								<div class="panel-heading">
								<strong>
									<span class="glyphicon glyphicon-option-vertical"></span>
									<span> Miembros/ Participantes</span>
								</strong>
							</div>
						</td>
						<td style="width: 50%;">
								
						</td>
							
						</tr>						
						<tr>
							<td style="width: 50%;">
							<div id="inputFormRow" style="width: 100%;">						
								<div class="col-md-4">
									<div class="form-group">
									<label for="nombre_participante">Nombre Asistente</label>
									<input type="text" class="form-control" name="nombre_participante[]" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
									<label for="procedencia_participante">Institución de Procedencia</label>
									<input type="text" class="form-control" name="procedencia_participante[]" required>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
									<button type="button" class="btn btn-success" id="addRow" name="addRow" >
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
										  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
										  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
										</svg>
									</button>										
									</div>
								</div>	
							</div>
							<br>
							<div class="row" id="newRow" style="width: 100%;">
							</div>							
							
						</td>
						<td style="width: 50%;">
						
												
						</td>
						</tr>
					</table>
				</div> 	

                <div class="form-group clearfix">
                    <a href="reuniones_vinculacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_reuniones_vinculacion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>