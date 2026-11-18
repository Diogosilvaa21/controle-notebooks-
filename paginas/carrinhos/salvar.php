<?php

include("../../conexao.php");

$id = $_POST["id"] ?? "";
$cor = trim($_POST["cor"] ?? "");

// Verifica se escolheu uma cor
if ($cor == "") {
    header("Location: index.php");
    exit;
}

try {

    // Cadastro
    if ($id == "") {

        $sql = "INSERT INTO carrinhos (cor) VALUES (?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$cor]);
    }

    // Edição
    else {

        $sql = "UPDATE carrinhos SET cor = ? WHERE id = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$cor, $id]);
    }
} catch (PDOException $e) {

    die("Erro: " . $e->getMessage());
}

header("Location: index.php");
exit;
