<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$sql = "
SELECT
    e.*,
    p.nome,
    c.cor,

    (
        SELECT COUNT(*)
        FROM itens_emprestimo ie2
        WHERE ie2.emprestimo_id = e.id
    ) AS total_notebooks,

    (
        SELECT COUNT(*)
        FROM itens_emprestimo ie3
        WHERE ie3.emprestimo_id = e.id
        AND ie3.devolvido = 0
    ) AS pendentes

FROM emprestimos e

INNER JOIN professores p
    ON p.id = e.professor_id

INNER JOIN itens_emprestimo ie
    ON ie.emprestimo_id = e.id

INNER JOIN notebooks n
    ON n.id = ie.notebook_id

INNER JOIN carrinhos c
    ON c.id = n.carrinho_id

WHERE e.status = 'FINALIZADO'

GROUP BY e.id

ORDER BY e.id DESC
";

$dados = $pdo->query($sql);

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between">

        <h2>📚 Histórico de Empréstimos</h2>

        <a href="form.php" class="btn btn-primary">

            ➕ Novo Empréstimo

        </a>

    </div>

    <hr>

    <div class="table-responsive">

        <table class="table table-striped table-hover align-middle">
            <thead>

                <tr>
                    <th>Professor</th>
                    <th>Carrinho</th>
                    <th>Aula</th>
                    <th>Data</th>
                    <th>Retirada</th>
                    <th>Devolução</th>
                    <th>Status</th>
                    <th>Pendentes</th>
                    <th>Ações</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($dados as $emprestimo) { ?>

                    <tr>

                        <td><?= $emprestimo["nome"] ?></td>

                        <td><?= $emprestimo["cor"] ?></td>

                        <td><?= $emprestimo["aula"] ?></td>

                        <td><?= date("d/m/Y", strtotime($emprestimo["data"])) ?></td>

                        <td><?= $emprestimo["hora_retirada"] ?></td>

                        <td>

                            <?php

                            if ($emprestimo["hora_devolucao"]) {

                                echo $emprestimo["hora_devolucao"];
                            } else {

                                echo "-";
                            }

                            ?>

                        </td>

                        <td>

                            <?php

                            switch ($emprestimo["status"]) {

                                case "ABERTO":

                                    echo "<span class='badge bg-danger'>🔴 ABERTO</span>";

                                    break;

                                case "DEVOLUÇÃO PARCIAL":

                                    echo "<span class='badge bg-warning text-dark'>🟠 DEVOLUÇÃO PARCIAL</span>";

                                    break;

                                case "FINALIZADO":

                                    echo "<span class='badge bg-primary'>🔵 FINALIZADO</span>";

                                    break;
                            }
                            ?>

                        </td>
                        <td>

                            <?php

                            if ($emprestimo["pendentes"] == 0) {

                                echo "<span class='badge bg-success'>🟢 {$emprestimo["pendentes"]}</span>";
                            } elseif ($emprestimo["pendentes"] <= 5) {

                                echo "<span class='badge bg-warning text-dark'>🟡 {$emprestimo["pendentes"]}</span>";
                            } else {

                                echo "<span class='badge bg-danger'>🔴 {$emprestimo["pendentes"]}</span>";
                            }

                            ?>

                        </td>
                        <td>

                            <a href="detalhes.php?id=<?= $emprestimo["id"] ?>"
                                class="btn btn-info btn-sm">

                                👁 Detalhes

                            </a>

                            <?php if ($emprestimo["status"] != "FINALIZADO") { ?>

                                <a href="detalhes.php?id=<?= $emprestimo["id"] ?>"
                                    class="btn btn-info btn-sm">

                                    👁 Detalhes

                                </a>

                            <?php } ?>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>
</div>

<?php

include("../../includes/footer.php");

?>