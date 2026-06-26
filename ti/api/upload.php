<?php
header('Content-Type: text/html; charset=utf-8');
echo $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_FILES["imagem"] != NULL) {

        print_r($_FILES['imagem']);

        if ($_FILES["imagem"]["size"] > 1000 * 1024) {
            die("Erro: imagem maior que 1000KB");
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tipo = $finfo->file($_FILES["imagem"]["tmp_name"]);

        if ($tipo != "image/jpeg" && $tipo != "image/png") {
            die("Erro: apenas JPG ou PNG");
        }

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