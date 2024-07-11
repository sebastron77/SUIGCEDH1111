<?php header('Content-type: text/html; charset=utf-8');
$page_title = 'Agregar trabajador';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];
if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 14) {
    page_require_level_exacto(14);
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 14) :
    redirect('home.php');
endif;
if ($nivel_user > 14) :
    redirect('home.php');
endif;

$cargos = find_all_cargos2();
$generos = find_all('cat_genero');
?>
<?php header('Content-type: text/html; charset=utf-8');
if (isset($_POST['add_detalle_usuario'])) {

    $req_fields = array('nombre', 'apellidos', 'id_cat_gen', 'correo');
    validate_fields($req_fields);

    if (empty($errors)) {
        $nombre   = remove_junk($db->escape($_POST['nombre']));
        $apellidos   = remove_junk($db->escape($_POST['apellidos']));
        $id_cat_gen   = remove_junk($db->escape($_POST['id_cat_gen']));
        $correo   = remove_junk($db->escape($_POST['correo']));
        $cargo   = (int)$db->escape($_POST['cargo']);

        if ($nivel_user == 1) {
            $query = "INSERT INTO detalles_usuario (";
            $query .= "nombre, apellidos, id_cat_gen, correo, id_cargo, estatus_detalle";
            $query .= ") VALUES (";
            $query .= " '{$nombre}', '{$apellidos}', '{$id_cat_gen}', '{$correo}', {$cargo}, '1'";
            $query .= ")";
        }

        if ($nivel_user != 1) {
            $query = "INSERT INTO detalles_usuario (";
            $query .= "nombre,apellidos, id_cat_gen, correo, estatus_detalle";
            $query .= ") VALUES (";
            $query .= " '{$nombre}', '{$apellidos}', '{$id_cat_gen}', '{$correo}', '1'";
            $query .= ")";
        }

        if ($db->query($query)) {
            //sucess
            $session->msg('s', " El trabajador ha sido agregado con éxito.");
            insertAccion($user['id_user'], '"' . $user['username'] . '" agregó al trabajador(a): ' . $nombre . ' ' . $apellidos . '.', 1);
            redirect('detalles_usuario.php', false);
        } else {
            //failed
            $session->msg('d', ' No se pudo agregar el trabajador.');
            redirect('add_detalles_usuario.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_detalle_usuario.php', false);
    }
}
?>
<?php header('Content-type: text/html; charset=utf-8');
include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Agregar trabajador</span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="add_detalle_usuario.php">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre(s)" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" placeholder="Apellidos" required>
                        </div>
                    </div>
                    <?php if ($nivel_user == 1) : ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="level">Cargo</label>
                                <select class="form-control" name="cargo">
                                    <option value="">Escoge una opción</option>
                                    <?php foreach ($cargos as $cargo) : ?>
                                        <option value="<?php echo $cargo['id_cargos']; ?>"><?php echo ucwords($cargo['nombre_cargo'] . " | " . $cargo['nombre_area']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_gen">Género</label>
                            <select class="form-control" name="id_cat_gen">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($generos as $genero) : ?>
                                    <option value="<?php echo $genero['id_cat_gen']; ?>"><?php echo ucwords($genero['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control" name="curp" placeholder="CURP" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="text" class="form-control" name="correo" placeholder="ejemplo@correo.com" required>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <a href="detalles_usuario.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="add_detalle_usuario" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>