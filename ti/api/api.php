<?php
//var_dump($_POST);
header('Content-Type: text/html; charset=utf-8');

$pastaFiles = __DIR__ . '/files/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nome']) || !isset($_POST['valor']) || !isset($_POST['hora']) || !isset($_POST['dashboard'])) {
        http_response_code(400);
        echo 'Parâmetros "nome", "valor", "hora" e "dashboard" são obrigatórios';
        exit;
    }


    $nomeSensor = $_POST['nome'];
    $hora = $_POST['hora'];
    $valor = $_POST['valor'];
    $dashboard = $_POST['dashboard'];

    if (isset($_POST['priority'])) {
        file_put_contents($pastaFiles . '/priorityMode.txt', $_POST['priority']);
        if ($_POST['priority'] == 0) {
            echo "estamos atualizando";
            updateData();
        }
        http_response_code(200);
        echo "Modo atualizado";
        exit;
    }

    $priority = file_get_contents($pastaFiles . '/priorityMode.txt');

    if ($dashboard == 1 && $priority == 1) {
        if ($nomeSensor === "airConditioner") {
            changeState("airConditioner", $valor, $hora);
        }
        if ($nomeSensor === "lights") {
            changeState("lights", $valor, $hora);
        }
        if ($nomeSensor === "machines") {
            changeState("machines", $valor, $hora);
        }
        if ($nomeSensor === "buzzer") {
            changeState("buzzer", $valor, $hora);
        }
    } else if ($dashboard == 0 && $priority == 1) {
        http_response_code(200);
        echo 'Priority mode enabled request ruled out';
        exit;
    } else if ($dashboard == 0 && $priority == 0) {
        if ($nomeSensor === "analogTemp") {
            $state = 0;
            if ($valor > 25) {
                $state = 1;
            }
            changeState("airConditioner", $state, $hora);
        } else if ($nomeSensor === "fotoResistor") {
            $state = 0;
            if ($valor > 700) {
                $state = 1;
            }
            changeState("lights", $state, $hora);
            changeState("envState", $state, $hora);
        } else if ($nomeSensor === "button") {
            $stateMachines = 1;
            $stateBuzzer = 0;

            if ($valor == 1) {
                $stateMachines = 0;
                $stateBuzzer = 1;
            }

            changeState("machines", $stateMachines, $hora);
            changeState("buzzer", $stateBuzzer, $hora);
        }
    }

    $pastaSensor = $pastaFiles . $nomeSensor;

    $ficheiroValor = $pastaSensor . '/valor.txt';
    $ficheiroNome = $pastaSensor . '/nome.txt';
    $ficheiroHora = $pastaSensor . '/hora.txt';

    if (!file_exists($pastaSensor)) {
        http_response_code(400);
        echo 'Sensor desconhecido';
        exit;
    }

    file_put_contents($ficheiroValor, $valor);
    file_put_contents($ficheiroNome, $nomeSensor);
    file_put_contents($ficheiroHora, $hora);
    addHistory($nomeSensor, $valor, $hora);

    http_response_code(200);
    echo 'Operação bem sucedida';



} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['nome'])) {
        $nomeSensor = $_GET['nome'];

        if ($nomeSensor == "priority") {
            // echo 'Link : ' . $pastaFiles . $nomeSensor;
            $valor = file_get_contents($pastaFiles . 'priorityMode.txt');
            echo $valor;
            http_response_code(200);
            exit;
        }

        if ($nomeSensor == "historico") {
            // echo 'Link : ' . $pastaFiles . $nomeSensor;
            $valor = file_get_contents($pastaFiles . 'historico.txt');
            echo $valor;
            http_response_code(200);
            exit;
        }

        $pastaSensor = $pastaFiles . $nomeSensor;

        if (!file_exists($pastaSensor)) {
            http_response_code(400);
            echo 'Sensor desconhecido';
            exit;
        }

        $ficheiroValor = $pastaSensor . '/valor.txt';
        $ficheiroData = $pastaSensor . '/hora.txt';

        $valor = file_get_contents($ficheiroValor);
        $data = file_get_contents($ficheiroData);

        echo $valor . ";" . $data;

    } else {
        http_response_code(400);
        echo 'Parâmetro "nome" é obrigatório';
    }
} else {
    http_response_code(405);
    echo 'Método não permitido';
}


function changeState($atuador, $state, $hora)
{
    $pastaFiles = __DIR__ . '/files/';
    file_put_contents($pastaFiles . $atuador . '/valor.txt', $state);
    file_put_contents($pastaFiles . $atuador . '/hora.txt', $hora);

    addHistory($atuador, $state, $hora);
}

function updateData()
{
    $pastaFiles = __DIR__ . '/files/';

    $temp = (float) file_get_contents($pastaFiles . "analogTemp/valor.txt");
    $light = (float) file_get_contents($pastaFiles . "fotoResistor/valor.txt");
    $button = (int) file_get_contents($pastaFiles . "button/valor.txt");

    $horaTemp = file_get_contents($pastaFiles . "analogTemp/hora.txt");
    $horaLight = file_get_contents($pastaFiles . "fotoResistor/hora.txt");
    $horaButton = file_get_contents($pastaFiles . "button/hora.txt");

    $acState = ($temp > 25) ? 1 : 0;
    changeState("airConditioner", $acState, $horaTemp);

    $lightState = ($light > 700) ? 1 : 0;
    changeState("lights", $lightState, $horaLight);
    changeState("envState", $lightState, $horaLight);

    changeState("button", 0, $horaButton);
    changeState("machines", 1, $horaButton);
    changeState("buzzer", 0, $horaButton);


}

function addHistory($sensor, $valor, $hora)
{
    $pastaFiles = __DIR__ . '/files/';
    $linha = $sensor . ";" . $valor . ";" . $hora . "\n";

    file_put_contents($pastaFiles . "historico.txt", $linha, FILE_APPEND);
}

?>