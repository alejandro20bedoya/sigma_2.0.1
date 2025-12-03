<!-- Modal: Nueva Ficha -->
<div class="modal fade" id="modalFormFicha" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Ficha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formFicha" name="formFicha" enctype="multipart/form-data" method="POST">

                            <input type="hidden" id="ideFicha" name="ideFicha" value="">


                            <!-- Info -->
                            <p class="text-primary">
                                Los campos con asterisco (<span class="required">*</span>) son obligatorios.
                            </p>
                            <hr>
                            <p class="text-primary fw-bold">Datos de la Ficha</p>

                            <!-- Código Programa ingresar-->
                            <div class="mb-3">
                                <label for="txtCodigoPrograma" class="form-label">
                                    Código del Programa <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control validNumber" id="txtCodigoPrograma"
                                    name="txtCodigoPrograma"
                                    onchange="fntViewInfoCodigoPrograma(this.value);"
                                    maxlength="10" required
                                    onkeypress="return controlTag(event);">
                            </div>

                            <!-- Nombre Programa ver-->
                            <div class="mb-3">
                                <label for="txtNombrePrograma" class="form-label">
                                    Nombre del Programa <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="txtNombrePrograma"
                                    name="txtNombrePrograma"
                                    style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    readonly
                                    required>
                            </div>

                            <!-- Número Ficha ingresar-->
                            <div class="mb-3">
                                <label for="txtFichaPrograma" class="form-label">
                                    Número de Ficha <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control validNumber" id="txtFichaPrograma"
                                    name="txtFichaPrograma" required>
                            </div>

                            <!-- Identificación Instructor ingresar-->
                            <div class="mb-3">
                                <label for="txtIdeInstructor" class="form-label">
                                    Identificación del Instructor <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control validNumber" id="txtIdeInstructor"
                                    name="txtIdeInstructor"
                                    onchange="fntViewInfoIdeInstructor(this.value);"
                                    maxlength="10" required
                                    onkeypress="return controlTag(event);">
                            </div>

                            <!-- Nombre Instructor ver-->
                            <div class="mb-3">
                                <label for="txtNombreInstructor" class="form-label">
                                    Nombre del Instructor <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="txtNombreInstructor"
                                    name="txtNombreInstructor" 
                                     style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    readonly 
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="listStatus" class="form-label">Estado</label>
                                <select class="form-select" id="listStatus" name="listStatus" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>

                            <!-- Footer -->
                            <div class="modal-footer">
                                <button id="btnActionForm" class="btn btn-success" type="submit">
                                    <i class="bi bi-floppy"></i> <span id="btnText">Guardar</span>
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg"></i> Cerrar
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal: PARA VER LA FICHA -->
<div class="modal fade" id="modalViewFicha" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header header-primary">
                <h5 class="modal-title">Detalle de Ficha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td id="celIdeFicha"></td>
                                </tr>
                                <tr>
                                    <td><strong>Código Programa:</strong></td>
                                    <td id="celCodigoPrograma"></td>
                                </tr>
                                <tr>
                                    <td><strong>Número de Ficha:</strong></td>
                                    <td id="celNumeroFicha"></td>
                                </tr>
                                <tr>
                                    <td><strong>Instructor:</strong></td>
                                    <td id="celIdeInstructor"></td>
                                </tr>
                                <tr>
                                    <td><strong>Programa:</strong></td>
                                    <td id="celPrograma"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                            <i class="bi bi-check2"></i> Listo
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>