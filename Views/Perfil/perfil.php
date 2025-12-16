<?php
headerAdmin($data);
?>
<div id="contentAjax"></div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="bi bi-people"></i> <?= $data['page_title'] ?>
                <?php if ($_SESSION['permisosMod']['w']) { ?>
            </h1>
        </div>
        <!-- <button class="btn btn-warning" type="button" onclick="openModalPerfil();">
            <i class="bi bi-image"></i>
            Cambiar foto de Perfil
        </button> -->

    <?php } ?>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="bi bi-house"></i></li>
        <li class="breadcrumb-item"><a href="<?= base_url(); ?>/perfil"><?= $data['page_title'] ?></a></li>
    </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tablePerfil">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Identificación</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>celular</th>
                                    <th>correo</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerAdmin($data);
getModal('modalPerfiles', $data);
?>