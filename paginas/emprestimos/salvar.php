<?php

include("../../conexao.php");

// ============================================================
// GARANTE O FUSO HORÁRIO DO BRASIL
// ============================================================

date_default_timezone_set('America/Sao_Paulo');


// ============================================================
// RECEBE OS DADOS DO FORMULÁRIO
// ============================================================

$professor_id = $_POST["professor_id"] ?? null;
$carrinho_id = $_POST["carrinho_id"] ?? null;
$aula = trim($_POST["aula"] ?? "");
$tipo = $_POST["tipo"] ?? "COMPLETO";

$notebooksSelecionados = $_POST["notebooks"] ?? [];


// ============================================================
// VALIDAÇÕES
// ============================================================

if (!$professor_id) {
    die("
        <h2>❌ Professor não selecionado.</h2>
        <a href='form.php'>Voltar</a>
    ");
}

if (!$carrinho_id) {
    die("
        <h2>❌ Carrinho não selecionado.</h2>
        <a href='form.php'>Voltar</a>
    ");
}

if ($aula === "") {
    die("
        <h2>❌ Informe a turma/aula.</h2>
        <a href='form.php'>Voltar</a>
    ");
}


// ============================================================
// VERIFICA DISPONIBILIDADE
// ============================================================

if ($tipo == "COMPLETO") {

    $sql = "
        SELECT COUNT(*)
        FROM notebooks
        WHERE carrinho_id = ?
        AND status <> 'Disponível'
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$carrinho_id]);

    if ($stmt->fetchColumn() > 0) {

        die("
            <h2>❌ Este carrinho não está totalmente disponível.</h2>
            <br>
            <a href='form.php'>Voltar</a>
        ");
    }
} else {

    if (empty($notebooksSelecionados)) {

        die("
            <h2>❌ Nenhum notebook foi selecionado.</h2>
            <br>
            <a href='form.php'>Voltar</a>
        ");
    }

    foreach ($notebooksSelecionados as $idNotebook) {

        $sql = "
            SELECT status
            FROM notebooks
            WHERE id = ?
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idNotebook]);

        $status = $stmt->fetchColumn();

        if ($status !== "Disponível") {

            die("
                <h2>❌ Um dos notebooks selecionados não está mais disponível.</h2>
                <br>
                <a href='form.php'>Voltar</a>
            ");
        }
    }
}


// ============================================================
// DATA E HORA DA RETIRADA
// ============================================================

// Horário local de Brasília
$data = date("Y-m-d");
$hora = date("H:i:s");


// ============================================================
// INICIA TRANSAÇÃO
// ============================================================

$pdo->beginTransaction();

try {

    // ========================================================
    // 1. REGISTRA O EMPRÉSTIMO
    // ========================================================

    $sql = "
        INSERT INTO emprestimos
        (
            professor_id,
            tipo,
            aula,
            data,
            hora_retirada,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $professor_id,
        "Carrinho",
        $aula,
        $data,
        $hora,
        "ABERTO"
    ]);


    // ========================================================
    // 2. PEGA O ID DO EMPRÉSTIMO
    // ========================================================

    $emprestimo_id = $pdo->lastInsertId();


    // ========================================================
    // 3. BUSCA OS NOTEBOOKS
    // ========================================================

    if ($tipo == "COMPLETO") {

        $sql = "
            SELECT id
            FROM notebooks
            WHERE carrinho_id = ?
            AND status = 'Disponível'
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$carrinho_id]);

        $notebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {

        $notebooks = [];

        foreach ($notebooksSelecionados as $idNotebook) {

            $notebooks[] = [
                "id" => $idNotebook
            ];
        }
    }


    // ========================================================
    // VERIFICA SE EXISTEM NOTEBOOKS
    // ========================================================

    if (empty($notebooks)) {

        throw new Exception(
            "Nenhum notebook disponível para este empréstimo."
        );
    }


    // ========================================================
    // 4. LIGA OS NOTEBOOKS AO EMPRÉSTIMO
    // ========================================================

    foreach ($notebooks as $notebook) {

        $sql = "
            INSERT INTO itens_emprestimo
            (
                emprestimo_id,
                notebook_id
            )
            VALUES (?, ?)
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $emprestimo_id,
            $notebook["id"]
        ]);
    }


    // ========================================================
    // 5. ALTERA O STATUS DOS NOTEBOOKS
    // ========================================================

    foreach ($notebooks as $notebook) {

        $sql = "
            UPDATE notebooks
            SET status = 'Emprestado'
            WHERE id = ?
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $notebook["id"]
        ]);
    }


    // ========================================================
    // 6. CONFIRMA A TRANSAÇÃO
    // ========================================================

    $pdo->commit();


    // ========================================================
    // 7. VOLTA PARA A LISTA
    // ========================================================

    header("Location: index.php");
    exit;
} catch (Exception $erro) {

    // Desfaz tudo se ocorrer algum erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    die("
        <h2>❌ Erro ao registrar empréstimo</h2>

        <p>
            " . htmlspecialchars($erro->getMessage()) . "
        </p>

        <br>

        <a href='form.php'>Voltar</a>
    ");
}
