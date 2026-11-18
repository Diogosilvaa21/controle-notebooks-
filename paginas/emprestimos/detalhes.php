<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$id = $_GET["id"];

// Dados do empréstimo
$sql = "
SELECT
    e.*,
    p.nome,
    c.cor
FROM emprestimos e

INNER JOIN professores p
ON p.id = e.professor_id

INNER JOIN itens_emprestimo ie
ON ie.emprestimo_id = e.id

INNER JOIN notebooks n
ON n.id = ie.notebook_id

INNER JOIN carrinhos c
ON c.id = n.carrinho_id

WHERE e.id = ?

GROUP BY e.id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

$emprestimo = $stmt->fetch();


// Lista dos notebooks

$sql = "
SELECT
n.numero,
n.status,
n.observacao

FROM itens_emprestimo ie

INNER JOIN notebooks n
ON n.id = ie.notebook_id

WHERE ie.emprestimo_id=?

ORDER BY n.numero
";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

$notebooks = $stmt->fetchAll();

?>

<div class="container mt-4">

    <h2>📋 Detalhes do Empréstimo</h2>

    <hr>

    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">

                📋 Informações do Empréstimo

            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <strong>👨 Professor</strong><br>

                    <?= $emprestimo["nome"] ?>

                </div>

                <div class="col-md-6">

                    <strong>🛒 Carrinho</strong><br>

                    <?= $emprestimo["cor"] ?>

                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-4">

                    <strong>🏫 Turma</strong><br>

                    <?= $emprestimo["aula"] ?>

                </div>

                <div class="col-md-4">

                    <strong>📅 Data</strong><br>

                    <?= date("d/m/Y", strtotime($emprestimo["data"])) ?>

                </div>

                <div class="col-md-4">

                    <strong>⏰ Hora da Retirada</strong><br>

                    <?= $emprestimo["hora_retirada"] ?>

                </div>

            </div>

            <hr>

            <div class="row">

                <div class="col-md-6">

                    <strong>⏰ Hora da Devolução</strong><br>

                    <?= $emprestimo["hora_devolucao"] ?: "Ainda não devolvido" ?>

                </div>

                <hr>

                <div class="row">

                    <div class="col-md-12">

                        <strong>📝 Observação da Devolução</strong><br>

                        <?= !empty($emprestimo["observacao"])
                            ? htmlspecialchars($emprestimo["observacao"])
                            : "Nenhuma observação registrada." ?>

                    </div>

                </div>

                <div class="col-md-6">

                    <strong>Status</strong><br>

                    <?php

                    switch ($emprestimo["status"]) {

                        case "ABERTO":

                            echo "<span class='badge bg-danger fs-6'>🔴 ABERTO</span>";

                            break;

                        case "DEVOLUÇÃO PARCIAL":

                            echo "<span class='badge bg-warning text-dark fs-6'>🟠 DEVOLUÇÃO PARCIAL</span>";

                            break;

                        case "FINALIZADO":

                            echo "<span class='badge bg-primary fs-6'>🔵 FINALIZADO</span>";

                            break;
                    }

                    ?>
                </div>

            </div>

        </div>

    </div>


    <h4>Notebooks</h4>

    <table class="table table-striped">

        <thead>

            <tr>

                <th>Número</th>

                <th>Status</th>

                <th>Observação</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($notebooks as $n) { ?>

                <tr>

                    <td><?= str_pad($n["numero"], 2, "0", STR_PAD_LEFT) ?></td>

                    <td>

                        <?php

                        switch ($n["status"]) {

                            case "Disponível":

                                echo "<span class='badge bg-success'>🟢 Disponível</span>";

                                break;

                            case "Emprestado":

                                echo "<span class='badge bg-danger'>🔴 Emprestado</span>";

                                break;

                            case "Manutenção":

                                echo "<span class='badge bg-warning text-dark'>🟠 Manutenção</span>";

                                break;
                        }

                        ?>

                    </td>

                    <td>

                        <?= $n["observacao"] ?: "-" ?>

                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

    <a href="index.php" class="btn btn-secondary">

        Voltar

    </a>

</div>

<?php

include("../../includes/footer.php");

?>