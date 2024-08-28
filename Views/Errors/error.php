<?php
// headerSigma($data);
headerAdmin($data);
?>
<script>
document.querySelector('header').classList.add('header-v4');
</script>
<div class="container text-center">
    <main class="app-content">
        <div class="page-error tile">
            <?=$data['page']['contenido'];?>
            <p><a class="btn btn-dark" href="javascript:window.history.back();">Regresar</a></p>
        </div>
    </main>
</div>

<?php footerAdmin($data);?>