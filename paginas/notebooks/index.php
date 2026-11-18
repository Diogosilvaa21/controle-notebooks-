<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$mensagem = "";
$tipoMensagem = "";


// ============================================================
// STATUS PERMITIDOS
// ============================================================

$statusPermitidos = [
    "Disponível",
    "Manutenção",
    "Chamado Aberto"
];


// ============================================================
// DADOS DO FORMULÁRIO
// ============================================================

$numeroForm = $_POST["numero"] ?? "";
$carrinhoForm = $_POST["carrinho_id"] ?? "";
$statusForm = $_POST["status"] ?? "Disponível";
$observacaoForm = $_POST["observacao"] ?? "";


// ============================================================
// CADASTRAR NOVO NOTEBOOK
// ============================================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["cadastrar_notebook"])
) {

    $numero = trim($_POST["numero"] ?? "");
    $carrinho_id = $_POST["carrinho_id"] ?? "";
    $status = $_POST["status"] ?? "Disponível";
    $observacao = trim($_POST["observacao"] ?? "");


    // --------------------------------------------------------
    // VALIDAR NÚMERO
    // --------------------------------------------------------

    if (
        $numero === ""
        || !is_numeric($numero)
        || $numero <= 0
    ) {

        $mensagem = "Informe um número de notebook válido.";
        $tipoMensagem = "danger";
    }


    // --------------------------------------------------------
    // VALIDAR CARRINHO
    // --------------------------------------------------------

    elseif (
        $carrinho_id === ""
        || !is_numeric($carrinho_id)
    ) {

        $mensagem = "Selecione um carrinho.";
        $tipoMensagem = "danger";
    }


    // --------------------------------------------------------
    // VALIDAR STATUS
    // --------------------------------------------------------

    elseif (
        !in_array(
            $status,
            $statusPermitidos,
            true
        )
    ) {

        $mensagem = "Status inválido.";
        $tipoMensagem = "danger";
    } else {

        // ----------------------------------------------------
        // VERIFICAR SE O CARRINHO EXISTE
        // ----------------------------------------------------

        $stmtCarrinho = $pdo->prepare("
            SELECT id
            FROM carrinhos
            WHERE id = ?
        ");

        $stmtCarrinho->execute([
            $carrinho_id
        ]);

        if (!$stmtCarrinho->fetch()) {

            $mensagem = "O carrinho selecionado não existe.";
            $tipoMensagem = "danger";
        } else {

            // ------------------------------------------------
            // VERIFICAR NOTEBOOK DUPLICADO
            // ------------------------------------------------

            $stmtVerifica = $pdo->prepare("
                SELECT id
                FROM notebooks
                WHERE numero = ?
            ");

            $stmtVerifica->execute([
                $numero
            ]);

            if ($stmtVerifica->fetch()) {

                $mensagem =
                    "Já existe um notebook cadastrado com o número "
                    . htmlspecialchars($numero)
                    . ".";

                $tipoMensagem = "danger";
            } else {

                // --------------------------------------------
                // CADASTRAR NOTEBOOK
                // --------------------------------------------

                try {

                    $stmtInsert = $pdo->prepare("
                        INSERT INTO notebooks
                        (
                            numero,
                            status,
                            observacao,
                            carrinho_id
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?,
                            ?
                        )
                    ");

                    $stmtInsert->execute([
                        $numero,
                        $status,
                        $observacao,
                        $carrinho_id
                    ]);

                    $mensagem =
                        "Notebook "
                        . str_pad(
                            $numero,
                            2,
                            "0",
                            STR_PAD_LEFT
                        )
                        . " cadastrado com sucesso!";

                    $tipoMensagem = "success";


                    // Limpar formulário
                    $numeroForm = "";
                    $carrinhoForm = "";
                    $statusForm = "Disponível";
                    $observacaoForm = "";
                } catch (PDOException $e) {

                    $mensagem =
                        "Não foi possível cadastrar o notebook.";

                    $tipoMensagem = "danger";
                }
            }
        }
    }
}


// ============================================================
// BUSCAR CARRINHOS
// ============================================================

$stmtCarrinhos = $pdo->query("
    SELECT
        id,
        cor
    FROM carrinhos
    ORDER BY cor
");

$carrinhos = $stmtCarrinhos->fetchAll(PDO::FETCH_ASSOC);


// ============================================================
// FILTROS
// ============================================================

$filtroNumero = trim($_GET["numero"] ?? "");
$filtroStatus = $_GET["status_filtro"] ?? "";
$filtroCarrinho = $_GET["carrinho_filtro"] ?? "";


// ============================================================
// MONTAR FILTROS SQL
// ============================================================

$condicoes = [];
$parametros = [];


// ------------------------------------------------------------
// FILTRO POR NÚMERO
// ------------------------------------------------------------

if ($filtroNumero !== "") {

    $condicoes[] = "notebooks.numero = ?";
    $parametros[] = $filtroNumero;
}


// ------------------------------------------------------------
// FILTRO POR STATUS
// ------------------------------------------------------------

if ($filtroStatus !== "") {

    $statusFiltroPermitidos = [
        "Disponível",
        "Manutenção",
        "Chamado Aberto",
        "Emprestado"
    ];

    if (
        in_array(
            $filtroStatus,
            $statusFiltroPermitidos,
            true
        )
    ) {

        $condicoes[] = "notebooks.status = ?";
        $parametros[] = $filtroStatus;
    }
}


// ------------------------------------------------------------
// FILTRO POR CARRINHO
// ------------------------------------------------------------

if (
    $filtroCarrinho !== ""
    && is_numeric($filtroCarrinho)
) {

    $condicoes[] = "notebooks.carrinho_id = ?";
    $parametros[] = $filtroCarrinho;
}


// ------------------------------------------------------------
// MONTAR WHERE
// ------------------------------------------------------------

$where = "";

if (!empty($condicoes)) {

    $where = "WHERE " . implode(" AND ", $condicoes);
}


// ============================================================
// LISTAR NOTEBOOKS
// ============================================================

$sql = "

    SELECT

        notebooks.id,
        notebooks.numero,
        notebooks.status,
        notebooks.observacao,
        notebooks.carrinho_id,
        carrinhos.cor

    FROM notebooks

    INNER JOIN carrinhos
        ON carrinhos.id = notebooks.carrinho_id

    $where

    ORDER BY
        CASE
            WHEN notebooks.status = 'Chamado Aberto' THEN 1
            WHEN notebooks.status = 'Manutenção' THEN 2
            WHEN notebooks.status = 'Disponível' THEN 3
            WHEN notebooks.status = 'Emprestado' THEN 4
            ELSE 5
        END,
        carrinhos.cor,
        notebooks.numero
";

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ============================================================
// CONTADORES
// ============================================================

$totalNotebooks = count($dados);

$totalChamados = 0;
$totalDisponiveis = 0;
$totalManutencao = 0;
$totalEmprestados = 0;

foreach ($dados as $item) {

    switch ($item["status"]) {

        case "Chamado Aberto":
            $totalChamados++;
            break;

        case "Disponível":
            $totalDisponiveis++;
            break;

        case "Manutenção":
            $totalManutencao++;
            break;

        case "Emprestado":
            $totalEmprestados++;
            break;
    }
}

?>

<style>
    /* ============================================================
   ETIQUETAS
   ============================================================ */

    .etiqueta {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        width: 145px;

        height: 42px;

        padding: 6px 10px;

        border-radius: 8px;

        font-size: 15px;

        font-weight: 600;

        white-space: nowrap;

        box-sizing: border-box;
    }


    /* ============================================================
   CORES DOS CARRINHOS
   ============================================================ */

    .carrinho-amarelo {
        background-color: #ffc107;
        color: #212529;
    }

    .carrinho-azul {
        background-color: #0d6efd;
        color: #ffffff;
    }

    .carrinho-verde {
        background-color: #198754;
        color: #ffffff;
    }

    .carrinho-vermelho {
        background-color: #dc3545;
        color: #ffffff;
    }

    .carrinho-laranja {
        background-color: #fd7e14;
        color: #ffffff;
    }

    .carrinho-roxo {
        background-color: #6f42c1;
        color: #ffffff;
    }

    .carrinho-rosa {
        background-color: #d63384;
        color: #ffffff;
    }

    .carrinho-cinza {
        background-color: #6c757d;
        color: #ffffff;
    }

    .carrinho-preto {
        background-color: #212529;
        color: #ffffff;
    }


    /* ============================================================
   STATUS
   ============================================================ */

    .status-disponivel {
        background-color: #198754;
        color: #ffffff;
    }

    .status-manutencao {
        background-color: #ffc107;
        color: #212529;
    }

    .status-chamado {
        background-color: #dc3545;
        color: #ffffff;
    }

    .status-emprestado {
        background-color: #6c757d;
        color: #ffffff;
    }


    /* ============================================================
   OBSERVAÇÃO
   ============================================================ */

    .observacao-vazia {

        color: #999;

        font-style: italic;
    }


    /* ============================================================
   TABELA
   ============================================================ */

    .tabela-notebooks th {

        font-weight: 700;

        white-space: nowrap;
    }

    .tabela-notebooks td {

        vertical-align: middle;
    }


    /* ============================================================
   NÚMERO
   ============================================================ */

    .numero-notebook {

        font-size: 18px;

        font-weight: 700;
    }


    /* ============================================================
   CARDS DE CONTAGEM
   ============================================================ */

    .card-contador {

        border: 0;

        border-radius: 10px;

        transition: transform 0.2s;
    }

    .card-contador:hover {

        transform: translateY(-2px);
    }

    .numero-contador {

        font-size: 26px;

        font-weight: 700;
    }


    /* ============================================================
   FILTROS RÁPIDOS
   ============================================================ */

    .filtro-rapido {

        font-weight: 600;
    }


    /* ============================================================
   RESPONSIVO
   ============================================================ */

    @media (max-width: 768px) {

        .etiqueta {

            width: 130px;

            height: 40px;

            font-size: 14px;
        }
    }
</style>

<div class="container mt-4 mb-5">

    ```
    <!-- ===================================================== -->
    <!-- MENSAGEM -->
    <!-- ===================================================== -->

    <?php if ($mensagem): ?>

        <div
            class="alert alert-<?= htmlspecialchars($tipoMensagem) ?>
        alert-dismissible fade show shadow-sm"
            role="alert">

            <?= htmlspecialchars($mensagem) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <!-- ===================================================== -->
    <!-- TÍTULO -->
    <!-- ===================================================== -->

    <div class="mb-4">

        <h2 class="fw-bold text-primary mb-1">
            💻 Notebooks
        </h2>

        <p class="text-muted mb-0">
            Cadastre, pesquise e acompanhe os notebooks.
        </p>

    </div>


    <!-- ===================================================== -->
    <!-- CONTADORES -->
    <!-- ===================================================== -->

    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">

            <div class="card card-contador shadow-sm">
                <div class="card-body">

                    <small class="text-muted">
                        Total
                    </small>

                    <div class="numero-contador text-primary">
                        <?= $totalNotebooks ?>
                    </div>

                </div>
            </div>

        </div>


        <div class="col-6 col-md-3">

            <div class="card card-contador shadow-sm">
                <div class="card-body">

                    <small class="text-muted">
                        Disponíveis
                    </small>

                    <div class="numero-contador text-success">
                        <?= $totalDisponiveis ?>
                    </div>

                </div>
            </div>

        </div>


        <div class="col-6 col-md-3">

            <div class="card card-contador shadow-sm">
                <div class="card-body">

                    <small class="text-muted">
                        Chamados Abertos
                    </small>

                    <div class="numero-contador text-danger">
                        <?= $totalChamados ?>
                    </div>

                </div>
            </div>

        </div>


        <div class="col-6 col-md-3">

            <div class="card card-contador shadow-sm">
                <div class="card-body">

                    <small class="text-muted">
                        Manutenção
                    </small>

                    <div class="numero-contador text-warning">
                        <?= $totalManutencao ?>
                    </div>

                </div>
            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- CADASTRO -->
    <!-- ===================================================== -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">
                ➕ Adicionar Notebook
            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                id="formCadastro">

                <div class="row g-3">


                    <!-- NÚMERO -->

                    <div class="col-md-2">

                        <label class="form-label fw-bold">
                            Número
                        </label>

                        <input
                            type="number"
                            name="numero"
                            class="form-control"
                            placeholder="Ex.: 01"
                            min="1"
                            value="<?= htmlspecialchars($numeroForm) ?>"
                            required>

                    </div>


                    <!-- CARRINHO -->

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Carrinho
                        </label>

                        <select
                            name="carrinho_id"
                            class="form-select"
                            required>

                            <option value="">
                                Selecione o carrinho...
                            </option>

                            <?php foreach ($carrinhos as $carrinho): ?>

                                <option
                                    value="<?= $carrinho["id"] ?>"
                                    <?= (
                                        $carrinhoForm
                                        == $carrinho["id"]
                                    )
                                        ? "selected"
                                        : ""
                                    ?>>

                                    🛒
                                    <?= htmlspecialchars(
                                        $carrinho["cor"]
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- STATUS -->

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Situação
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select"
                            required>

                            <option
                                value="Disponível"
                                <?= $statusForm === "Disponível"
                                    ? "selected"
                                    : ""
                                ?>>

                                🟢 Disponível

                            </option>

                            <option
                                value="Manutenção"
                                <?= $statusForm === "Manutenção"
                                    ? "selected"
                                    : ""
                                ?>>

                                🟡 Manutenção

                            </option>

                            <option
                                value="Chamado Aberto"
                                <?= $statusForm === "Chamado Aberto"
                                    ? "selected"
                                    : ""
                                ?>>

                                🔴 Chamado Aberto

                            </option>

                        </select>

                    </div>


                    <!-- OBSERVAÇÃO -->

                    <div class="col-md-4">

                        <label class="form-label fw-bold">
                            Observação
                        </label>

                        <input
                            type="text"
                            name="observacao"
                            class="form-control"
                            maxlength="255"
                            placeholder="Ex.: Tela quebrada, carregador..."
                            value="<?= htmlspecialchars(
                                        $observacaoForm
                                    ) ?>">

                    </div>


                    <!-- BOTÕES -->

                    <div class="col-12">

                        <button
                            type="submit"
                            name="cadastrar_notebook"
                            class="btn btn-success">

                            💾 Cadastrar Notebook

                        </button>

                        <button
                            type="reset"
                            class="btn btn-outline-secondary">

                            Limpar

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- FILTROS -->
    <!-- ===================================================== -->

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-light">

            <h5 class="mb-0 fw-bold">
                🔎 Pesquisar e Filtrar
            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                id="formFiltros">

                <div class="row g-3">


                    <!-- NÚMERO -->

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Número do Notebook
                        </label>

                        <input
                            type="number"
                            name="numero"
                            class="form-control"
                            min="1"
                            placeholder="Ex.: 01"
                            value="<?= htmlspecialchars(
                                        $filtroNumero
                                    ) ?>">

                    </div>


                    <!-- STATUS -->

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Status
                        </label>

                        <select
                            name="status_filtro"
                            class="form-select">

                            <option value="">
                                Todos os status
                            </option>

                            <option
                                value="Disponível"
                                <?= $filtroStatus === "Disponível"
                                    ? "selected"
                                    : ""
                                ?>>

                                🟢 Disponível

                            </option>

                            <option
                                value="Chamado Aberto"
                                <?= $filtroStatus === "Chamado Aberto"
                                    ? "selected"
                                    : ""
                                ?>>

                                🔴 Chamado Aberto

                            </option>

                            <option
                                value="Manutenção"
                                <?= $filtroStatus === "Manutenção"
                                    ? "selected"
                                    : ""
                                ?>>

                                🟡 Manutenção

                            </option>

                            <option
                                value="Emprestado"
                                <?= $filtroStatus === "Emprestado"
                                    ? "selected"
                                    : ""
                                ?>>

                                ⚫ Emprestado

                            </option>

                        </select>

                    </div>


                    <!-- CARRINHO -->

                    <div class="col-md-3">

                        <label class="form-label fw-bold">
                            Carrinho
                        </label>

                        <select
                            name="carrinho_filtro"
                            class="form-select">

                            <option value="">
                                Todos os carrinhos
                            </option>

                            <?php foreach ($carrinhos as $carrinho): ?>

                                <option
                                    value="<?= $carrinho["id"] ?>"
                                    <?= (
                                        $filtroCarrinho
                                        == $carrinho["id"]
                                    )
                                        ? "selected"
                                        : ""
                                    ?>>

                                    🛒
                                    <?= htmlspecialchars(
                                        $carrinho["cor"]
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- BOTÕES -->

                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            🔍 Filtrar

                        </button>

                        <a
                            href="index.php"
                            class="btn btn-secondary">

                            Limpar

                        </a>

                    </div>

                </div>

            </form>


            <!-- ================================================= -->
            <!-- FILTROS RÁPIDOS -->
            <!-- ================================================= -->

            <hr class="my-4">

            <div>

                <div class="fw-bold mb-2">
                    Filtros rápidos:
                </div>

                <div class="d-flex flex-wrap gap-2">


                    <!-- TODOS -->

                    <a
                        href="index.php"
                        class="btn btn-outline-primary btn-sm filtro-rapido">

                        📋 Todos

                    </a>


                    <!-- CHAMADOS -->

                    <a
                        href="index.php?status_filtro=Chamado%20Aberto"
                        class="btn btn-outline-danger btn-sm filtro-rapido">

                        🔴 Chamados Abertos

                    </a>


                    <!-- DISPONÍVEIS -->

                    <a
                        href="index.php?status_filtro=Disponível"
                        class="btn btn-outline-success btn-sm filtro-rapido">

                        🟢 Disponíveis

                    </a>


                    <!-- MANUTENÇÃO -->

                    <a
                        href="index.php?status_filtro=Manutenção"
                        class="btn btn-outline-warning btn-sm filtro-rapido">

                        🟡 Manutenção

                    </a>


                    <!-- EMPRESTADOS -->

                    <a
                        href="index.php?status_filtro=Emprestado"
                        class="btn btn-outline-secondary btn-sm filtro-rapido">

                        ⚫ Emprestados

                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- LISTAGEM -->
    <!-- ===================================================== -->

    <div class="card shadow-sm border-0">

        <div class="card-body">


            <!-- CABEÇALHO DA LISTA -->

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h4 class="fw-bold mb-0">
                        📋 Lista de Notebooks
                    </h4>

                    <small class="text-muted">

                        <?= $totalNotebooks ?>

                        notebook(s) encontrado(s)

                    </small>

                </div>


                <?php if ($filtroNumero !== "" || $filtroStatus !== "" || $filtroCarrinho !== ""): ?>

                    <span class="badge bg-primary">

                        🔎 Filtro aplicado

                    </span>

                <?php endif; ?>

            </div>


            <!-- ================================================= -->
            <!-- TABELA -->
            <!-- ================================================= -->

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0 tabela-notebooks">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Número
                            </th>

                            <th>
                                Carrinho
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Observação
                            </th>

                            <th class="text-center">
                                Ações
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php if (empty($dados)): ?>

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-5">

                                    <div class="fs-1">
                                        💻
                                    </div>

                                    <div class="fw-bold">
                                        Nenhum notebook encontrado.
                                    </div>

                                    <small>
                                        Tente alterar os filtros.
                                    </small>

                                </td>

                            </tr>


                        <?php else: ?>


                            <?php foreach ($dados as $notebook): ?>


                                <?php

                                // =================================================
                                // COR DO CARRINHO
                                // =================================================

                                $corCarrinho = strtolower(
                                    trim(
                                        $notebook["cor"]
                                    )
                                );

                                $classeCarrinho =
                                    "carrinho-cinza";


                                switch ($corCarrinho) {

                                    case "amarelo":
                                        $classeCarrinho =
                                            "carrinho-amarelo";
                                        break;

                                    case "azul":
                                        $classeCarrinho =
                                            "carrinho-azul";
                                        break;

                                    case "verde":
                                        $classeCarrinho =
                                            "carrinho-verde";
                                        break;

                                    case "vermelho":
                                        $classeCarrinho =
                                            "carrinho-vermelho";
                                        break;

                                    case "laranja":
                                        $classeCarrinho =
                                            "carrinho-laranja";
                                        break;

                                    case "roxo":
                                        $classeCarrinho =
                                            "carrinho-roxo";
                                        break;

                                    case "rosa":
                                        $classeCarrinho =
                                            "carrinho-rosa";
                                        break;

                                    case "cinza":
                                        $classeCarrinho =
                                            "carrinho-cinza";
                                        break;

                                    case "preto":
                                        $classeCarrinho =
                                            "carrinho-preto";
                                        break;
                                }


                                // =================================================
                                // STATUS
                                // =================================================

                                $status =
                                    $notebook["status"];

                                $classeStatus =
                                    "status-emprestado";

                                $iconeStatus = "⚪";


                                if (
                                    $status ===
                                    "Disponível"
                                ) {

                                    $classeStatus =
                                        "status-disponivel";

                                    $iconeStatus = "🟢";
                                } elseif (
                                    $status ===
                                    "Manutenção"
                                ) {

                                    $classeStatus =
                                        "status-manutencao";

                                    $iconeStatus = "🟡";
                                } elseif (
                                    $status ===
                                    "Chamado Aberto"
                                ) {

                                    $classeStatus =
                                        "status-chamado";

                                    $iconeStatus = "🔴";
                                } elseif (
                                    $status ===
                                    "Emprestado"
                                ) {

                                    $classeStatus =
                                        "status-emprestado";

                                    $iconeStatus = "⚫";
                                }

                                ?>


                                <tr>


                                    <!-- NÚMERO -->

                                    <td>

                                        <span
                                            class="numero-notebook">

                                            <?= str_pad(
                                                $notebook["numero"],
                                                2,
                                                "0",
                                                STR_PAD_LEFT
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- CARRINHO -->

                                    <td>

                                        <span
                                            class="etiqueta <?= $classeCarrinho ?>">

                                            🛒

                                            <?= htmlspecialchars(
                                                $notebook["cor"]
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <span
                                            class="etiqueta <?= $classeStatus ?>">

                                            <?= $iconeStatus ?>

                                            <?= htmlspecialchars(
                                                $status
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- OBSERVAÇÃO -->

                                    <td>

                                        <?php if (
                                            !empty($notebook["observacao"])
                                        ): ?>

                                            <?= htmlspecialchars(
                                                $notebook["observacao"]
                                            ) ?>

                                        <?php else: ?>

                                            <span
                                                class="observacao-vazia">

                                                Sem observação

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- AÇÕES -->

                                    <td class="text-center">

                                        <a
                                            href="editar.php?id=<?= $notebook["id"] ?>"
                                            class="btn btn-sm btn-warning">

                                            ✏️ Editar

                                        </a>

                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>
    ```

</div>

<script>
    // ============================================================
    // COR DO SELECT DE STATUS
    // ============================================================

    const statusSelect =
        document.getElementById("status");


    if (statusSelect) {

        function atualizarCorStatus() {

            statusSelect.classList.remove(
                "text-success",
                "text-warning",
                "text-danger",
                "fw-bold"
            );


            if (
                statusSelect.value ===
                "Disponível"
            ) {

                statusSelect.classList.add(
                    "text-success",
                    "fw-bold"
                );

            }


            if (
                statusSelect.value ===
                "Manutenção"
            ) {

                statusSelect.classList.add(
                    "text-warning",
                    "fw-bold"
                );

            }


            if (
                statusSelect.value ===
                "Chamado Aberto"
            ) {

                statusSelect.classList.add(
                    "text-danger",
                    "fw-bold"
                );
            }

        }


        statusSelect.addEventListener(
            "change",
            atualizarCorStatus
        );


        atualizarCorStatus();

    }


    // ============================================================
    // CONFIRMAR CADASTRO
    // ============================================================

    const formCadastro =
        document.getElementById("formCadastro");


    if (formCadastro) {

        formCadastro.addEventListener(
            "submit",
            function(event) {

                const numero =
                    this.querySelector(
                        '[name="numero"]'
                    ).value;


                const carrinho =
                    this.querySelector(
                        '[name="carrinho_id"]'
                    );


                const status =
                    this.querySelector(
                        '[name="status"]'
                    ).value;


                const carrinhoTexto =
                    carrinho
                    .options[
                        carrinho.selectedIndex
                    ]
                    .text;


                const mensagem =

                    "Confirma o cadastro?\n\n" +

                    "💻 Notebook: " +
                    numero +
                    "\n" +

                    "🛒 Carrinho: " +
                    carrinhoTexto +
                    "\n" +

                    "📌 Status: " +
                    status;


                if (!confirm(mensagem)) {

                    event.preventDefault();
                }

            }
        );

    }
</script>

<?php

include("../../includes/footer.php");

?>