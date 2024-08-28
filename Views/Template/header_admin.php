<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Sigma">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="TPS La Jagua de Ibirico">
    <link rel="shortcut icon" href="<?=media();?>/images/favicon.ico">
    <title><?=$data['page_tag']?></title>

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?=media();?>/css/main.css">
    <!-- Font-icon css 2024-->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Javascripts-->
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css">

    <link href="
https://cdn.jsdelivr.net/npm/bootstrap-sweetalert@1.0.1/dist/sweetalert.min.css
" rel="stylesheet">

    <link rel="stylesheet" href="<?=media();?>/js/fullcalendar/lib/main.css">
    <script src="<?=media();?>/js/fullcalendar/jquery-3.6.0.min.js"></script>
    <script src="<?=media();?>/js/fullcalendar/lib/main.min.js"></script>
    <script type='text/javascript' src='<?=media();?>/js/fullcalendar/locale/es.js'></script>

</head>

<body class="app sidebar-mini">

    <div id="divLoading">
        <div class="spinner-border visually-hidden" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="<?=base_url();?>/dashboard">SigmaSoft</a>
        <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
            aria-label="Hide Sidebar"></a>
        <!-- Navbar Menu-->
        <ul class="app-nav">

            <!-- Menu Uusario -->
            <li class="dropdown"><a class="app-nav__item" href="#" data-bs-toggle="dropdown"
                    aria-label="Open Profile Menu"><i class="bi bi-person fs-4"></i></a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="<?=base_url();?>/dashboard"><i class="bi bi-gear me-2 fs-5"></i>
                            Configuración</a></li>
                    <li><a class="dropdown-item" href="<?=base_url();?>/dashboard"><i
                                class="bi bi-person me-2 fs-5"></i> Perfil</a>
                    </li>
                    <li><a class="dropdown-item" href="<?=base_url();?>/logout"><i
                                class="bi bi-box-arrow-right me-2 fs-5"></i>
                            Salir</a></li>
                </ul>
            </li>
        </ul>
    </header>

    <?php require_once "nav_admin.php";?>