<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Vacaciones del Personal';
require_once('includes/load.php');

$idP =  (int)$_GET['id'];
$e_detalle = find_by_id('detalles_usuario', $idP, 'id_det_usuario');
if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}

$user = current_user();
$nivel_user = $user['user_level'];
$periodos = find_all('cat_periodos_vac');
$vacaciones = find_all_vac($idP);

if ($nivel_user == 1) {
    page_require_level_exacto(1);
}
if ($nivel_user == 2) {
    page_require_level_exacto(2);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;
?>

<?php
if (isset($_POST['vacaciones'])) {

    $id_cat_periodo_vac = $_POST['id_cat_periodo_vac'];
    $semana1_1 = $_POST['semana1_1'];
    $semana1_2 = $_POST['semana1_2'];
    $semana2_1 = $_POST['semana2_1'];
    $semana2_2 = $_POST['semana2_2'];
    $observaciones = $_POST['observaciones'];
    date_default_timezone_set('America/Mexico_City');
    $fecha_creacion = date('Y-m-d');

    $query2 = "INSERT INTO rel_vacaciones (";
    $query2 .= "id_detalle_usuario, id_cat_periodo_vac, semana1_1, semana1_2, semana2_1, semana2_2, observaciones, fecha_creacion";
    $query2 .= ") VALUES (";
    $query2 .= " '{$idP}', '{$id_cat_periodo_vac}', '{$semana1_1}', '{$semana1_2}', '{$semana2_1}', '{$semana2_2}', '{$observaciones}', '{$fecha_creacion}'";
    $query2 .= ")";

    if ($db->query($query2)) {
        //sucess
        $session->msg('s', "Periodo vacacional agregado con éxito! ");
        insertAccion($user['id_user'], '"' . $user['username'] . '" agregó periodo vacacional al usuario de id:' . (int)$idP, 1);
        redirect('detalles_usuario.php', false);
    } else {
        //failed
        $session->msg('d', 'Desafortunadamente no se pudo crear el registro.');
        redirect('detalles_usuario.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-5">
        <div class="panel login-page4" style="margin-left: 0%;">
            <div class="panel-heading">
                <strong style="font-size: 16px; font-family: 'Montserrat', sans-serif">
                    <span class="glyphicon glyphicon-th"></span>
                    VACACIONES DE: <?php echo upper_case(ucwords($e_detalle['nombre'] . " " . $e_detalle['apellidos'])); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="vacaciones.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_cat_periodo_vac">Periodo Vacacional</label>
                                <select class="form-control" name="id_cat_periodo_vac" id="id_cat_periodo_vac">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($periodos as $periodo) : ?>
                                        <option value="<?php echo $periodo['id_cat_periodo_vac']; ?>">
                                            <?php echo ucwords($periodo['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span style="font-weight: bold; margin-left: 37%; margin-bottom: 1%;"> - Semana 1 -</span><br>
                        </div>
                        <div class="col-md-6">
                            <span style="font-weight: bold; margin-left: 37%; margin-bottom: 1%;"> - Semana 2 -</span><br>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <span style="font-weight: bold; margin-left: 18%; margin-bottom: 1%;"> - Semana 1 -</span><br> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semana1_1" style="margin-left: 35%;">Del día</label>
                                <input type="date" class="form-control" name="semana1_1" id="semana1_1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semana1_2" style="margin-left: 35%;">Al día</label>
                                <input type="date" class="form-control" name="semana1_2" id="semana1_2">
                            </div>
                        </div>
                        <!-- <span style="font-weight: bold; margin-left: 18%; margin-bottom: 1%;"> - Semana 2 -</span><br> -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semana2_1" style="margin-left: 35%;">Del día</label>
                                <input type="date" class="form-control" name="semana2_1" id="semana2_1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semana2_2" style="margin-left: 35%;">Al día</label>
                                <input type="date" class="form-control" name="semana2_2" id="semana2_2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="observaciones">Observaciones</label>
                                <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="vacaciones" class="btn btn-info">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7 panel-body" style="height: 100%; margin-top: -5px;">
        <table class="table table-bordered table-striped" style="width: 100%; float: left;" id="tblProductos">
            <thead class="thead-purple" style="margin-top: -50px;">
                <tr style="height: 10px;">
                    <th colspan="7" style="text-align:center; font-size: 14px;">Vacaciones <?php echo $diferencia ?></th>
                </tr>
                <tr style="height: 10px;">
                    <th class="text-center" style="width: 8%; font-size: 13px;">Periodo</th>
                    <th class="text-center" style="width: 6%; font-size: 13px;">Del (Semana 1)</th>
                    <th class="text-center" style="width: 6%; font-size: 13px;">Al (Semana 1)</th>
                    <th class="text-center" style="width: 6%; font-size: 13px;">Del (Semana 2)</th>
                    <th class="text-center" style="width: 6%; font-size: 13px;">Al (Semana 2)</th>
                    <th class="text-center" style="width: 14%; font-size: 13px;">Observaciones</th>
                    <th class="text-center" style="width: 1%; font-size: 13px;">Acciones</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($vacaciones as $vac) : ?>
                    <tr>
                        <td style="font-size: 15px;"><?php echo $vac['periodo']; ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $newDate = date("d-m-Y", strtotime($vac['semana1_1'])); ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $newDate = date("d-m-Y", strtotime($vac['semana1_2'])); ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $newDate = date("d-m-Y", strtotime($vac['semana2_1'])); ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $newDate = date("d-m-Y", strtotime($vac['semana2_2'])); ?></td>
                        <td class="text-center" style="font-size: 15px;"><?php echo $vac['observaciones']; ?></td>
                        <td style="font-size: 14px;" class="text-center">
                            <a href="edit_vacaciones.php?id=<?php echo (int)$vac['id_rel_vacaciones']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 30px; width: 30px;"><span class="material-symbols-rounded" style="font-size: 22px; color: black; margin-top: -1.5px; margin-left: -5px;">edit</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    num.oninput = function() {
        if (this.value.length > 4) {
            this.value = this.value.svace(0, 4);
        }
    }
</script>
<?php include_once('layouts/footer.php'); ?>