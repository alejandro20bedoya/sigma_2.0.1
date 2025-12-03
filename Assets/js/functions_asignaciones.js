let tableAsignaciones;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener(
  "DOMContentLoaded",
  function () {
    tableAsignaciones = $("#tableAsignaciones").dataTable({
      aProcessing: true,
      aServerSide: true,
      language: {
        url: "./es.json",
      },
      ajax: {
        url: " " + base_url + "/Asignaciones/getFichas",
        dataSrc: "",
      },

      columns: [
        { data: "programaficha" },
        { data: "nombres" },
        { data: "idecompetencia" },
        { data: "nombrecompetencia" },
        { data: "horasrealizadas" }, // 👈 corregido
        { data: "totalhoras" },
        { data: "progreso" }, // 👈 NUEVA COLUMNA con la barra de progreso
        { data: "mes" },
        { data: "options" },
      ],

      dom: "lBfrtip",

      buttons: [
        {
          text: "<i class='fas fa-file-excel'></i> Excel",
          titleAttr: "Exportar a Excel",
          className: "btn btn-success mt-3",
          action: function () {
            window.open(base_url + "/export/export_excel.php?tipo=asignaciones", "_blank");
          },
        },

        {
          text: "<i class='fas fa-file-pdf'></i> PDF",
          titleAttr: "Exportar a PDF",
          className: "btn btn-danger mt-3",
          action: function () {
            window.open(base_url + "/export/export_pdf.php", "_blank");
          },
        },
      ],




      responsive: "true",
      bDestroy: true,
      iDisplayLength: 10,
      order: [[0, "desc"]],
    });

    if (document.querySelector("#formAsignacion")) {
      let formAsignacion = document.querySelector("#formAsignacion");
      formAsignacion.onsubmit = function (e) {
        e.preventDefault();
        let formData = new FormData(formAsignacion);

        divLoading.style.display = "flex";
        let request = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");
        let ajaxUrl = base_url + "/Asignaciones/setAsignaciones";
        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function () {
          if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
              if (rowTable == "") {
                tableAsignaciones.api().ajax.reload();
              } else {
                tableAsignaciones.api().ajax.reload();
                rowTable = "";
              }
              $("#modalFormAsignacion").modal("hide");
              formAsignacion.reset();
              swal("Asignacion", objData.msg, "success");
            } else {
              swal("Error", objData.msg, "error");
            }
          }
          divLoading.style.display = "none";
          return false;
        };
      };
    }
  },
  false
);

// vista del modal de informacion
function fntViewInfo(idedetalleficha) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Asignaciones/getFicha/" + idedetalleficha;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#celFicha").innerHTML =
          objData.data.programaficha;
        document.querySelector("#celNombreFicha").innerHTML =
          objData.data.nombreprograma;
        document.querySelector("#celInstructor").innerHTML =
          objData.data.nombres;
        document.querySelector("#celCompetencia").innerHTML =
          objData.data.nombrecompetencia;
        document.querySelector("#celNCompetencia").innerHTML =
          objData.data.idecompetencia;
        document.querySelector("#celHoras").innerHTML =
          objData.data.horascompetencia;
        document.querySelector("#celHorasfaltantes").innerHTML =
          objData.data.totalhoras;
        document.querySelector("#celMes").innerHTML = objData.data.mes;

        $("#modalViewAsignacion").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

