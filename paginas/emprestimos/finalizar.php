<?php

include("../../conexao.php");

$emprestimo_id = $_POST["emprestimo_id"];

$devolvidos = $_POST["notebooks"] ?? [];

$observacao = trim($_POST["observacao"] ?? []);


// Busca todos os notebooks do empréstimo

$sql = "
SELECT id, notebook_id
FROM itens_emprestimo
WHERE emprestimo_id = ?
";

$stmt = $pdo->prepare($sql);

$stmt->execute([$emprestimo_id]);

$itens = $stmt->fetchAll();


foreach ($itens as $item) {

    // Se foi marcado como devolvido

    if (in_array($item["id"], $devolvidos)) {

        // Atualiza notebook

        $sql = "
        UPDATE notebooks
        SET status='Disponível'
        WHERE id=?
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $item["notebook_id"]
        ]);


        // Marca item como devolvido
        // e salva observação

        $sql = "
        UPDATE itens_emprestimo

        SET
            devolvido=1,
            data_devolucao=datetime('now'),
            observacao=?

        WHERE id=?
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $observacao,
            $item["id"]
        ]);

    }

}


// Verifica se ainda existe notebook não devolvido

$sql = "
SELECT COUNT(*)
FROM itens_emprestimo
WHERE
emprestimo_id=?
AND devolvido=0
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $emprestimo_id
]);

$pendentes = $stmt->fetchColumn();


// Todos foram devolvidos

if ($pendentes == 0) {

    $sql = "
    UPDATE emprestimos

    SET
        status='FINALIZADO',
        hora_devolucao=?

    WHERE id=?
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        date("H:i:s"),
        $emprestimo_id
    ]);


// Ainda existem notebooks pendentes

} else {

    $sql = "
    UPDATE emprestimos

    SET status='DEVOLUÇÃO PARCIAL'

    WHERE id=?
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $emprestimo_id
    ]);

}


header("Location: index.php");

exit;

?>