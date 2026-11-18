<?php

require "conexao.php";

echo "<h2>Atualizando banco de dados...</h2>";

try {

    $pdo->exec("ALTER TABLE notebooks ADD COLUMN status TEXT DEFAULT 'Disponível'");

    echo "✅ Campo 'status' criado.<br>";

} catch (PDOException $e) {

    echo "ℹ️ Campo 'status' já existe.<br>";

}

try {

    $pdo->exec("ALTER TABLE notebooks ADD COLUMN observacao TEXT");

    echo "✅ Campo 'observacao' criado.<br>";

} catch (PDOException $e) {

    echo "ℹ️ Campo 'observacao' já existe.<br>";

}

echo "<hr>";
echo "<h3>Banco atualizado com sucesso!</h3>";

?>