//editar informacion
function fntEditInfo(element, idedetalleficha) {
  rowTable = element.parentNode.parentNode.parentNode;
  document.querySelector("#titleModal").innerHTML = "Asignar Competencias";
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnText").innerHTML = "Asignar";
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Asignaciones/getFicha/" + idedetalleficha;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector("#ideDetalleFicha").value =
          objData.data.idedetalleficha;
        document.querySelector("#txtNumeroFicha").value =
          objData.data.programaficha;
        document.querySelector("#txtNombreFicha").value =
          objData.data.nombreprograma;
        document.querySelector("#txtIdeInstructor").value =
          objData.data.identificacion;
        document.querySelector("#txtNombreInstructor").value =
          objData.data.nombres;
        document.querySelector("#txtCodigoCompetencia").value =
          objData.data.idecompetencia;
        document.querySelector("#txtNombreCompetencia").value =
          objData.data.nombrecompetencia;
        document.querySelector("#txtHorasTotalCompetencia").value =
          objData.data.totalhoras;
        document.querySelector("#txtHorasSumaAsignacionCompetencia").value = "";

        document.querySelector("#txtHorasPendienteCompetencia").value = "";

        document.querySelector("#txtNumeroHoras").value = "";
        document.querySelector("#listadoMeses").value = objData.data.mes;

        // TODO PRUEBA
        var valor1 = parseFloat(
          (document.getElementById("txtHorasTotalCompetencia").value =
            objData.data.totalhoras)
        );

        var valor2 = parseFloat(
          (document.getElementById("txtHorasSumaAsignacionCompetencia").value =
            "")
        );

        // Realizar la resta
        var resultado = valor1 - valor2;

        // Mostrar el resultado en el campo de salida
        document.getElementById("txtHorasPendienteCompetencia").value =
          resultado;
      }
    }
    $("#modalFormAsignacion").modal("show");
    // ftnSumarCantidadHoras();
  };
}

// eliminar informacion
function fntDelInfo(idedetalleficha) {
  // Asignar el valor al input hidden (opcional)
  document.getElementById("ideDetalleFicha").value = idedetalleficha;

  Swal.fire({
    title: "Eliminar Ficha",
    text: "¿Está seguro que desea eliminar la ficha?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
    reverseButtons: true,
  }).then((result) => {
    if (!result.isConfirmed) return;

    divLoading.style.display = "flex"; // Mostrar loading

    // Obtener el valor del input hidden
    let idFicha = document.getElementById("ideDetalleFicha").value;

    fetch(base_url + "/Asignaciones/delFicha", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "idedetalleficha=" + idFicha,
    })
      .then((res) => res.json())
      .then((objData) => {
        if (!objData.status) {
          Swal.fire("¡Atención!", objData.msg, "error");
          return;
        }

        Swal.fire("¡Eliminado!", objData.msg, "success").then(() => {
          // 🔹 Recargar solo la tabla correcta
          tableAsignaciones.api().ajax.reload(null, false); // false mantiene la página actual
        });
      })
      .catch((err) => console.error("Error:", err))
      .finally(() => {
        divLoading.style.display = "none"; // Ocultar loading
      });
  });
}

// modal
function openModal() {
  rowTable = "";
  document.querySelector("#ideDetalleFicha").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nueva Ficha";
  document.querySelector("#formAsignacion").reset();
  $("#modalFormAsignacion").modal("show");
}

// buscar el nombre del programa por el numero de ficha
function fntViewInfoIdeFicha(fichaprograma) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Asignaciones/getIdeFicha/" + fichaprograma;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.getElementById("txtNombreFicha").value =
          objData.data.nombreprograma;
      } else {
        document.getElementById("txtNombreFicha").value = "";
      }
    }
  };
}

// buscar el nombre del instructor
function fntViewInfoIdeInstructor(identificacion) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Asignaciones/getInstructor/" + identificacion;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.getElementById("txtNombreInstructor").value =
          objData.data.nombres;
      } else {
        document.getElementById("txtNombreInstructor").value = "";
      }
    }
  };
}

// buscar el nombre de la competencia
function fntViewInfoCodigoCompetencia(codigocompetencia) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Asignaciones/getCompetencia/" + codigocompetencia;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.getElementById("txtNombreCompetencia").value =
          objData.data.nombrecompetencia;

        var valor1 = parseFloat(
          (document.getElementById("txtHorasTotalCompetencia").value =
            objData.data.horascompetencia)
        );
        var valor2 = parseFloat(
          (document.getElementById("txtHorasSumaAsignacionCompetencia").value =
            objData.data.horasrealizadas)
        );

        // Realizar la resta
        var resultado = valor1 - valor2;

        // Mostrar el resultado en el campo de salida
        document.getElementById("txtHorasPendienteCompetencia").value =
          resultado;
      } else {
        document.getElementById("txtNombreCompetencia").value = "";
        document.getElementById("txtHorasTotalCompetencia").value = "";
        document.getElementById("txtHorasPendienteCompetencia").value = "";
      }
    }
  };
}

