<?php
headerAdmin($data);
?>
<div id="contentAjax"></div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1> <i class="bi bi-check2-circle"></i> <?=$data['page_title']?></h1>
        </div>

        <?php if ($_SESSION['permisosMod']['w']) {?>
        <button class="btn btn-warning" type="button" data-bs-toggle="modal" onclick="openModal();">
            <i class="bi bi-plus-lg"></i>
            Nueva Asignación</button>
        <?php }?>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="bi bi-house"></i></li>
            <li class="breadcrumb-item"><a href="<?=base_url();?>/asignaciones"><?=$data['page_title']?></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tableAsignaciones">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">Ficha</th>
                                    <th class="text-center">Instructor</th>
                                    <th class="text-center">N° Competencia</th>
                                    <th class="text-center">Nombre de la Competencia</th>
                                    <th class="text-center">Horas asignadas</th>
                                    <th class="text-center">horas faltantes</th>
                                    <th class="text-center">Progreso</th>
                                    <th class="text-center">Mes</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerAdmin($data);
getModal('modalAsignaciones', $data);
?>