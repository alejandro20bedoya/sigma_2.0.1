let tablePerfil;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
    "DOMContentLoaded",
    function () {
        tablePerfil = $("#tablePerfil").DataTable({
            aProcessing: true,
            aServerSide: true,
            language: {
                url: "./es.json",
            },
            ajax: {
                url: " " + base_url + "/Perfil/getPerfiles",
                dataSrc: "",
            },

            columns: [
                // { data: "ideusuario" },
                { data: "identificacion" }, /// sonas de cambio
                { data: "nombres" },
                { data: "apellidos" },
                { data: "celular" },
                { data: "correo" },
                { data: "nombrerol" },
                { data: "status" },
                { data: "options" },
            ],

            dom: "lBfrtip",
            buttons: [
                // {
                //     text: "<i class='fas fa-file-excel'></i> Excel",
                //     titleAttr: "Exportar a Excel",
                //     className: "btn btn-success mt-3",
                //     action: function () {
                //         window.open(base_url + "/export/export_excel.php?tipo=usuarios", "_blank");
                //     },
                // },

                // {
                //     text: "<i class='fas fa-file-pdf'></i> PDF",
                //     titleAttr: "Exportar a PDF",
                //     className: "btn btn-danger mt-3",
                //     action: function () {
                //         window.open(base_url + "/export/export_pdf.php?tipo=usuarios", "_blank");
                //     },
                // },
            ],

            resonsieve: "true",
            bDestroy: true,
            iDisplayLength: 10,
            order: [[0, "desc"]],
        });

        if (document.querySelector("#formUsuarioperfil")) {

            let formUsuario = document.querySelector("#formUsuarioperfil");

            formUsuario.addEventListener("submit", function (e) {
                e.preventDefault();

                divLoading.style.display = "flex";

                let formData = new FormData(formUsuario);

                // FOTO (opcional)
                let inputFoto = document.querySelector("#fotoUsuario");

                // Asegurar ID
                let ideUsuario = document.querySelector("#ideUsuarioperfil").value.trim();
                formData.set("ideUsuarioperfil", ideUsuario);

                let request = new XMLHttpRequest();
                request.open("POST", base_url + "/Perfil/setPerfil", true);

                request.onreadystatechange = function () {
                    if (request.readyState === 4) {

                        divLoading.style.display = "none";

                        if (request.status === 200) {

                            let objData;
                            try {
                                objData = JSON.parse(request.responseText);
                            } catch (e) {
                                Swal.fire("Error", "Respuesta inválida del servidor", "error");
                                return;
                            }

                            if (objData.status) {

                                // Cerrar modal
                                $("#modalFormUsuarioperfil").modal("hide");

                                // Limpiar input file
                                if (inputFoto) inputFoto.value = "";

                                Swal.fire({
                                    icon: "success",
                                    title: "Éxito",
                                    text: objData.msg,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // 🔄 RECARGAR TODA LA PÁGINA (CTRL + R)
                                setTimeout(() => {
                                    location.reload(true);
                                }, 1500);

                            } else {
                                Swal.fire("Atención", objData.msg, "warning");
                            }

                        } else {
                            Swal.fire("Error", "Error en la petición", "error");
                        }
                    }
                };

                request.send(formData);
            });
        }

    },
    false
);

// vista de informacion
function fntViewInfo(ideusuario) {
    let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
    let ajaxUrl = base_url + "/Usuarios/getUsuario/" + ideusuario;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
                let estadoUsuario =
                    objData.data.status == 1
                        ? '<span class="badge text-bg-success">Activo</span>'
                        : '<span class="badge text-bg-danger">Inactivo</span>';

                // document.querySelector("#celIdeUsuario").innerHTML =
                //     objData.data.ideusuario;
                document.querySelector("#celIdentificacionUsuario").innerHTML =
                    objData.data.identificacion; ///  berificacion de base de datos
                document.querySelector("#celNombresUsuario").innerHTML =
                    objData.data.nombres;
                document.querySelector("#celApellidosUsuario").innerHTML =
                    objData.data.apellidos;
                document.querySelector("#celCelularUsuario").innerHTML =
                    objData.data.celular;
                document.querySelector("#celCorreoUsuario").innerHTML =
                    objData.data.correo;
                document.querySelector("#celRolUsuario").innerHTML = objData.data.rolid;
                document.querySelector("#celEstadoUsuario").innerHTML = estadoUsuario;
                // document.querySelector("#celNombrePrograma").innerHTML = objData.data.nombreprograma;

                $("#modalViewUsuario").modal("show");
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    };
}

