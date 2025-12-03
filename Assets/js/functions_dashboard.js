document.getElementById("selectPrograma").addEventListener("change", function () {
    let filtro = this.value; // progreso | completado | todos
    let items = document.querySelectorAll("#competenciasContainer .competencia-box");

    items.forEach(item => {
        let estado = item.querySelector(".estado").textContent.trim().toLowerCase();

        if (filtro === "todos") {
            item.style.display = "flex";
        } else if (filtro === "progreso" && estado === "en progreso") {
            item.style.display = "flex";
        } else if (filtro === "completado" && estado === "completado") {
            item.style.display = "flex";
        } else {
            item.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const percent = document.querySelector(".percent");
    const progressCircle = percent.querySelector(".progress");
    const progressText = document.getElementById("progressValue");

    const value = parseInt(percent.getAttribute("data-num")) || 0;
    const circumference = 440;
    const targetOffset = circumference - (circumference * value) / 100;

    let current = 0;
    const duration = 2000; // 2 segundos
    const interval = 20; // velocidad de incremento
    const step = value / (duration / interval);

    const timer = setInterval(() => {
        current += step;
        if (current >= value) {
            current = value;
            clearInterval(timer);
        }
        progressCircle.style.strokeDashoffset = circumference - (circumference * current) / 100;
        progressText.innerHTML = Math.round(current) + "<span>%</span>";
    }, interval);
});

document.getElementById("selectFicha").addEventListener("change", function () {
    const ficha = this.value;

    fetch(base_url + "/Dashboard/getProgresoAsignacion/" + ficha)
        .then(response => response.json())
        .then(data => {
            const nuevoProgreso = data.progreso || 0;
            actualizarProgreso(nuevoProgreso);
        })
        .catch(error => console.error("Error al consultar progreso:", error));
});

function actualizarProgreso(value) {
    const percent = document.querySelector(".percent");
    const progressCircle = percent.querySelector(".progress");
    const progressText = document.getElementById("progressValue");

    const circumference = 440;
    const targetOffset = circumference - (circumference * value) / 100;

    let current = 0;
    const duration = 2000; // 2 segundos
    const interval = 20;
    const step = value / (duration / interval);

    clearInterval(window.timerProgreso);
    window.timerProgreso = setInterval(() => {
        current += step;
        if (current >= value) {
            current = value;
            clearInterval(window.timerProgreso);
        }
        progressCircle.style.strokeDashoffset = circumference - (circumference * current) / 100;
        progressText.innerHTML = Math.round(current) + "<span>%</span>";
    }, interval);
}

document.getElementById("buscarCompetencia").addEventListener("keyup", function () {
    let filtro = this.value.toLowerCase();
    let items = document.querySelectorAll(".competencia-box");

    items.forEach(item => {
        let texto = item.innerText.toLowerCase();
        item.style.display = texto.includes(filtro) ? "" : "none";
    });
});

