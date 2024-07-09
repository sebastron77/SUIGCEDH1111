<?php
$page_title = 'Editar Vacaciones';
require_once('includes/load.php');
$user = current_user();
$nivel_user = $user['user_level'];

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

$idP =  (int)$_GET['id'];
$e_vacaciones = find_by_id('rel_vacaciones', (int)$_GET['id'], 'id_rel_vacaciones');
$detalle = find_by_id('detalles_usuario', $e_vacaciones['id_detalle_usuario'], 'id_det_usuario');
if (!$e_vacaciones) {
    $session->msg("d", "La información no existe, verifique el ID.");
    redirect('edit_vacaciones.php?id=' . (int)$e_vacaciones['id_rel_vacaciones']);
}
$tipo_vacaciones = find_all('cat_periodos_vac');
$consec = find_by_id_consec($idP);
$licencias = find_all_lic($idP);
$periodos = find_all('cat_periodos_vac');
?>
<?php
if (isset($_POST['edit_vacaciones'])) {

    $id_cat_periodo_vac = $_POST['id_cat_periodo_vac'];
    $semana1_1 = $_POST['semana1_1'];
    $semana1_2 = $_POST['semana1_2'];
    $semana2_1 = $_POST['semana2_1'];
    $semana2_2 = $_POST['semana2_2'];
    $observaciones = $_POST['observaciones'];
    
    $query  = "UPDATE rel_vacaciones SET id_cat_periodo_vac='{$id_cat_periodo_vac}', semana1_1='{$semana1_1}', semana1_2='{$semana1_2}', 
                semana2_1='{$semana2_1}', semana2_2='{$semana2_2}', observaciones='{$observaciones}'";
    $query .= "WHERE id_rel_vacaciones='{$db->escape($e_vacaciones['id_rel_vacaciones'])}'";
    $result = $db->query($query);

    if ($result && $db->affected_rows() === 1) {
        //sucess
        $session->msg('s', "La información del periodo vacacional de permiso ha sido actualizada.");
        insertAccion($user['id_user'], '"' . $user['username'] . '" editó permiso de licencia al usuario de id:' . (int)$idP, 2);
        redirect('edit_vacaciones.php?id=' . (int)$e_vacaciones['id_rel_vacaciones'], false);
    } else {
        //failed
        $session->msg('d', 'Lamentablemente no se ha actualizado la licencia de permiso, debido a que no hay cambios registrados en la descripción.');
        redirect('edit_vacaciones.php?id=' . (int)$e_vacaciones['id_rel_vacaciones'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
<div class="row login-page6" style="width: 40%; height: 450px; margin-left: 25%; margin-top: 5%;">
    <div class="panel-heading" style="height: 11%">
        <strong>
            <span style="font-size: 16px;">EDITAR VACACIONES DE: <?php echo upper_case($detalle['nombre'] . " " . $detalle['apellidos']); ?></span>
        </strong>
    </div>
    <div class="panel-body" style=" margin-top: -1%;">
        <form method="post" action="edit_vacaciones.php?id=<?php echo (int)$e_vacaciones['id_rel_vacaciones']; ?>" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="id_cat_periodo_vac">Periodo Vacacional</label>
                        <select class="form-control" name="id_cat_periodo_vac" id="id_cat_periodo_vac">
                            <option value="">Escoge una opción</option>
                            <?php foreach ($periodos as $periodo) : ?>
                                <option <?php if ($periodo['id_cat_periodo_vac'] === $e_vacaciones['id_cat_periodo_vac']) echo 'selected="selected"'; ?> value="<?php echo $periodo['id_cat_periodo_vac']; ?>"><?php echo ucwords($periodo['descripcion']); ?>
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
                        <input type="date" class="form-control" name="semana1_1" id="semana1_1" value="<?php echo $e_vacaciones['semana1_1'] ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="semana1_2" style="margin-left: 35%;">Al día</label>
                        <input type="date" class="form-control" name="semana1_2" id="semana1_2" value="<?php echo $e_vacaciones['semana1_2'] ?>">
                    </div>
                </div>
                <!-- <span style="font-weight: bold; margin-left: 18%; margin-bottom: 1%;"> - Semana 2 -</span><br> -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="semana2_1" style="margin-left: 35%;">Del día</label>
                        <input type="date" class="form-control" name="semana2_1" id="semana2_1" value="<?php echo $e_vacaciones['semana2_1'] ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="semana2_2" style="margin-left: 35%;">Al día</label>
                        <input type="date" class="form-control" name="semana2_2" id="semana2_2" value="<?php echo $e_vacaciones['semana2_2'] ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="observaciones">Observaciones</label>
                        <textarea type="text" class="form-control" name="observaciones" id="observaciones" cols="30" rows="4"><?php echo $e_vacaciones['observaciones'] ?></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group clearfix">
                <a href="vacaciones.php?id=<?php echo $e_vacaciones['id_detalle_usuario'] ?>" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
                <button type="submit" name="edit_vacaciones" class="btn btn-info">Agregar</button>
            </div>
        </form>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>