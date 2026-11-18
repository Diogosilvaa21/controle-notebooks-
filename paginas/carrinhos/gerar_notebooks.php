<?php

include("../../conexao.php");

if (!isset($_GET["id"])) {

    die("Carrinho não informado.");

}

$carrinho_id = $_GET["id"];

// Verifica se já existem notebooks para esse carrinho
$sql = "SELECT COUNT(*) FROM notebooks WHERE carrinho_id = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$carrinho_id]);

$total = $stmt->fetchColumn();

if ($total > 0) {

    die("Este carrinho já possui notebooks cadastrados.");

}

// Gera os 40 notebooks
for ($i = 1; $i <= 40; $i++) {

    $sql = "INSERT INTO notebooks (numero, carrinho_id, status)
            VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$i, $carrinho_id, "Disponível"]);

}

header("Location: index.php");

exit;

?>