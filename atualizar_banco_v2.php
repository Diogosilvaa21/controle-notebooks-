<?php

include("conexao.php");

echo "<h2>Atualizando banco...</h2>";

try{

    $pdo->exec("
    ALTER TABLE itens_emprestimo
    ADD COLUMN devolvido INTEGER DEFAULT 0
    ");

    echo "✅ Campo devolvido criado.<br>";

}catch(Exception $e){

    echo "Campo devolvido já existe.<br>";

}

try{

    $pdo->exec("
    ALTER TABLE itens_emprestimo
    ADD COLUMN data_devolucao DATETIME
    ");

    echo "✅ Campo data_devolucao criado.<br>";

}catch(Exception $e){

    echo "Campo data_devolucao já existe.<br>";

}

echo "<hr>";
echo "Banco atualizado.";

?>