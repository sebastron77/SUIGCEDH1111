<?php
$page_title = 'Editar Datos de Trabajador';
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
?>
<?php
$e_detalle = find_by_id('detalles_usuario', (int)$_GET['id'], 'id_det_usuario');
$cargos = find_all_order('cargos','nombre_cargo');
$areas = find_all_order('area','jerarquia');
$generos = find_all('cat_genero');
if (!$e_detalle) {
    $session->msg("d", "id de usuario no encontrado.");
    redirect('detalles_usuario.php');
}
$user = current_user();
$nivel = $user['user_level'];
$cat_municipios = find_all_cat_municipios();
?>

<?php
if (isset($_POST['update'])) {
    $req_fields = array('nombre', 'apellidos', 'id_cat_gen', 'correo', 'cargo');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_detalle['id_det_usuario'];
        $nombre   = $_POST['nombre'];
        $apellidos   = $_POST['apellidos'];
        $sexo   = remove_junk($db->escape($_POST['id_cat_gen']));
        $correo   = remove_junk($db->escape($_POST['correo']));
        $cargo = remove_junk((int)$db->escape($_POST['cargo']));
        $telefono   = $db->escape($_POST['telefono']);
        $curp   = $db->escape($_POST['curp']);
        $rfc   = $db->escape($_POST['rfc']);
        $calle_num   = $db->escape($_POST['calle_num']);
        $colonia   = $db->escape($_POST['colonia']);
        $municipio   = $db->escape($_POST['municipio']);
        $estado   = $db->escape($_POST['estado']);

        $sql = "UPDATE detalles_usuario SET nombre='{$nombre}', apellidos='{$apellidos}', id_cat_gen='{$sexo}', correo='{$correo}', 
		id_cargo={$cargo}, curp='$curp', rfc='{$rfc}', 
		calle_num='{$calle_num}', colonia='{$colonia}', municipio='{$municipio}', estado='{$estado}', telefono='{$telefono}' WHERE id_det_usuario='{$db->escape($id)}'";
        $result = $db->query($sql);
        if ($result && $db->affected_rows() === 1) {
            $session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó al trabajador(a): ' . $nombre . ' ' . $apellidos . '.', 2);
            redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_detalle_usuario.php?id=' . (int)$e_detalle['id_det_usuario'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12"> <?php echo display_msg($msg); ?> </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    Actualizar expediente general del trabajador: <?php echo (ucwords($e_detalle['nombre'])); ?> <?php echo ($e_detalle['apellidos']); ?>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_detalle_usuario.php?id=<?php echo (int)$e_detalle['id_det_usuario']; ?>" class="clearfix">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nombre" class="control-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo ($e_detalle['nombre']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="apellidos" class="control-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" value="<?php echo ($e_detalle['apellidos']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cargo">Cargo</label>
                                <select class="form-control" name="cargo" required>
                                    <?php foreach ($cargos as $cargo) : ?>
                                        <option <?php if ($cargo['id_cargos'] === $e_detalle['id_cargo']) echo 'selected="selected"'; ?> value="<?php echo $cargo['id_cargos']; ?>">
                                            <?php foreach ($areas as $area) : ?>
                                                <?php if ($area['id_area'] === $cargo['id_area'])
                                                    echo ucwords($cargo['nombre_cargo']) . " - " . ucwords($area['nombre_area']);
                                                ?>
                                            <?php endforeach; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
						
						<label for="id_cat_gen">Género</label>
                                <select class="form-control" name="id_cat_gen">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($generos as $genero): ?>
                                        <option <?php if ($e_detalle['id_cat_gen'] === $genero['id_cat_gen']) echo 'selected="selected"'; ?> value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                    <?php endforeach; ?>
                                </select>                              
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['telefono']); ?>" name="telefono">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="correo" class="control-label">Correo</label>
                            <input type="text" class="form-control" name="correo" value="<?php echo remove_junk($e_detalle['correo']); ?>">
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="curp">CURP</label>
                                <input type="text" class="form-control" name="curp" value="<?php echo ($e_detalle['curp']); ?>" placeholder="CURP">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rfc">RFC</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['rfc']); ?>" name="rfc">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Calle y Núm.</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['calle_num']); ?>" name="calle_num">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="colonia">Colonia</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['colonia']); ?>" name="colonia">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <select class="form-control" name="municipio" required>
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cat_municipios as $municipio) : ?>
                                        <option <?php if ($municipio['id_cat_mun'] === $e_detalle['municipio'])
                                                    echo 'selected="selected"'; ?> value="<?php echo $municipio['id_cat_mun']; ?>">
                                                    <?php echo ucwords($municipio['descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <input type="text" class="form-control" value="<?php echo ($e_detalle['estado']); ?>" name="estado">
                            </div>
                        </div>
                       
                    </div>
                    <div class="form-group clearfix">
                        <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                            Regresar
                        </a>
                        <button type="submit" name="update" class="btn btn-info">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>