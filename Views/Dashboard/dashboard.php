<?php headerAdmin($data); ?>
<?php
// Ejemplo: valor que viene del modelo
$progreso = isset($data['progresoAsignaciones']) ? $data['progresoAsignaciones'] : 0;
?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Roboto, sans-serif;
    }

    .card-header {
        border-bottom: none;
        padding: 10px 15px;
        margin-bottom: center;
        /* ahora sí funciona */
        text-align: center;
    }


    .card-header select {
        background: #1c1c1c;
        border: 1px solid #444;
        color: #f5f5f5;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 0.85rem;
        outline: none;
        cursor: pointer;
    }


    /* Contenedor centrado */
    .container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-top: 40px;
        gap: 30px;
    }

    /* ===== TARJETA ===== */
    .card {
        position: relative;
        width: 280px;
        height: 300px;
        background: #2a2a2a;
        border-radius: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: inset 0 0 10px #0005, 0 0 10px #0005;
    }

    /* ===== CÍRCULO ===== */
    .percent {
        position: relative;
        width: 160px;
        height: 160px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .percent svg {
        position: absolute;
        width: 160px;
        height: 160px;
        top: 0;
        left: 0;
        transform: rotate(-90deg);
    }

    .percent svg circle {
        fill: none;
        stroke-width: 10;
        stroke: #191919;
    }

    .percent svg circle.progress {
        stroke: var(--clr);
        stroke-dasharray: 440;
        stroke-dashoffset: 440;
        stroke-linecap: round;
        transition: stroke-dashoffset 2s ease;
    }

    /* ===== CONTENIDO CENTRAL ===== */
    .number {
        position: relative;
        z-index: 2;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* centra verticalmente */
        align-items: center;
        /* centra horizontalmente */
        text-align: center;
        color: #fff;
        opacity: 0;
        animation: fadeIn 1s ease forwards;
        animation-delay: 1s;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }

    .number label {
        color: #ddd;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }

    .number .selector-container {
        position: absolute;
        top: 20px;
        /* 🔹 Ajusta este valor si quieres subir/bajar el selector */
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
    }

    .number select {
        background: #1c1c1c;
        border: 1px solid #444;
        color: #f5f5f5;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 0.85rem;
        outline: none;
        cursor: pointer;
        transition: 0.3s ease;
        margin-bottom: 10px;

    }

    .number select:hover {
        border-color: #666;
        background: #232323;
    }

    .number select:focus {
        border-color: #04fc43;
        box-shadow: 0 0 0 2px rgba(4, 252, 67, 0.15);
    }

    .number h2 {
        font-size: 2.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        line-height: 1em;
    }

    .number h2 span {
        font-size: 1.2rem;
        color: #ccc;
        margin-left: 3px;
    }

    .number p {
        margin-top: 5px;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* compentencias */
    .competencia-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 12px 16px;
        margin-bottom: 12px;
        border-radius: 10px;
        gap: 20px;
        /* 👉 separa texto y estado */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .competencia-box strong {
        font-size: 18px;
        word-break: break-word;
        /* 👉 evita que los textos largos dañen el diseño */
    }

    .estado {
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 14px;
        white-space: nowrap;
        /* 👉 evita que el texto se parta en 2 líneas */
    }

    .estado.progreso {
        background: #FFD500;
        color: #000;
    }

    .estado.completado {
        background: #4CAF50;
        color: white;
    }

    .card-header {
        border-bottom: none !important;
    }

    .card-header h3 {
        margin-bottom: -10px;
    }

    /* ======== RESPONSIVE PARA CELULAR ======== */
    @media (max-width: 600px) {

        /* Contenedor principal: de horizontal → vertical */
        .container {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center;
            gap: 20px;
            width: 100%;
        }

        /* Las tarjetas ocupan el 95% del ancho en móvil */
        .card {
            width: 95% !important;
            height: auto !important;
        }

        /* Input de búsqueda ajustado */
        #buscarCompetencia {
            width: 100%;
            font-size: 1rem;
            padding: 12px;
        }

        /* Lista de competencias 100% width */
        #competenciasContainer {
            width: 100%;
        }

        .competencia-box {
            width: 100%;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    #selectPrograma {
        width: 120px;
        /* 👈 más pequeño */
        background: #000000ff;
        border: 1px solid #d1d1d1;
        color: #ffffffff;
        padding: 6px 8px;
        margin-top: 10px;
        /* 👈 padding reducido */
        font-size: 0.85rem;
        /* 👈 texto más pequeño */
        border-radius: 6px;
        cursor: pointer;
        transition: 0.2s ease-in-out;
    }

    /* Hover */
    #selectPrograma:hover {
        border-color: #888;
    }

    /* Focus */
    #selectPrograma:focus {
        outline: none;
        border-color: #4a90e2;
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.25);
    }
