<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("refresh:3;url=index.php");
    die("Acesso Restrito");
}
?>




<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <div class="container py-4">
        <!-- Cabeçalho -->
        <div style="display:flex;flex-direction:row;">
            <div style="display:flex;flex-direction:row;">
                <div class="">
                    <h1 style="color: white;">Histórico</h1>
                </div>

                <a href="dashboard.php" class="linkHistorico">Dashboard</a>
            </div>

            <div style="display: flex;align-items: center;justify-content: right;width:100%;">
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
        <br>
        <div class="row g-3 justify-content-center" id="contentTables"></div>
    </div>
    <div style="width: 100%; margin: 50px 0px;" class="d-flex justify-content-center align-items-center">
        <div class="card border-primary-color bg-linear-gradient w-100" style="max-width: 800px; height: 500px;">
            <div class="card-header bg-primary-color text-white">Gráfico temperatura</div>
            <canvas id="tempChart" style="width: 100%;height: 100%;"></canvas>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="scriptHistory.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
```