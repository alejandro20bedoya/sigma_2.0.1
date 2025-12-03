let tableProgramas;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
  "DOMContentLoaded",
  function () {
    tableProgramas = $("#tableProgramas").DataTable({
      aProcessing: true,
      aServerSide: true,
      language: {
        url: "./es.json",
      },
      ajax: {
        url: base_url + "/Programas/getProgramas",
        dataSrc: "",
      },

      columns: [
        { data: "codigoprograma" },
        { data: "nivelprograma" },
        { data: "nombreprograma" },
        { data: "horasprograma" },
        { data: "status_ficha" },
        { data: "options" },
      ],

      dom: "lBfrtip",

      buttons: [
        {
          text: "<i class='fas fa-file-excel'></i> Excel",
          titleAttr: "Exportar a Excel",
          className: "btn btn-success mt-3",
          action: function () {
            window.open(base_url + "/export/export_excel.php?tipo=programas", "_blank");
          },
        },

        {
          text: "<i class='fas fa-file-pdf'></i> PDF",
          titleAttr: "Exportar a PDF",
          className: "btn btn-danger mt-3",
          action: function () {
            window.open(base_url + "/export/export_pdf.php?tipo=programas", "_blank");
          },
        },
      ],

      responsive: "true",
      bDestroy: true,
      iDisplayLength: 10,
      order: [[0, "desc"]],
    });

    if (document.querySelector("#formPrograma")) {
      let formPrograma = document.querySelector("#formPrograma");
      formPrograma.onsubmit = function (e) {
        e.preventDefault();

        // Mostrar el loader si existe
        if (typeof divLoading !== "undefined")
          divLoading.style.display = "flex";

        // Crear petición AJAX
        let request = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");
        let ajaxUrl = base_url + "/Programas/setPrograma";
        let formData = new FormData(formPrograma);

        request.open("POST", ajaxUrl, true);
        request.send(formData);

        request.onreadystatechange = function () {
          if (request.readyState != 4) return;

          // Ocultar loader
          if (typeof divLoading !== "undefined")
            divLoading.style.display = "none";

          if (request.status == 200) {
            let objData = JSON.parse(request.responseText);

            if (objData.status) {
              // Recargar DataTable (si existe)
              try {
                if (
                  tableProgramas &&
                  typeof tableProgramas.api === "function"
                ) {
                  tableProgramas.api().ajax.reload(null, false);
                } else if (tableProgramas && tableProgramas.ajax) {
                  tableProgramas.ajax.reload(null, false);
                }
              } catch (error) {
                console.warn("No se pudo recargar la tabla:", error);
              }

              // Cerrar modal
              $("#modalFormPrograma").modal("hide");

              // Limpiar formulario
              formPrograma.reset();

              // Mostrar alerta y recargar la página
              swal("Programas", objData.msg, "success");
              setTimeout(() => location.reload(), 800);
            } else {
              swal("Error", objData.msg, "error");
            }
          } else {
            swal("Error", "No se pudo conectar con el servidor.", "error");
          }

          return false;
        };
      };
    }
  },
  false
);

function fntViewInfo(ideprograma) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Programas/getPrograma/" + ideprograma;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#celIdePrograma").innerHTML =
          objData.data.ideprograma;
        document.querySelector("#celCodigoPrograma").innerHTML =
          objData.data.codigoprograma;
        document.querySelector("#celNivelPrograma").innerHTML =
          objData.data.nivelprograma;
        document.querySelector("#celNombrePrograma").innerHTML =
          objData.data.nombreprograma;
        document.querySelector("#celHorasPrograma").innerHTML =
          objData.data.horasprograma;

        $("#modalViewPrograma").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

function fntEditInfo(element, ideprograma) {
  rowTable = element.parentNode.parentNode.parentNode;
  document.querySelector("#titleModal").innerHTML = "Actualizar Programa";
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
  let ajaxUrl = base_url + "/Programas/getPrograma/" + ideprograma;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#idePrograma").value = objData.data.ideprograma;
        document.querySelector("#txtCodigoPrograma").value =
          objData.data.codigoprograma;
        document.querySelector("#txtNivelPrograma").value =
          objData.data.nivelprograma;
        document.querySelector("#txtNombrePrograma").value =
          objData.data.nombreprograma;
        document.querySelector("#txtHorasPrograma").value =
          objData.data.horasprograma;
      }
    }
    $("#modalFormPrograma").modal("show");
  };
}

// ELIMINAR
function fntDelInfo(ideprograma) {
  Swal.fire({
    title: "¿Eliminar Programa?",
    text: "No podrás revertir esta acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6", // azul
    cancelButtonColor: "#d33", // rojo
    confirmButtonText: "Sí, eliminarlo",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch(base_url + "/Programas/delPrograma", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "idePrograma=" + ideprograma,
    })
      .then((res) => res.json())
      .then((objData) => {
        if (!objData.status) {
          Swal.fire("¡Atención!", objData.msg, "error");
          return;
        }

        Swal.fire("¡Eliminado!", objData.msg, "success");

        // 🔹 Guardar la página actual del DataTable
        let currentPage = tableProgramas.page
          ? tableProgramas.page()
          : tableProgramas.api().page();

        // 🔹 Recargar datos sin perder la página
        $.getJSON(base_url + "/Programas/getProgramas", function (data) {
          if (tableProgramas.clear) {
            tableProgramas.clear().rows.add(data).draw();
            tableProgramas.page(currentPage).draw(false);
          } else {
            tableProgramas.api().clear().rows.add(data).draw();
            tableProgramas.api().page(currentPage).draw(false);
          }
        });
      })
      .catch((err) => {
        console.error("Error:", err);
        Swal.fire("Error", "No se pudo eliminar el programa", "error");
      });
  });
}

function openModal() {
  rowTable = "";
  document.querySelector("#idePrograma").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nuevo Programa";
  document.querySelector("#formPrograma").reset();
  $("#modalFormPrograma").modal("show");
}
