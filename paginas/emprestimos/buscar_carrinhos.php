<?php

include("../../conexao.php");

$tipo = $_GET["tipo"] ?? "COMPLETO";

if ($tipo == "COMPLETO") {

    $sql = "
    SELECT
        c.id,
        c.cor
    FROM carrinhos c
    WHERE NOT EXISTS (

        SELECT 1
        FROM notebooks n
        WHERE
            n.carrinho_id = c.id
            AND n.status <> 'Disponível'

    )
    ORDER BY c.cor
    ";

} else {

    $sql = "
    SELECT DISTINCT
        c.id,
        c.cor
    FROM carrinhos c

    INNER JOIN notebooks n
        ON n.carrinho_id = c.id

    WHERE n.status = 'Disponível'

    ORDER BY c.cor
    ";

}

$carrinhos = $pdo->query($sql)->fetchAll();

if (count($carrinhos) == 0) {

    if ($tipo == "COMPLETO") {

        echo "<option value=''>Nenhum carrinho completo disponível</option>";

    } else {

        echo "<option value=''>Nenhum carrinho disponível</option>";

    }

} else {

    echo "<option value=''>Selecione...</option>";

    foreach ($carrinhos as $carrinho) {

        echo "
        <option value='{$carrinho["id"]}'>

            {$carrinho["cor"]}

        </option>
        ";

    }

}