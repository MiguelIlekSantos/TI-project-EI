<?php
header('Content-Type: text/html; charset=utf-8');
echo $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_FILES["imagem"] != NULL) {

        print_r($_FILES['imagem']);

        $ficheiro_tmp = $_FILES['imagem']['tmp_name'];
        $destino = "images/webcam.jpg";
        move_uploaded_file($ficheiro_tmp, $destino);

    } else {
        echo "Erro: array imagem não existe";
    }

} else {
    echo "Erro: método incorreto";
}
?>