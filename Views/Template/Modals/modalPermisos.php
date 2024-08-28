<div class="modal fade modalPermiso" id="modalFormPermiso" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Permisos Roles <?=$data['rol']?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="modal-body">
                    <div class="tile">
                        <div class="tile-body">
                            <form action="" id="formPermiso" name="formPermiso">
                                <input type="hidden" id="idrol" name="idrol" value="<?=$data['idrol'];?>" required="">

                                <div class="modal-body">
                                    <p class="text-primary">Selecciona las opciones de los permisos
                                    </p>
                                    <hr>
                                </div>

                                <div class="table-responsive ">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Ìtem</th>
                                                <th>Módulo</th>
                                                <th>Ver</th>
                                                <th>Crear</th>
                                                <th>Actualizar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
$no = 1;
$modulos = $data['modulos'];
for ($i = 0; $i < count($modulos); $i++) {

    $permisos = $modulos[$i]['permisos'];
    $rCheck = $permisos['r'] == 1 ? " checked " : "";
    $wCheck = $permisos['w'] == 1 ? " checked " : "";
    $uCheck = $permisos['u'] == 1 ? " checked " : "";
    $dCheck = $permisos['d'] == 1 ? " checked " : "";

    $idmod = $modulos[$i]['idmodulo'];
    ?>
                                            <tr>
                                                <td>
                                                    <?=$no;?>
                                                    <input type="hidden" name="modulos[<?=$i;?>][idmodulo]"
                                                        value="<?=$idmod?>" required>
                                                </td>
                                                <td>
                                                    <?=$modulos[$i]['titulo'];?>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <label>
                                                            <input class="form-check-input" role="switch"
                                                                type="checkbox" name="modulos[<?=$i;?>][r]"
                                                                <?=$rCheck?>><span class="btn-check" data-toggle-on="ON"
                                                                data-toggle-off="OFF"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <label>
                                                            <input class="form-check-input" role="switch"
                                                                type="checkbox" name="modulos[<?=$i;?>][w]"
                                                                <?=$wCheck?>><span class="flip-indecator"
                                                                data-toggle-on="ON" data-toggle-off="OFF"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <label>
                                                            <input class="form-check-input" role="switch"
                                                                type="checkbox" name="modulos[<?=$i;?>][u]"
                                                                <?=$uCheck?>><span class="flip-indecator"
                                                                data-toggle-on="ON" data-toggle-off="OFF"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <label>
                                                            <input class="form-check-input" role="switch"
                                                                type="checkbox" name="modulos[<?=$i;?>][d]"
                                                                <?=$dCheck?>><span class="flip-indecator"
                                                                data-toggle-on="ON" data-toggle-off="OFF"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
$no++;
}
?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-success" type="submit"><i class="bi bi-send"></i>
                                        Guardar</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i
                                            class="bi bi-x-lg"></i>Cancelar</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>