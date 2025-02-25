<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Estadísticas Municipio';
require_once('includes/load.php');
$municipios = municipio((int)$_GET['id']);

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 3) {
    page_require_level(3);
}
if ($nivel_user == 5) {
    page_require_level_exacto(5);
}
if ($nivel_user == 7) {
    page_require_level_exacto(7);
}
if ($nivel_user == 50) {
    page_require_level_exacto(50);
}

if ($nivel_user > 7 && $nivel_user < 50) :
  redirect('home.php');
endif;
?>

<?php include_once('layouts/header.php'); ?>

<?php if ((int)$_GET['id'] == 1) : ?>
    <a href="tabla_estadistica_orientacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar" style="margin-bottom: 15px; margin-top: -15px;">
        Regresar
    </a>
<?php endif; ?>
<?php if ((int)$_GET['id'] == 2) : ?>
    <a href="tabla_estadistica_canalizacion.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar" style="margin-bottom: 15px; margin-top: -15px;">
        Regresar
    </a>
<?php endif; ?>
<!-- Debemos de tener Canvas en la página -->
<div class="panel-body">
    <center>
        <button id="btnCrearPdf" style="margin-top: -15px; background: #FE2C35; color: white; font-size: 12px;" class="btn btn-pdf btn-md">Guardar en PDF</button>
        <div id="prueba">
            <center>
                <?php if ((int)$_GET['id'] == 1) : ?>
                    <h2 style="margin-top: 15px; color: #3a3d44;">Estadística de Orientaciones (Por Municipio)</h2>
                <?php endif; ?>
                <?php if ((int)$_GET['id'] == 2) : ?>
                    <h2 style="margin-top: 15px; color: #3a3d44;">Estadística de Canalizaciones (Por Municipio)</h2>
                <?php endif; ?>
            </center>
            <div class=" row" style="display: flex; justify-content: center; align-items: center; margin-left:-70px;">
                <!-- <div class="col-md-6" style="width: 50%; height: 20%;"> -->
                <div style="width:50%; float:left;">
                    <canvas id="gVulnerableB"></canvas>
                    <!-- Incluímos Chart.js -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <!-- Añadimos el script a la página -->

                    <script>
                        var yValues = [<?php foreach ($municipios as $municipio) : ?><?php echo $municipio['total']; ?>, <?php endforeach; ?>];
                        Chart.defaults.font.family = "Montserrat";
                        Chart.defaults.font.size = 12;

                        const ctx3 = document.getElementById('gVulnerableB');
                        const gVulnerableB = new Chart(ctx3, {
                            type: 'bar',
                            data: {
                                labels: [<?php foreach ($municipios as $municipio) : ?> '<?php echo $municipio['municipio_localidad']; ?>', <?php endforeach; ?>],
                                datasets: [{
                                    label: 'Orientaciones por Municipio',
                                    data: yValues,
                                    backgroundColor: [
                                        '#3E5161', '#C5E2FB', '#90BBE0', '#5A87AD', '#6F90AD', '#6C6E58', '#3E423A', '#417378', '#A4CFBE', '#F4F7D9', '#AC89A6', '#51AFC2', '#427085'
                                    ],
                                    borderColor: [
                                        '#27333D', '#8BA0B3', '#627F99', '#3E5E78', '#405363', '#494A3B', '#22241F', '#2B4C4F', '#6F8C80', '#A9AB96', '#7D6479', '#397A87', '#2D4B59'
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                legend: {
                                    display: false
                                },
                                // El salto entre cada valor de Y
                                ticks: {
                                    min: 0,
                                    max: 10000,
                                    stepSize: 10
                                },
                                scales: {
                                    y: {
                                        ticks: {
                                            color: '#3a3d44ss',
                                            beginAtZero: true
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: '#3a3d44ss',
                                            beginAtZero: true
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
            <!-- Debemos de tener Canvas en la página -->
            <div class=" row" style="display: flex; justify-content: center; align-items: center;">
                <div style="width:40%; float:right; margin-left: 50px;  margin-top: 40px">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-purple">
                            <tr style="height: 10px;">
                                <th class="text-center" style="width: 70%;">Municipios</th>
                                <th class="text-center" style="width: 30%;">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody style="background: white;">
                            <?php $total=0;  foreach ($municipios as $municipio) : ?>
                                <tr>
                                    <td>
                                        <?php echo remove_junk(ucwords($municipio['municipio_localidad'])) ?>
                                    </td>
                                    <td>
                                        <?php echo remove_junk(ucwords($municipio['total'])) ?>
                                        <?php $total = $total + $municipio['total']; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td style="text-align:right;"><b>Total</b></td>
                                <td>
                                    <?php echo $total; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </center>
</div>


<?php include_once('layouts/footer.php'); ?>