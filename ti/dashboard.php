<?php

$flagPermissions = 0;

session_start();
if (!isset($_SESSION['username'])) {
    header("refresh:3;url=index.php");
    die("Acesso Restrito");
}
if ($_SESSION['username'] == "admin") {
    $flagPermissions = 1;
} else if ($_SESSION['username'] == "supervisor") {
    $flagPermissions = 2;
} else if ($_SESSION['username'] == "user") {
    $flagPermissions = 3;
}


?>




<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <div class="container py-4">

        <!-- Cabeçalho -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 style="color: white;">Dashboard</h1>
            </div>

            <div class="col-md-4 text-end">
                <div class="card border-primary-color bg-linear-gradient std-flex">
                    <div class="std-flex">
                        <div class="card-body">
                            <strong>Utilizador:</strong> <?php echo $_SESSION["username"] ?>
                        </div>
                        <a href="logout.php" class="btn-sair std-flex">
                            Sair
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards dos sensores -->
        <div class="row g-4 justify-content-center">

            <!-- Temperatura -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-primary-color bg-linear-gradient">
                    <div class="card-header bg-primary-color text-white">Temperatura</div>

                    <div class="card-body text-center">
                        <div class="sensor-value" id="temperatura"></div>

                        <p class="mb-2">
                            Sistema de refrigeração:
                            <span class="badge bg-success" id="ACState"></span>
                        </p>

                        <p>
                            RGB Ambiente:
                            <span class="badge" id="rgbTemp"></span>
                        </p>

                        <p id="dataTemp"></p>
                    </div>
                </div>
            </div>

            <!-- Fotoresistor -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-primary-color bg-linear-gradient">
                    <div class="card-header bg-primary-color text-white">Fotoresistor</div>
                    <div class="card-body text-center">
                        <p class="mb-2">
                            Iluminação:
                            <span class="badge bg-success" id="lightState">
                                Ligada
                            </span>
                        </p>
                        <p>
                            Ambiente:
                            <span class="badge bg-dark" id="lightEnv">
                                Escuro
                            </span>
                        </p>
                        <p id="lightDate"></p>
                    </div>
                </div>
            </div>

            <!-- Botão Emergência -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 border-primary-color bg-linear-gradient">
                    <div class="card-header bg-primary-color text-white">Botão de Emergência</div>
                    <div class="card-body text-center">
                        <div class="sensor-value text-success" id="emergencyBtnState">
                            ON
                        </div>

                        <p>
                            Máquinas:
                            <span class="badge bg-success" id="machinesState">
                                Operando
                            </span>
                        </p>

                        <p>
                            Buzzer:
                            <span class="badge bg-secondary" id="buzzerState">
                                Desligado
                            </span>
                        </p>

                        <p id="emergencyBtnDate"></p>
                    </div>
                </div>
            </div>


        </div>

        <?php if ($flagPermissions == 1 || $flagPermissions == 3): ?>
            <!-- Controle Manual -->
            <div class="card mt-5 border-primary-color bg-linear-gradient">
                <div
                    class="card-header bg-primary-color text-white d-flex flex-row justify-content-between align-items-center">
                    <div>
                        Controle Manual dos Atuadores
                    </div>
                    <button type="button" class="btn btn-secondary" onClick="manageEnable()" id="enableBtn"></button>
                </div>

                <div class="card-body">

                    <div class="row g-3 justify-content-center" id="buttons">
                        <div class="col-md-3 d-grid">
                            <button class="btn btn-info btn-lg" onClick="toggleAirConditioner()">Alternar
                                Refrigeração</button>
                        </div>
                        <div class="col-md-3 d-grid">
                            <button class="btn btn-warning btn-lg" onClick="toggleLight()">Alternar Iluminação</button>
                        </div>
                        <div class="col-md-3 d-grid">
                            <button id="machinesBtn" class="btn btn-danger btn-lg" onClick="toggleMachines()">Parar
                                Máquinas</button>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>


        <?php if ($flagPermissions == 1 || $flagPermissions == 2): ?>
            <!-- Log de eventos -->
            <div class="card mt-4 border-primary-color bg-linear-gradient position-relative">
                <div class="card-header bg-primary-color text-white">Últimos Eventos</div>
                <div class="card-body position-relative" style="height: 250px; overflow: auto; padding:0px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Requisição</th>
                                <th>Dispositivo</th>
                                <th>Valor/Estado</th>
                                <th>Data/Hora</th>
                            </tr>
                        </thead>

                        <tbody id="logBody"></tbody>
                    </table>

                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
```