// editar el usuario
function fntEditInfo(element, ideusuario) {
    rowTable = element.closest("tr");

    document.querySelector("#titleModal").innerHTML = "Actualizar Usuario";
    document
        .querySelector(".modal-header")
        .classList.replace("headerRegister", "headerUpdate");

    document
        .querySelector("#btnActionForm")
        .classList.replace("btn-success", "btn-info");

    document.querySelector("#btnText").innerHTML = "Actualizar";

    let request = new XMLHttpRequest();
    let ajaxUrl = base_url + "/Perfil/getUsuarioperfil/" + ideusuario;

    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let objData = JSON.parse(request.responseText);

            if (objData.status) {
                document.querySelector("#ideUsuarioperfil").value = objData.data.ideusuario;
                document.querySelector("#txtIdentificacionUsuario").value = objData.data.identificacion;
                document.querySelector("#txtNombresUsuario").value = objData.data.nombres;
                document.querySelector("#txtApellidosUsuario").value = objData.data.apellidos;
                document.querySelector("#txtCelularUsuario").value = objData.data.celular;
                document.querySelector("#txtCorreoUsuario").value = objData.data.correo;
            }

            // ✅ ABRIR MODAL (BOOTSTRAP 5)
            let modal = new bootstrap.Modal(
                document.getElementById("modalFormUsuarioperfil")
            );
            modal.show();
        }
    };
}


// eliminar
function fntDelInfo(ideusuario) {
    Swal.fire({
        title: "¿Eliminar Usuario?",
        text: "No podrás revertir esta acción",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6", // azul
        cancelButtonColor: "#d33", // rojo
        confirmButtonText: "Sí, eliminarlo",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (!result.isConfirmed) return;

        fetch(base_url + "/Usuarios/delUsuario", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "ideUsuario=" + ideusuario,
        })
            .then((res) => res.json())
            .then((objData) => {
                if (!objData.status) {
                    Swal.fire("¡Atención!", objData.msg, "error");
                    return;
                }

                Swal.fire("¡Eliminado!", objData.msg, "success");

                // 🔹 Guardar la página actual del DataTable
                let currentPage = tableUsuarios.page
                    ? tableUsuarios.page()
                    : tableUsuarios.api().page();

                // 🔹 Recargar datos sin perder la página
                $.getJSON(base_url + "/Usuarios/getUsuarios", function (data) {
                    if (tableUsuarios.clear) {
                        tableUsuarios.clear().rows.add(data).draw();
                        tableUsuarios.page(currentPage).draw(false);
                    } else {
                        tableUsuarios.api().clear().rows.add(data).draw();
                        tableUsuarios.api().page(currentPage).draw(false);
                    }
                });
            })
            .catch((err) => {
                console.error("Error:", err);
                Swal.fire("Error", "No se pudo eliminar el usuario", "error");
            });
    });
}

//modal
function openModalPerfil() {

    // limpiar id
    document.querySelector("#ideUsuarioperfil").value = "";

    // estilos del modal
    document.querySelector(".modal-header")
        .classList.replace("headerUpdate", "headerRegister");

    document.querySelector("#btnActionForm")
        .classList.replace("btn-info", "btn-success");

    document.querySelector("#btnText").innerHTML = "Guardar";
    document.querySelector("#titleModal").innerHTML = "Nuevo Perfil";

    // limpiar formulario
    document.querySelector("#formUsuarioperfil").reset();

    /* -------------------------------------------------
       OCULTAR CAMPOS DE DATOS Y SOLO MOSTRAR SUBIR FOTO
       ------------------------------------------------- */

    // Ocultar todos los inputs del usuario
    document.querySelectorAll(".user-fields").forEach(el => {
        el.style.display = "none";
    });

    // Mostrar solamente el input de la foto
    document.querySelector("#fotoUsuario").parentNode.style.display = "block";

    /* ------------------------------------------------- */

    // abrir modal
    let modal = new bootstrap.Modal(document.getElementById('modalFormUsuarioperfil'));
    modal.show();
}