</style>

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

        <?php if (!empty($_SESSION['permisos'][2]['r'])) { ?>
            <div class="col-md-6 col-lg-3">
                <a href="<?= base_url() ?>/usuarios" class="linkw">
                    <div class="widget-small primary coloured-icon"><i class="icon bi bi-people fs-1"></i>
                        <div class="info">
                            <h4>Usuarios</h4>
                            <p><b><?= $data['usuarios'] ?></b></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>


        <?php if (!empty($_SESSION['permisos'][2]['r'])) { ?>
            <div class="col-md-6 col-lg-3">
                <a href="<?= base_url() ?>/programas" class="link-info">
                    <div class="widget-small info coloured-icon">
                        <i class="icon bi bi-list-stars fs-1"></i>
                        <div class="info">
                            <h4>Programas</h4>
                            <p><b><?= $data['programas'] ?></b></p>
                        </div>
                    </div>
            </div>
            </a>
    </div>

    <div class="container">
        <div class="card" style="--clr:#04fc43;">
            <div class="card-header" style="color: #ddd;">
                <h4>Buscar por Ficha</h4>
                <select id="selectFicha">
                    <?php foreach ($data['fichas'] as $ficha) { ?>
                        <option value="<?= $ficha['programaficha']; ?>"><?= $ficha['programaficha']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="percent" data-num="<?= $data['progresoAsignaciones']; ?>">
                <svg>
                    <circle cx="80" cy="80" r="70"></circle>
                    <circle class="progress" cx="80" cy="80" r="70"></circle>
                </svg>

                <div class="number">

                    <h2 id="progressValue">0<span>%</span></h2>
                    <p>Asignaciones</p>
                </div>
            </div>
        </div>
        <!-- CARD 2: Competencias -->
        <div class="card" style="--clr:#04fc43;">
            <div class="card-header" style="color: #ddd;">
                <h3>Competencias</h3>
            </div>
            <div>
                <select id="selectPrograma">
                    <option value="todos">Todos</option>
                    <option value="progreso">En progreso</option>
                    <option value="completado">Completado</option>
                </select>
            </div>

            <div class="p-3">
                <!-- <input type="text" id="buscarCompetencia" class="form-control mb-3" placeholder="Buscar competencia..."> -->
                <div id="competenciasContainer">
                    <?php foreach ($data['competencias'] as $c) {
                        $estado = ($c['totalhoras'] == 0) ? "Completado" : "En progreso";
                        $clase  = ($c['totalhoras'] == 0) ? "completado" : "progreso";
                    ?>
                        <div class="competencia-box">
                            <div>
                                <strong><?= $c['codigocompetencia'] ?></strong><br>
                                <small>Horas: <?= $c['totalhoras'] ?></small>
                            </div>

                            <div class="estado <?= strtolower($clase) ?>">
                                <?= $estado ?>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>

    </div>

<?php } ?>

</main>
<?php footerAdmin($data); ?>