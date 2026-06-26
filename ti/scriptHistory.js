const contentTables = document.getElementById("contentTables")
const ctx = document.getElementById('tempChart');

const urlGet = "http://iot.dei.estg.ipleiria.pt/ti/ti022/ti/api/api.php?nome="
const urlPost = "http://iot.dei.estg.ipleiria.pt/ti/ti022/ti/api/api.php"
// const urlGet = "http://localhost/ti/api/api.php?nome="
// const urlPost = "http://localhost/ti/api/api.php"

var lastValor = {
    analogTemp: null,
    airConditioner: null,
    envState: null,
    lights: null,
    machines: null,
    buzzer: null,
    button: null
};

var names = {
    analogTemp: "Temperatura",
    airConditioner: "Ar condicionado",
    envState: "Claridade",
    lights: "Iluminação",
    machines: "Máquinas",
    buzzer: "Buzzer",
    button: "Botão de emergência"
};

let chart;
var listTemperaturas = []
var listDatas = []



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
            return data;
        })
        .catch(err => console.error("Erro API:", err));
}

function getRequest(nome) {
    return fetch(urlGet + nome)
        .then(res => res.text())
        .then(data => {
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

function row(nome, info, hora) {
    return `                        
        <tr style="height: 50px;">
            <td>${manageValor(nome, info)}</td>
            <td>${hora}</td>
        </tr>
    `
}

function table(nome, tipo) {
    return `
            <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
                <div class="card border-primary-color bg-linear-gradient w-100" style="max-width: 400px; height: 300px;">
                    <div class="card-header bg-primary-color text-white">${nome}</div>
                    <div class="card-body p-2 overflow-auto">

                        <table>
                            <thead>
                                <tr>
                                    <th>${tipo === "Atuador" ? "Estado" : "Valor"}</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody id="${nome}Tabela"></tbody>
                        </table>

                    </div>
                </div>
            </div>
    `
}

async function loadHistory() {

    for (let sensor in names) {
        const tableId = names[sensor] + "Tabela";
        const table = document.getElementById(tableId);

        table.innerHTML = "";
    }

    const data = await getRequest("historico");

    const lines = data.split("\n").filter(l => l.trim() !== "");
    listTemperaturas = []
    listDatas = []

    for (let line of lines) {

        const [sensor, valor, hora] = line.split(";");

        const tableId = names[sensor] + "Tabela";
        const table = document.getElementById(tableId);

        if (sensor == "analogTemp") {
            listTemperaturas.push(valor)
            listDatas.push(hora.split(" ")[1]);
        }

        if (table) {
            table.innerHTML += row(sensor, valor, hora);
        }
    }


    chart.data.labels = listDatas;
    chart.data.datasets[0].data = listTemperaturas;
    chart.update();

}

function initChart() {
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Temperatura (°C)',
                data: [],
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    title: {
                        display: true,
                        text: '°C'
                    }
                }
            }
        }
    });
}

for (nam in names) {
    var name = names[nam]
    if (name == "Temperatura" || name == "Claridade") {
        contentTables.innerHTML += table(name, "Sensor")
    } else {
        contentTables.innerHTML += table(name, "Atuador")
    }
}


initChart();
loadHistory();
setInterval(() => {
    loadHistory();
}, 3000)










