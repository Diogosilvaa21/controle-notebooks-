<?php

include("../../conexao.php");

$carrinho_id = $_GET["carrinho_id"] ?? 0;

$sql = "
SELECT
    id,
    numero
FROM notebooks
WHERE carrinho_id = ?
AND status = 'Disponível'
ORDER BY numero
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$carrinho_id]);

$notebooks = $stmt->fetchAll();

echo "
<div class='form-check mb-3'>

    <input
        class='form-check-input'
        type='checkbox'
        id='selecionarTodos'>

    <label
        class='form-check-label fw-bold'
        for='selecionarTodos'>

        Selecionar todos

    </label>

</div>

<div class='row'>
";

foreach ($notebooks as $notebook) {

    echo "
    <div class='col-md-2 mb-2'>

        <div class='form-check'>

            <input
                class='form-check-input'
                type='checkbox'
                name='notebooks[]'
                value='{$notebook["id"]}'
                id='nb{$notebook["id"]}'>

            <label
                class='form-check-label'
                for='nb{$notebook["id"]}'>

                Notebook " . str_pad($notebook["numero"], 2, "0", STR_PAD_LEFT) . "

            </label>

        </div>

    </div>
    ";
}

echo "</div>";