// SUMAR CANTIDAD DE HORAS
function ftnSumarCantidadHoras() {
  // Obtener los valores de los inputs
  var horasPendientes =
    parseFloat(document.getElementById("txtHorasTotalCompetencia").value) || 0;
  var horasAsignar =
    parseFloat(document.getElementById("txtNumeroHoras").value) || 0;
  var horasAsignadasInput = document.getElementById(
    "txtHorasSumaAsignacionCompetencia"
  );
  var mensajeError = document.getElementById("mensajeError");

  // Limpiar mensaje previo
  mensajeError.textContent = "";

  // Validar si las horas a asignar superan las pendientes
  if (horasAsignar > horasPendientes) {
    mensajeError.textContent =
      "⚠️ Importante: El número de horas asignadas es mayor que las horas pendientes.";
    document.getElementById("txtNumeroHoras").value = "";
    horasAsignadasInput.value = ""; // limpiar también
    return false;
  }

  // ✅ Reflejar las mismas horas en el campo de "HORAS total ASIGNADAS"
  horasAsignadasInput.value = horasAsignar;

  // ✅ Calcular las nuevas horas pendientes (restar)
  var nuevasHorasPendientes = horasPendientes - horasAsignar;
  document.getElementById("txtHorasPendienteCompetencia").value =
    nuevasHorasPendientes;

  return true; // Validación exitosa
}

// VERIFICAR HORAS PENDIENTES
document.addEventListener("DOMContentLoaded", function () {
  // Seleccionar el input por su ID
  var inputField = document.getElementById("txtHorasPendienteCompetencia");
  var mensaje = document.getElementById("mensaje");
  var valorAnterior = ""; // Almacena el valor anterior del input

  // Usar setInterval para verificar cambios en el valor del input cada 500ms
  setInterval(function () {
    var valorActual = inputField.value.trim();

    // Comprobar si el valor ha cambiado
    if (valorActual !== valorAnterior) {
      valorAnterior = valorActual; // Actualizar el valor anterior
      if (valorActual == 0) {
        mensajeError.textContent =
          "El número de horas de la competencia ya se encuentra completo";
        document.getElementById("txtNumeroHoras").disabled = true;
        // document.getElementById("txtNumeroHoras").hidden = true;
        // mensaje.textContent = "El input tiene un valor: " + valorActual;
      } else {
        mensaje.textContent = "Horas pendientes de la competencia";
        document.getElementById("txtNumeroHoras").disabled = false;
        mensajeError.textContent = "";
      }
    }
  }, 500); // Revisa cada 500ms (medio segundo)
});

// RESTAR CANTIDAD DE HORAS
function realizarResta() {
  // Obtener los valores de los campos de entrada
  var valor111 = parseFloat(
    document.getElementById("txtHorasTotalCompetencia").value
  );
  var valor211 = parseFloat(document.getElementById("valor2").value);

  // Realizar la resta
  var resultado = valor1 - valor2;

  // Mostrar el resultado en el campo de salida
  document.getElementById("resultado").value = resultado;

  // Opcionalmente, enviar los valores al servidor con AJAX
  enviarDatos(valor1, valor2, resultado);
}

function enviarDatos(valor1, valor2, resultado) {
  // Crear una solicitud AJAX
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "procesar_resta.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      alert("Datos enviados al servidor");
    }
  };
  xhr.send(
    "valor1=" + valor1 + "&valor2=" + valor2 + "&resultado=" + resultado
  );
}
