<?php headerAdmin($data);?>

<main class="app-content">
    <div class="app-title">
        <div>
            <h1>
                <i class="bi bi-house"></i>
                </i> Inicio
            </h1>
            <p>Sistema de Información para la Gestión de Módulos Académicos - SIGMA</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="bi bi-house-door fs-6"></i></li>
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
        </ul>
    </div>
    <div class="row">

        <?php if (!empty($_SESSION['permisos'][2]['d'])) {?>
        <div class="col-md-6 col-lg-3">
            <a href="<?=base_url()?>/usuarios" class="linkw">
                <div class="widget-small primary coloured-icon"><i class="icon bi bi-people fs-1"></i>
                    <div class="info">
                        <h4>Usuarios</h4>
                        <p><b><?=$data['usuarios']?></b></p>
                    </div>
                </div>
            </a>
        </div>
        <?php }?>


        <?php if (!empty($_SESSION['permisos'][2]['d'])) {?>
        <div class="col-md-6 col-lg-3">
            <a href="<?=base_url()?>/programas" class="link-info">
                <div class="widget-small info coloured-icon">
                    <i class="icon bi bi-list-stars fs-1"></i>
                    <div class="info">
                        <h4>Programas</h4>
                        <p><b><?=$data['programas']?></b></p>
                    </div>
                </div>
        </div>
        </a>
    </div>
    <?php }?>

</main>
<?php footerAdmin($data);?>