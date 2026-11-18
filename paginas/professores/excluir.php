<?php

include("../../conexao.php");

if(isset($_GET["id"])){

    $id = $_GET["id"];

    $sql = "DELETE FROM professores WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$id]);

}

header("Location: index.php");
exit;

?>

