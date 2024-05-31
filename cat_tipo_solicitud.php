<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de Tipo de Solicitudes';
require_once('includes/load.php');

// page_require_level(2);
$all_solicitudes = find_all_order('cat_tipo_solicitud', 'descripcion');
$user = current_user();
$nivel = $user['user_level'];

$id_user = $user['id_user'];
$busca_area = area_usuario($id_user);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 10) {
    page_require_level_exacto(10);
}
if ($nivel_user > 2 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 10 ) :
    redirect('home.php');
endif;
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
                    <span>Catálogo de Tipos de Solicitudes<span>
                </strong>
                <?php if ($nivel_user <= 2 || $nivel == 10 ) : ?>
                    <a href="add_tipo_solicitud.php" class="btn btn-info pull-right btn-md"> Agregar Tipo Solicitud</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 40%;">Nombre de Tipo Solicitud</th>
                            <th class="text-center" style="width: 20%;">Estatus</th>
                            <?php if ($otro == 1 || $nivel == 10 ) : ?>
                                <th class="text-center" style="width: 15%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_solicitudes as $a_disenios) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_disenios['descripcion'])) ?></td>
                                <td class="text-center">
                                    <?php if ($a_disenios['estatus'] === '1') : ?>
                                        <span class="label label-success"><?php echo "Activa"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactiva"; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($nivel_user <= 2 || $nivel == 10 ) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($nivel_user <= 2 || $nivel == 7 || $nivel == 10 ) : ?>
                                                <a href="edit_tipo_solicitud.php?id=<?php echo (int)$a_disenios['id_cat_tipo_solicitud']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            <?php endif ?>
                                            <?php if (($nivel_user <= 2 || $nivel == 10 ) && ($a_disenios['id_cat_tipo_solicitud'] != 1)) : ?>

                                                <?php if ($a_disenios['estatus'] == 0) : ?>
                                                    <a href="activate_tipo_solicitud.php?id=<?php echo (int)$a_disenios['id_cat_tipo_solicitud']; ?>&a=0" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="activate_tipo_solicitud.php?id=<?php echo (int)$a_disenios['id_cat_tipo_solicitud']; ?>&a=1" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
                                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                                    </a>
                                                <?php endif; ?>                                               
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>