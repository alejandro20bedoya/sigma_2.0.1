<!-- Modal -->
<div class="modal fade" id="modalFormRol" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Rol</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formRol" name="formRol">
                            <input type="hidden" id="idRol" name="idRol" value="">
                            <div class="modal-body">
                                <label class="control-label">Nombre</label>
                                <input class="form-control" id="txtNombre" name="txtNombre" type="text"
                                    placeholder="Nombre del rol" required="">
                            </div>
                            <div class="modal-body">
                                <label class="control-label">Descripción</label>
                                <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="2"
                                    placeholder="Descripción del rol" required=""></textarea>
                            </div>
                            <div class="modal-body">

                                <select class="form-select" id="listStatus" name="listStatus" required=""
                                    aria-label="Default select example">
                                    <label for="exampleSelect1">Estado</label>
                                    <!-- <option selected>Estado</option> -->
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>


                            </div>
                            <div class="modal-footer">

                                <button id="btnActionForm" class="btn btn-success" type="submit"><i
                                        class="bi bi-send"></i><span id="btnText">Guardar</span></button>

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