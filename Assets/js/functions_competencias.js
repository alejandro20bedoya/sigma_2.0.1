let tableCompetencias;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
  "DOMContentLoaded",
  function () {
    tableCompetencias = $("#tableCompetencias").DataTable({
      aProcessing: true,
      aServerSide: true,
      language: {
        url: "./es.json",
      },
      ajax: {
        url: base_url + "/Competencias/getCompetencias",
        dataSrc: "",
      },
      columns: [
        { data: "codigocompetencia" },
        { data: "numeroficha" },
        { data: "tipocompetencia" },
        { data: "nombrecompetencia" },
        { data: "horascompetencia" },
        { data: "progreso" },
        { data: "codigoprograma" },
        { data: "options" },
      ],

      dom: "lBfrtip",
      
      buttons: [
        {
          text: "<i class='fas fa-file-excel'></i> Excel",
          titleAttr: "Exportar a Excel",
          className: "btn btn-success mt-3",
          action: function () {
            window.open(base_url + "/export/export_excel.php?tipo=competencias", "_blank");
          },
        },

        {
          text: "<i class='fas fa-file-pdf'></i> PDF",
          titleAttr: "Exportar a PDF",
          className: "btn btn-danger mt-3",
          action: function () {
            window.open(base_url + "/export/export_pdf.php?tipo=competencias", "_blank");
          },
        },
      ],


      responsive: "true",
      bDestroy: true,
      iDisplayLength: 10,
      order: [[0, "desc"]],
    });

    /// EVENTO PARA AGREGAR NUEVA COMPETENCIA
    if (document.querySelector("#formCompetencia")) {
      let formCompetencia = document.querySelector("#formCompetencia");
      formCompetencia.addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(formCompetencia);
        divLoading.style.display = "flex";

        let request = new XMLHttpRequest();
        request.open("POST", base_url + "/Competencias/setCompetencia", true);
        request.onreadystatechange = function () {
          if (request.readyState === 4) {
            divLoading.style.display = "none";
            if (request.status === 200) {
              let objData = JSON.parse(request.responseText);
              if (objData.status) {
                $("#modalFormCompetencia").modal("hide");
                formCompetencia.reset();
                tableCompetencias.ajax.reload(null, false);

                if (objData.msg === 1) {
                  Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Competencia guardada correctamente",
                    confirmButtonText: "Aceptar",
                  });
                } else if (objData.msg === 0) {
                  Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "Competencia actualizada correctamente",
                    confirmButtonText: "Aceptar",
                  });
                }
              } else {
                Swal.fire({
                  icon: "warning",
                  title: "Atención",
                  text: objData.msg,
                  confirmButtonText: "Aceptar",
                });
              }
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Error en la petición",
                confirmButtonText: "Aceptar",
              });
            }
          }
        };
        request.send(formData);
      });
    }
  },
  false
);

///vista de la informacion
function fntViewInfo(idecompetencia) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Competencias/getCompetencia/" + idecompetencia;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        // document.querySelector("#celIdeCompetencia").innerHTML = objData.data.idecompetencia;
        document.querySelector("#celCodigoCompetencia").innerHTML =
          objData.data.codigocompetencia;
        document.querySelector("#celTipoCompetencia").innerHTML =
          objData.data.tipocompetencia;
        document.querySelector("#celNombreCompetencia").innerHTML =
          objData.data.nombrecompetencia;
        document.querySelector("#celHorasCompetencia").innerHTML =
          objData.data.horascompetencia;
        document.querySelector("#celCodigoPrograma").innerHTML =
          objData.data.codigoprograma;
        document.querySelector("#celNombrePrograma").innerHTML =
          objData.data.nombreprograma;

        $("#modalViewCompetencia").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

// TODO SELECCIONAR PROGRAMAS
function fntProgramas() {
  if (document.querySelector("#ListadoProgramas")) {
    let ajaxUrl = base_url + "/Competencias/getSelectProgramas?op=combo";
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {
        document.querySelector("#ListadoProgramas").innerHTML =
          request.responseText;
        // $('#ListadoProgramas').html(data);
        formCompetencia.reset();
      }
    };
  }
}

// LIMPIAR MODAL
$("#modalFormCompetencia").on("hidden.bs.modal", function (e) {
  $(this).find("#formCompetencia")[0].reset();

  fntProgramas();
  fntProgramasEditar();
  // const formulario = document.getElementById('formCompetencia');
  // formulario.reset();
  // $('#ListadoProgramas').empty();
});

