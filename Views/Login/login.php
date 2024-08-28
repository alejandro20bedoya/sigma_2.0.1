<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Sigma">
    <link rel="shortcut icon" href="<?=media();?>/images/favicon.ico">

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?=media();?>/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link href="
https://cdn.jsdelivr.net/npm/bootstrap-sweetalert@1.0.1/dist/sweetalert.min.css
" rel="stylesheet">

    <title><?=$data['page_tag'];?></title>
</head>

<body>
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="login-content">
        <div class="logo">
            <h1>SigmaSoft</h1>
        </div>
        <div class="login-box">
            <div id="divLoading">
                <div class="spinner-border visually-hidden" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>

            <form class="login-form" name="formLogin" id="formLogin" action="">
                <h3 class="login-head"><i class="bi bi-person me-2"></i>Iniciar Sesión</h3>

                <div class="mb-3">
                    <br>
                    <label class="form-label">Identificación</label>
                    <input id="txtIdentificacion" name="txtIdentificacion" class="form-control" type="number"
                        placeholder="Identificación" autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input id="txtPassword" name="txtPassword" class="form-control" type="password"
                        placeholder="Contraseña">
                </div>

                <div id="alertLogin" class="text-center"></div>

                <div class="mb-3 btn-container d-grid">
                    <button type="submit" class="btn btn-primary btn-block"><i
                            class="bi bi-box-arrow-in-right me-2 fs-5"></i> Iniciar
                        Sesión</button>
                </div>
            </form>

        </div>
    </section>

    <!-- ORIGINAL -->
    <script>
    const base_url = "<?=base_url();?>";
    </script>

    <!-- Essential javascripts for application to work-->
    <script src="<?=media();?>/js/jquery-3.7.0.min.js"></script>

    <script src="<?=media();?>/js/popper.min.js"></script>

    <script src="<?=media();?>/js/bootstrap.min.js"></script>

    <script src="<?=media();?>/js/fontawesome.js"></script>

    <script src="<?=media();?>/js/main.js"></script>
    <script type="text/javascript">
    // Login Page Flipbox control
    $('.login-content [data-toggle="flip"]').click(function() {
        $('.login-box').toggleClass('flipped');
        return false;
    });
    </script>

    <!-- JavaScript-->
    <script src="<?=media();?>/js/plugins/pace.min.js"></script>
    <script src="
https://cdn.jsdelivr.net/npm/bootstrap-sweetalert@1.0.1/dist/sweetalert.min.js
"></script>

    <script src="<?=media();?>/js/<?=$data['page_functions_js'];?>"></script>

</body>

</html>