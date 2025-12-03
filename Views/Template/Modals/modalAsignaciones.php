<!-- Modal -->
<div class="modal fade" id="modalFormAsignacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Asignación</h5>
                <button type="button" id="cerrarModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formAsignacion" name="formAsignacion" enctype="multipart/form-data" method="POST">
                            <input type="hidden" id="ideDetalleFicha" name="ideDetalleFicha" value="">
                            <div class="modal-body">
                                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son
                                    obligatorios.
                                </p>
                                <hr>
                                <p class="text-primary">Datos de la Asignación</p>
                            </div>




                            <div class="modal-body">
                                <label for="txtNumeroFicha">Número de Ficha<span class="required">*</span></label>
                                <input type="text" class="form-control validNumber" id="txtNumeroFicha"
                                    onchange="fntViewInfoIdeFicha(this.value);" name="txtNumeroFicha" required=""
                                    maxlength="10" onkeypress="return controlTag(event);">
                            </div>
                            <!-- nombre de las fichas es un buscador  -->
                            <div class="modal-body">
                                <label for="txtNombreFicha">Nombre de la FICHA <span class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNombreFicha" name="txtNombreFicha"
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



                            <div class="modal-body">
                                <label for="txtCodigoCompetencia">Codigo de la COMPETENCIA<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control validNumber" id="txtCodigoCompetencia"
                                    onchange="fntViewInfoCodigoCompetencia(this.value);" name="txtCodigoCompetencia"
                                    required="" maxlength="10" onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtNombreCompetencia">Nombre de la COMPETENCIA<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNombreCompetencia"
                                    name="txtNombreCompetencia" required=""
                                    style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    disabled>
                            </div>

                            <div class="modal-body">
                                <label for="txtHorasTotalCompetencia">HORAS HORAS FALTANTES DE LA COMPETENCIA<span
                                        class="required"></span></label>
                                <input type="text" class="form-control" id="txtHorasTotalCompetencia"
                                    name="txtHorasTotalCompetencia" required="" 
                                     style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    disabled>
                            </div>

                            <div class="modal-body">
                                <label for="txtHorasSumaAsignacionCompetencia">HORAS ASIGNADAS DE LA COMPETENCIA<span class="required">*</span></label>
                                <input type="text" class="form-control" id="txtHorasSumaAsignacionCompetencia"
                                    name="txtHorasSumaAsignacionCompetencia" required=""
                                    style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    disabled>
                            </div>

                            <div class="modal-body">
                                <label for="txtHorasPendienteCompetencia">HORAS PENDIENTES<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtHorasPendienteCompetencia"
                                    name="txtHorasPendienteCompetencia"  placeholder="No hay horas"
                                     style="
                                        background-color: #d4edda;      
                                        border: 2px solid #28a745;       
                                        color: #000000ff;                 
                                        font-weight: 600;
                                        border-radius: 8px;
                                        box-shadow: 0 0 5px rgba(40,167,69,0.3);
                                        transition: all 0.3s ease;"
                                    required="" readonly>
                                <!-- Mensaje de salida -->
                                <p id="mensaje"></p>
                            </div>

                            <!-- numero de documento -->
                            <div class="modal-body">
                                <label for="txtIdeInstructor">Identificación del Instructor<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control validNumber" id="txtIdeInstructor"
                                    onchange="fntViewInfoIdeInstructor(this.value);" name="txtIdeInstructor" required=""
                                    maxlength="10" onkeypress="return controlTag(event);">
                            </div>

                            <div class="modal-body">
                                <label for="txtNombreInstructor">Nombre del INSTRUCTOR <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control"
                                    id="txtNombreInstructor"
                                    name="txtNombreInstructor"
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

                            <div class="modal-body">
                                <label for="txtNumeroHoras">Cantidad de horas ASIGNAR<span
                                        class="required">*</span></label>
                                <input type="text" class="form-control validNumber" id="txtNumeroHoras" required=""  title="Si no va ingresar horas debe ingresar 0"
                                    onchange="ftnSumarCantidadHoras(this.value);" name="txtNumeroHoras" > 
                                <p id="mensajeError" style="color: red;"></p>
                            </div>

                            <div class="modal-body">
                                <select class="form-select" id="listadoMeses" name="listadoMeses" required=""
                                    aria-label="Default select example">
                                    <label for="listadoMeses">Selecciona el mes</label>
                                    <option value="Enero">Enero</option>
                                    <option value="Febrero">Febrero</option>
                                    <option value="Marzo">Marzo</option>
                                    <option value="Abril">Abril</option>
                                    <option value="Mayo">Mayo</option>
                                    <option value="Junio">Junio</option>
                                    <option value="Julio">Julio</option>
                                    <option value="Agosto">Agosto</option>
                                    <option value="Septiembre">Septiembre</option>
                                    <option value="Octubre">Octubre</option>
                                    <option value="Noviembre">Noviembre</option>
                                    <option value="Diciembre">Diciembre</option>
                                </select>
                            </div>

                            <!-- <div class="modal-body">
                                <label for="txtNombreCompetencia">Nombre del COMPETENCIA <span
                                        class="required">*</span></label>
                                <input type="text" class="form-control" id="txtNombreCompetencia"
                                    name="txtNombreCompetencia" required="" disabled>
                            </div> -->

                            <BR></BR>
                            <div class="modal-footer">
                                <button id="btnActionForm" class="btn btn-success" type="submit"><i
                                        class="bi bi-floppy"></i><span id="btnText">Guardar</span></button>

                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal" id="cerrarModal"><i
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
<div class="modal fade" id="modalViewAsignacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">ASIGNAR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">


                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Ficha:</td>
                                    <td id="celFicha">233104</td>
                                </tr>

                                <tr>
                                    <td>Nombre Ficha:</td>
                                    <td id="celNombreFicha">233104</td>
                                </tr>

                                <tr>
                                    <td>Instructor:</td>
                                    <td id="celInstructor">233104</td>
                                </tr>

                                <tr>
                                    <td>Nombre Competencia:</td>
                                    <td id="celCompetencia">Programación de Software</td>
                                </tr>

                                <tr>
                                    <td>N° Competencia:</td>
                                    <td id="celNCompetencia">Programación de Software</td>
                                </tr>

                                <tr>
                                    <td>Horas:</td>
                                    <td id="celHoras">Horas</td>
                                </tr>
                                <tr>
                                    <td>Horas Faltates:</td>
                                    <td id="celHorasfaltantes">Horas</td>
                                </tr>

                                <tr>
                                    <td>Mes:</td>
                                    <td id="celMes">2875079</td>
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