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

                    <tbody id="logBody">
                        <tr>
                            <td>POST</td>
                            <td>analogTemp</td>
                            <td>27.5 ºC</td>
                            <td>2025-06-25 14:30</td>
                        </tr>

                        <tr>
                            <td>GET</td>
                            <td>airConditioner</td>
                            <td>Ativado</td>
                            <td>2025-06-25 14:31</td>
                        </tr>

                        <tr>
                            <td>POST</td>
                            <td>lights</td>
                            <td>Desativado</td>
                            <td>2025-06-25 14:32</td>
                        </tr>

                        <tr>
                            <td>GET</td>
                            <td>envState</td>
                            <td>Escuro</td>
                            <td>2025-06-25 14:33</td>
                        </tr>

                        <tr>
                            <td>POST</td>
                            <td>button</td>
                            <td>Ativado</td>
                            <td>2025-06-25 14:34</td>
                        </tr>

                        <tr>
                            <td>GET</td>
                            <td>machines</td>
                            <td>Desativado</td>
                            <td>2025-06-25 14:35</td>
                        </tr>

                        <tr>
                            <td>GET</td>
                            <td>buzzer</td>
                            <td>Ativado</td>
                            <td>2025-06-25 14:35</td>
                        </tr>

                        <tr>
                            <td>POST</td>
                            <td>fotoResistor</td>
                            <td>845</td>
                            <td>2025-06-25 14:36</td>
                        </tr>

                        <tr>
                            <td>GET</td>
                            <td>lights</td>
                            <td>Ativado</td>
                            <td>2025-06-25 14:36</td>
                        </tr>

                        <tr>
                            <td>POST</td>
                            <td>airConditioner</td>
                            <td>Desativado</td>
                            <td>2025-06-25 14:40</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    <script src="scriptHistory.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
```