// funcion para editar
function fntEditInfo(element, idecompetencia) {
  rowTable = element.parentNode.parentNode.parentNode;
  document.querySelector("#titleModal").innerHTML = "Actualizar Competencia";
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnText").innerHTML = "Actualizar";
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Competencias/getCompetencia/" + idecompetencia;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        // fntProgramas();
        document.querySelector("#ideCompetencia").value =
          objData.data.idecompetencia;
        document.querySelector("#txtFicha").value = objData.data.numeroficha;
        document.querySelector("#txtCodigoCompetencia").value =
          objData.data.codigocompetencia;
        document.querySelector("#txtNombreCompetencia").value =
          objData.data.nombrecompetencia;
        document.querySelector("#txtHorasCompetencia").value =
          objData.data.horascompetencia;
        document.querySelector("#txtTipoCompetencia").value =
          objData.data.tipocompetencia;
        document.querySelector("#txtCodigoPrograma").value =
          objData.data.codigoprograma;
        document.querySelector("#txtNombrePrograma").value =
          objData.data.nombreprograma;
      }
    }
    $("#modalFormCompetencia").modal("show");
    // fntProgramasEditar();
  };
}

// funcion para eliminar
function fntDelInfo(idecompetencia) {
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

    fetch(base_url + "/Competencias/delCompetencia", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "ideCompetencia=" + idecompetencia,
    })
      .then((res) => res.json())
      .then((objData) => {
        if (!objData.status) {
          Swal.fire("¡Atención!", objData.msg, "error");
          return;
        }

        Swal.fire("¡Eliminado!", objData.msg, "success");

        // 🔹 Guardar página actual del DataTable
        let currentPage = tableCompetencias.page();

        // 🔹 Recargar los datos de la tabla sin perder la página
        $.getJSON(base_url + "/Competencias/getCompetencias", function (data) {
          tableCompetencias.clear().rows.add(data).draw();
          tableCompetencias.page(currentPage).draw(false);
        });
      })
      .catch((err) => {
        console.error("Error:", err);
        Swal.fire("Error", "No se pudo eliminar la competencia", "error");
      });
  });
}

// funcion para abrir modal
function openModal() {
  rowTable = "";
  document.querySelector("#ideCompetencia").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nueva Competencia";
  document.querySelector("#formCompetencia").reset();
  $("#modalFormCompetencia").modal("show");
  //TODO  PENDIDENTE DE HACER PRUEBAS
  fntProgramas();
  // fntProgramasEditar();
}

function fntViewInfoCodigoPrograma(codprograma) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Competencias/getPrograma/" + codprograma;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.getElementById("txtNombrePrograma").value =
          objData.data.nombreprograma;

        document.getElementById("txtTipoCompetencia").value =
          objData.data.nivelprograma;
        // document.getElementById('txtNombrePrograma').innerHTML = objData.data.nombreprograma;
      } else {
        document.getElementById("txtNombrePrograma").value = "";
        document.getElementById("txtTipoCompetencia").value = "";
      }
    }
  };
}
// function fntViewInfoTipoPrograma(codprograma) {
//   let request = window.XMLHttpRequest
//     ? new XMLHttpRequest()
//     : new ActiveXObject("Microsoft.XMLHTTP");
//   let ajaxUrl = base_url + "/Competencias/getTipo/" + codprograma;
//   request.open("GET", ajaxUrl, true);
//   request.send();
//   request.onreadystatechange = function () {
//     if (request.readyState == 4 && request.status == 200) {
//       let objData = JSON.parse(request.responseText);
//       if (objData.status) {
//         document.getElementById("txtNombrePrograma").value =
//           objData.data.nombreprograma;
//         // document.getElementById('txtNombrePrograma').innerHTML = objData.data.nombreprograma;
//       } else {
//         document.getElementById("txtNombrePrograma").value = "";
//       }
//     }
//   };
// }

// funcion para listar los programas
function fntProgramasEditar() {
  if (document.querySelector("#ListadoProgramas")) {
    let ajaxUrl = base_url + "/Competencias/getSelectProgramasEditar?op=combo";
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {
        // document.querySelector('#ListadoProgramas').innerHTML = request.responseText;
        document
          .querySelector("#ListadoProgramas")
          .append(request.responseText);
        // $('#ListadoProgramas').html(data);
        // formCompetencia.reset();
      }
    };
  }
}

// ver el nombre del prprograma
// function fntViewInfoCodigoPrograma(codprograma) {
//   let request = window.XMLHttpRequest
//     ? new XMLHttpRequest()
//     : new ActiveXObject("Microsoft.XMLHTTP");
//   let ajaxUrl = base_url + "/Fichas/getPrograma/" + codprograma;
//   request.open("GET", ajaxUrl, true);
//   request.send();
//   request.onreadystatechange = function () {
//     if (request.readyState == 4 && request.status == 200) {
//       let objData = JSON.parse(request.responseText);
//       if (objData.status) {
//         document.getElementById("txtCodigoPrograma").value =
//           objData.data.nombreprograma;
//       } else {
//         document.getElementById("txtCodigoPrograma").value = "";
//       }
//     }
//   };
// }
