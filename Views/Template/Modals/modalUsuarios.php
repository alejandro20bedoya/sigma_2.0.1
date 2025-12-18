<!-- Modal -->
<div class="modal fade" id="modalFormUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formUsuario"" name=" formUsuario" enctype="multipart/form-data" method="POST">
                            <!-- id de los usuarios    -->
                            <input type="text" id="ideUsuario"" name=" ideUsuario"" value="">
                            <div class="modal-body">
                                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son
                                    obligatorios.
                                </p>
                                <hr>
                                <p class="text-primary">Datos del Usuario</p>
                            </div>

                            <div class="modal-body">
                                <label for="txtIdentificacionUsuario"">Identificación<span class="
                                    required">*</span></label>
                                <input type="number" class="form-control valid validNumber" id="txtIdentificacionUsuario"
                                    name="txtIdentificacionUsuario" required="" maxlength="15"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtNombresUsuario"">Nombres<span class="
                                    required">*</span></label>
                                <input type="text" class="form-control valid validText" id="txtNombresUsuario"
                                    name="txtNombresUsuario" required="" maxlength="40"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtApellidosUsuario"">Apellidos<span class="
                                    required">*</span></label>
                                <input type="text" class="form-control valid validText" id="txtApellidosUsuario"
                                    name="txtApellidosUsuario" required="" maxlength="30"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtCelularUsuario"">Celular<span class="
                                    required">*</span></label>
                                <input type="number" class="form-control valid validText" id="txtCelularUsuario"
                                    name="txtCelularUsuario" required="" maxlength="10"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtCorreoUsuario"">Correo<span class="
                                    required">*</span></label>
                                <input type="text" class="form-control valid validText" id="txtCorreoUsuario"
                                    name="txtCorreoUsuario" required="" maxlength="100"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="listRol" class="form-label fw-bold">Rol de Usuario</label>
                                <select class="form-select form-select-lg mb-3 text-dark bg-light rounded" id="listRol" name="listRol" required>
                                    <option value="">Seleccione un rol</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Coordinador</option>
                                    <option value="4">Instructor</option>
                                    <option value="5">Aprendiz</option>
                                    <option value="6">Usuario</option>
                                </select>
                            </div>

                            <div class="modal-body">
                                <label for="listStatus" class="form-label fw-bold">Estado</label>
                                <select class="form-select form-select-lg mb-3 text-dark bg-light rounded" id="listStatus" name="listStatus" required>
                                    <option value="">Seleccione un estado</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>


                            <div class="modal-footer">
                                <button id="btnActionForm" class="btn btn-success" type="submit"><i
                                        class="bi bi-send"></i><span id="btnText">Guardar</span></button>

                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal"><i
                                        class="bi bi-x-lg"></i>Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalViewUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">


                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>ID:</td>
                                    <td id="celIdeUsuario">233104</td>
                                </tr>

                                <tr>
                                    <td>Identificación:</td>
                                    <td id="celIdentificacionUsuario">233104</td>
                                </tr>
                                <tr>
                                    <td>Nombre:</td>
                                    <td id="celNombresUsuario">233104</td>
                                </tr>
                                <tr>
                                    <td>Apellidos:</td>
                                    <td id="celApellidosUsuario">233104</td>
                                </tr>

                                <tr>
                                    <td>Celular:</td>
                                    <td id="celCelularUsuario">233104</td>
                                </tr>

                                <tr>
                                    <td>Correo:</td>
                                    <td id="celCorreoUsuario">233104</td>
                                </tr>

                                <tr>
                                    <td>Rol:</td>
                                    <td id="celRolUsuario">2875079</td>
                                </tr>

                                <tr>
                                    <td>Estado:</td>
                                    <td id="celEstadoUsuario">Programación de Software</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"><i
                                class="bi bi-check2"></i>Listo</button>
                    </div>

                </div>
            </div>
        </div>