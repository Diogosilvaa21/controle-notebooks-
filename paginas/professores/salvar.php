<?php

include("../../conexao.php");

// Recebe os dados do formulário
$id = $_POST["id"];
$nome = trim($_POST["nome"]);

// Verifica se o nome foi preenchido
if ($nome == "") {

    header("Location: form.php");
    exit;

}

// Se o ID estiver vazio, cadastra um novo professor
if ($id == "") {

    $sql = "INSERT INTO professores (nome) VALUES (?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$nome]);

}

// Caso contrário, atualiza o professor
else {

    $sql = "UPDATE professores SET nome = ? WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$nome, $id]);

}

// Volta para a lista
header("Location: index.php");
exit;

?>