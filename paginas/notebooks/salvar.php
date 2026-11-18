<?php

include("../../conexao.php");

$id = $_POST["id"];
$status = $_POST["status"];
$observacao = trim($_POST["observacao"]);

$sql = "UPDATE notebooks
        SET status = ?, observacao = ?
        WHERE id = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$status, $observacao, $id]);

header("Location: index.php");
exit;

?>