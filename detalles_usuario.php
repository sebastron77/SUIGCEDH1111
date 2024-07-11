<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Datos trabajadores';
require_once('includes/load.php');
?>
<?php
$all_detalles = find_all_trabajadores();
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id_user'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];

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
          <span>Lista de Trabajadores de la CEDH</span>
        </strong>
        <?php if ($otro == 1 || $nivel_user == 1 || $nivel_user == 14) : ?>
          <a href="add_detalle_usuario.php" class="btn btn-info pull-right" style="margin-left:10px">Agregar trabajador</a>
          <a href="ver_licencias_vigentes.php" class="btn btn-info pull-right" style="background: #5f03df; border-color: #5f03df;">Ver Licencias Vigentes</a>
        <?php endif ?>
        <!-- <a href="ver_licencias" style="margin-left: 15%; margin-top: 10%">Ver información de licencias vigentes</a> -->
      </div>

      <div class="panel-body">
        <table class="datatable table table-bordered table-striped">
          <thead class="thead-purple">
            <tr style="height: 10px;"">
              <th class="text-center" style=" width: 1%;">#</th>
              <th class="text-center" style="width: 5%;">Nombre(s)</th>
              <th class="text-center" style="width: 5%;">Apellidos</th>
              <th class="text-center" style="width: 10%;">Puesto</th>
              <th class="text-center" style="width: 15%;">Área</th>
              <th class="text-center" style="width: 1%;">Estatus</th>
              <?php if ($otro == 1 || $nivel_user == 1 || ($nivel_user == 14)) : ?>
                <th style="width: 1%;" class="text-center">Acciones</th>
              <?php endif ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_detalles as $a_detalle) : ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['nombre'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['apellidos'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['puesto'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['nombre_area'])) ?></td>
                <td class="text-center">
                  <?php if ($a_detalle['estatus_detalle'] === '1') : ?>
                    <span class="label label-success"><?php echo "Activo"; ?></span>
                  <?php else : ?>
                    <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                  <?php endif; ?>
                </td>
                <?php if ($otro == 1 || $nivel_user == 1 || ($nivel_user == 14)) : ?>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="ver_info_detalle.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información" style="height: 40px">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">visibility</span>
                      </a>&nbsp;
                      <a href="edit_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-warning btn-md" title="Editar" data-toggle="tooltip" style="height: 40px">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: black; margin-top: 5px;">edit</span>
                      </a>&nbsp;
                      <a href="exp_general.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #D94F21; border-color:#D94F21; height: 40px" title="Expediente General" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 22px; color: white; margin-top: 5px;">folder_shared</span>
                      </a>&nbsp;
                      <a href="exp_ac_lab.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #0F6466; border-color:#0F6466; height: 40px" title="Expediente Académico" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 23px; color: white; margin-top: 5px;">school</span>
                      </a>&nbsp;
                      <a href="exp_laboral.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #5347da; border-color:#5347da; height: 40px" title="Expediente Laboral" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">work</span>
                      </a>&nbsp;
                      <a href="licencias.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #e44cd5; border-color:#e44cd5; height: 40px" title="Licencias" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">calendar_clock</span>
                      </a>&nbsp;
                      <a href="vacaciones.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" style=" background: #229df0; border-color:#229df0; height: 40px" title="Vacaciones" data-toggle="tooltip">
                        <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">beach_access</span>
                      </a>&nbsp;
                      <?php if (($nivel == 1) || ($nivel_user == 14)) : ?>
                        <?php if ($a_detalle['estatus_detalle'] == 0) : ?>
                          <a href="activate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip" style="height: 40px">
                            <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">check</span>
                          </a>&nbsp;
                        <?php else : ?>
                          <a href="inactivate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" title="Inactivar" data-toggle="tooltip" style="height: 40px">
                            <span class="material-symbols-rounded" style="font-size: 20px; color: white; margin-top: 5px;">block</span>
                          </a>&nbsp;
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