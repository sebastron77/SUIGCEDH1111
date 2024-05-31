<?php
$page_title = 'Datos trabajadores';
require_once('includes/load.php');
?>
<?php
header('Content-Type: text/html; charset=UTF-8');

$e_detalle = find_by_id('detalles_usuario', (int) $_GET['id'], 'id_det_usuario');
$e_detalle_cargo = find_detalle_cargo((int) $_GET['id']);
$cargos = find_all('cargos');
$areas = find_all('area');
$user = current_user();
$nivel_user = $user['user_level'];

page_require_level(53);
if ($nivel_user == 7 || $nivel_user == 53) {
	insertAccion($user['id_user'], '"' . $user['username'] . '" Visualizo la Información de '.$page_title.' . Folio:'.$e_detalle['nombre'].' '.$e_detalle['apellidos'], 5);   
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Información completa de
                        <?php echo remove_junk(ucwords($e_detalle['nombre'])) ?>
                        <?php echo remove_junk(ucwords($e_detalle['apellidos'])) ?>
                    </span>
                </strong>
            </div>

            <div class="panel-body">

                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 10%;">ID Trabajador</th>
                            <th style="width: 12%;">Nombre</th>
                            <th style="width: 12%;">Apellidos</th>
                            <th style="width: 1%;">Sexo</th>
                            <th style="width: 20%;">Correo</th>
                            <th style="width: 20%;">Cargo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo remove_junk($e_detalle['id_det_usuario']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['nombre']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['apellidos']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['sexo']) ?>
                            </td>
                            <td>
                                <?php echo remove_junk($e_detalle['correo']) ?>
                            </td>
                            <td>
                                <?php
                                foreach ($cargos as $cargo): foreach ($areas as $area):
                                        if ($cargo['id_cargos'] === $e_detalle['id_cargo'])
                                            if ($area['id_area'] === $cargo['id_area'])
                                                echo $cargo['nombre_cargo'] . " - " . $area['nombre_area'];
                                    endforeach;
                                endforeach;
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-9">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip"
                            title="Regresar">
                            Regresar
                        </a>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>