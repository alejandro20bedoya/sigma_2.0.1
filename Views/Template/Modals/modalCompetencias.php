<!-- Modal -->
<div class="modal fade" id="modalFormCompetencia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Competencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formCompetencia" name="formCompetencia" enctype="multipart/form-data" method="POST">
                            <!-- // id de la competencia -->
                            <input type="hidden" id="ideCompetencia" name="ideCompetencia" value="">

                            <div class="modal-body">
                                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son
                                    obligatorios.
                                </p>
                                <hr>
                                <p class="text-primary">Datos de la Competencia</p>
                            </div>
                            <div class="modal-body">
                                <label for="txtFicha">Ficha en la que estas inscripto<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control valid validNumber" id="txtFicha"
                                    name="txtFicha" required="" maxlength="10"
                                    onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtCodigoCompetencia">Código de la Competencia<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control valid validNumber" id="txtCodigoCompetencia"
                                    name="txtCodigoCompetencia" required="" maxlength="10"
                                    onkeypress="return controlTag(event);">
                            </div>


                            <div class="modal-body">
                                <label for="txtNombreCompetencia">Nombre de la Competencia <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control validText" id="txtNombreCompetencia"
                                    name="txtNombreCompetencia" required="">
                            </div>

                            <div class="modal-body">
                                <label for="txtHorasCompetencia">Horas de la COMPETENCIA <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control validNumber" id="txtHorasCompetencia"
                                    name="txtHorasCompetencia" required="" maxlength="10"
                                    onkeypress="return controlTag(event);">
                            </div>


                            <div class="modal-body">
                                <label for="txtCodigoPrograma">Código del PROGRAMA <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control validNumber" id="txtCodigoPrograma"
                                    onchange="fntViewInfoCodigoPrograma(this.value);" name="txtCodigoPrograma"
                                    required="" maxlength="10" onkeypress="return controlTag(event);">
                            </div>

                            <!-- <div class="modal-body">
                                <label for="ListadoProgramas">Programas <span class="required">*</span></label>
                                <select class="form-control" id="ListadoProgramas" name="ListadoProgramas"
                                    onchange="fntViewInfoCodigoPrograma(this.value);">
                                    <option value="" selected>Selecciona el programa</option>
                                </select>
                            </div> -->

                            <div class="modal-body">
                                <label for="txtNombrePrograma">Nombre del PROGRAMA <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNombrePrograma" name="txtNombrePrograma"
                                    style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    required="" disabled>
                            </div>

                            <!-- tipo de competencia  -->
                            <div class="modal-body">
                                <label for="txtTipoCompetencia">tipo Competencia<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control valid validNumber" id="txtTipoCompetencia"
                                    style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    required="" readonly
                                    name="txtTipoCompetencia" required="" maxlength="10"

                                    onkeypress="return controlTag(event);">
                            </div>


                            <BR></BR>
                            <div class="modal-footer">
                                <button id="btnActionForm" class="btn btn-success" type="submit"><i
                                        class="bi bi-floppy"></i><span id="btnText">Guardar</span></button>

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
<div class="modal fade" id="modalViewCompetencia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos de la Competencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">


                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Codigo:</td>
                                    <td id="celCodigoCompetencia">233104</td>
                                </tr>
                                <tr>
                                    <td>Codigo:</td>
                                    <td id="celTipoCompetencia">233104</td>
                                </tr>

                                <tr>
                                    <td>Nombre Competencia:</td>
                                    <td id="celNombreCompetencia">Programación de Software</td>
                                </tr>

                                <tr>
                                    <td>Horas de la Competencia:</td>
                                    <td id="celHorasCompetencia">Horas Competencia</td>
                                </tr>

                                <tr>
                                    <td>Programa:</td>
                                    <td id="celCodigoPrograma">2875079</td>
                                </tr>
                                <tr>
                                    <td>Programa nombre:</td>
                                    <td id="celNombrePrograma">2875079</td>
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