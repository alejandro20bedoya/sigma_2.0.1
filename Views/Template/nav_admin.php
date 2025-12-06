    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">

        <!-- esta es la imagennd ell perfil -->
        <div class="mb-2 text-center">
            <?php
            // Tomar el nombre de la foto desde la sesión (que viene de la BD)
            $foto = $_SESSION['userData']['imgperfil'];

            // Ruta física donde se guarda la imagen
            $rutaLocal = "Assets/images/uploads/perfiles/" . $foto;

            // Ruta web para mostrar la imagen en el navegador
            $rutaWeb = media() . "/images/uploads/perfiles/" . $foto;

            // Validar si la imagen existe físicamente
            if (empty($foto) || !file_exists($rutaLocal)) {
                $rutaWeb = media() . "/images/uploads/perfiles/sinimagen.jpg";
            }
            ?>

            <img src="<?= $rutaWeb; ?>"
                class="rounded-circle shadow"
                width="50"
                height="50"
                style="object-fit: cover; border: 2px solid white;">
        </div>

        <div class="text-center">
            <p class="app-sidebar__user-name text-white fw-bold fs-5 mb-0">
                <?= $_SESSION['userData']['nombres']; ?>
            </p>
            <p class="app-sidebar__user-designation text-white-50 fst-italic mb-0">
                <?= $_SESSION['userData']['nombrerol']; ?>
            </p>
        </div>




        <ul class="app-menu">
            <li>
                <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
                    <i class="app-menu__icon bi bi-house"></i>
                    <span class="app-menu__label">Inicio</span>
                </a>
            </li>

            <li>
                <a class="app-menu__item" href="<?= base_url(); ?>/roles">
                    <i class="app-menu__icon bi bi-toggles"></i>
                    <span class="app-menu__label">Role</span>
                </a>
            </li>

            <li>
                <a class="app-menu__item " href="<?= base_url(); ?>/usuarios">
                    <i class="app-menu__icon bi bi-people"></i>
                    <span class="app-menu__label">Usuarios</span>
                </a>
            </li>

            <li>
                <a class="app-menu__item" href="<?= base_url(); ?>/programas">
                    <i class="app-menu__icon bi bi-inboxes"></i>
                    <span class="app-menu__label">Programas</span>
                </a>
            </li>

            <li>
                <a class="app-menu__item" href="<?= base_url(); ?>/fichas">
                    <i class="app-menu__icon bi bi-clipboard-plus"></i>
                    <span class="app-menu__label">Fichas</span>
                </a>
            </li>

            <li>
                <a class="app-menu__item" href="<?= base_url(); ?>/competencias">
                    <i class="app-menu__icon bi bi-ui-checks-grid"></i>
                    <span class="app-menu__label">Competencias</span>
                </a>
            </li>


            <li>
                <a class="app-menu__item " href="<?= base_url(); ?>/asignaciones">
                    <i class="app-menu__icon bi bi-card-checklist"></i>
                    <span class="app-menu__label">Asignaciones</span>
                </a>
            </li>



            <li>
                <a class="bg-danger app-menu__item" href="<?= base_url(); ?>/logout">
                    <i class="app-menu__icon bi bi-escape"></i>
                    <span class="app-menu__label">Salir</span>
                </a>
            </li>

        </ul>

    </aside>