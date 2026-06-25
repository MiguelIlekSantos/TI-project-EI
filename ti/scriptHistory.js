const tempDiv = document.getElementById("temperatura")
const ACStateDiv = document.getElementById("ACState")
const rgbTempDiv = document.getElementById("rgbTemp")
const dataTempDiv = document.getElementById("dataTemp")
const emergencyBtnState = document.getElementById("emergencyBtnState")
const machinesState = document.getElementById("machinesState")
const buzzerState = document.getElementById("buzzerState")
const emergencyBtnDate = document.getElementById("emergencyBtnDate")
const machinesBtn = document.getElementById("machinesBtn")
const lightStateDiv = document.getElementById("lightState")
const lightEnvDiv = document.getElementById("lightEnv")
const lightDateDiv = document.getElementById("lightDate")
const enableBtn = document.getElementById("enableBtn")
const buttons = document.getElementById("buttons")
const logBody = document.getElementById("logBody")

var buttonsFlag = 0;

// const urlGet = "http://iot.dei.estg.ipleiria.pt/ti/ti022/ti/api/api.php?nome="
// const urlPost = "http://iot.dei.estg.ipleiria.pt/ti/ti022/ti/api/api.php"
const urlGet = "http://localhost/ti/api/api.php?nome="
const urlPost = "http://localhost/ti/api/api.php"

function updateLog(reqType, nome, valor, hora) {
    const row = `
        <tr>
            <td>${reqType}</td>
            <td>${nome}</td>
            <td>${manageValor(nome, valor)}</td>
            <td>${hora}</td>
        </tr>
    `;

    logBody.insertAdjacentHTML("afterbegin", row);
}

function manageValor(nome, valor) {

    const ativaveis = [
        "airConditioner",
        "lights",
        "machines",
        "buzzer",
        "button"
    ];

    if (nome === "envState") {
        return valor == 1 ? "Escuro" : "Claro";
    }

    if (ativaveis.includes(nome)) {
        return valor == 1 ? "Ativado" : "Desativado";
    }

    return valor;
}

function postRequest(nome, valor, hora) {
    return fetch(urlPost, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            nome: nome,
            valor: valor,
            hora: hora,
            dashboard: 1
        })
    })
        .then(res => res.text())
        .then(data => {
            updateLog("POST", nome, valor, hora)
            return data;
        })
        .catch(err => console.error("Erro API:", err));
}

function getRequest(nome) {
    return fetch(urlGet + nome)
        .then(res => res.text())
        .then(data => {
            if (nome != "priority") {
                var valor = data.split(";")[0]
                var hora = data.split(";")[1]
                updateLog("GET", nome, valor, hora)
            }
            return data;
        })
        .catch(err => {
            console.error("Error:", err);
        });
}

function timeFormat() {
    const now = new Date();

    const hora =
        now.getFullYear() + "-" +
        String(now.getMonth() + 1).padStart(2, "0") + "-" +
        String(now.getDate()).padStart(2, "0") + " " +
        String(now.getHours()).padStart(2, "0") + ":" +
        String(now.getMinutes()).padStart(2, "0");

    return hora
}

async function refreshInfo() {

    // refresh temp

    var values = (await getRequest("analogTemp")).split(";")

    var temp = parseFloat(values[0])
    var dataTemp = values[1]

    tempDiv.innerHTML = temp + "ºC"

    if ((await getRequest("airConditioner")).split(";")[0] == 1) {
        ACStateDiv.innerHTML = "Ligado";
        ACStateDiv.classList.remove("bg-secondary", "bg-danger");
        ACStateDiv.classList.add("bg-success");
    } else {
        ACStateDiv.innerHTML = "Desligado";
        ACStateDiv.classList.remove("bg-success");
        ACStateDiv.classList.add("bg-secondary");
    }

    if (temp > 28) {
        rgbTempDiv.innerHTML = "Quente";
        rgbTempDiv.classList.add("bg-danger", "text-white");
    } else if (temp > 25) {
        rgbTempDiv.innerHTML = "Moderado";
        rgbTempDiv.classList.add("bg-warning", "text-dark");
    } else {
        rgbTempDiv.innerHTML = "Fresco";
        rgbTempDiv.classList.add("bg-info", "text-dark");
    }

    dataTempDiv.innerHTML = "Atualizado :" + dataTemp


    // refresh light


    var envState = (await getRequest("envState")).split(";")
    var lightState = (await getRequest("lights")).split(";")

    var lightDate = lightState[1]

    if (lightState[0] == 1) {
        lightStateDiv.innerHTML = "Ligada"
        lightStateDiv.classList.remove("bg-secondary")
        lightStateDiv.classList.add("bg-success")
    } else {
        lightStateDiv.innerHTML = "Desligada"
        lightStateDiv.classList.remove("bg-success")
        lightStateDiv.classList.add("bg-secondary")
    }

    if (envState[0] == 1) {
        lightEnvDiv.innerHTML = "Escuro"
        lightEnvDiv.classList.remove("bg-warning")
        lightEnvDiv.classList.add("bg-dark")
    } else {
        lightEnvDiv.innerHTML = "Claro"
        lightEnvDiv.classList.remove("bg-dark")
        lightEnvDiv.classList.add("bg-warning")
    }



    lightDateDiv.innerHTML = "Atualizado: " + lightDate


    // refresh btn


    var emgValues = (await getRequest("button")).split(";")

    var state = parseInt(emgValues[0])
    var emgDate = emgValues[1]

    if (state === 1) {
        emergencyBtnState.innerHTML = "ON"
    } else {
        emergencyBtnState.innerHTML = "OFF"
    }

    if (((await getRequest("machines")).split(";")[0] == 1)) {
        machinesState.innerHTML = "Operando"
        machinesState.classList.remove("bg-danger")
        machinesState.classList.add("bg-success")
    } else {
        machinesState.innerHTML = "Parado"
        machinesState.classList.remove("bg-success")
        machinesState.classList.add("bg-danger")
    }

    if (((await getRequest("buzzer")).split(";")[0] == 1)) {
        buzzerState.innerHTML = "Ligado"
        buzzerState.classList.remove("bg-secondary")
        buzzerState.classList.add("bg-danger")
    } else {
        buzzerState.innerHTML = "Desligado"
        buzzerState.classList.remove("bg-danger")
        buzzerState.classList.add("bg-secondary")
    }


    emergencyBtnDate.innerHTML = "Atualizado: " + emgDate

    loadPriorityState()
}


refreshInfo()

// setInterval(() => {
// }, 1000)










