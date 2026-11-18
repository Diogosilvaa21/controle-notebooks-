<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

// ============================================================
// BUSCAR EMPRÉSTIMOS
// ============================================================

$sql = "
    SELECT
        e.*,
        p.nome AS professor_nome,
        c.cor AS carrinho_cor,

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

    GROUP BY e.id

    ORDER BY e.id DESC
";

$dados = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);


// ============================================================
// FUNÇÃO PARA FORMATAR HORA
// ============================================================

function formatarHora($hora)
{
    if (empty($hora)) {
        return "-";
    }

    // Se vier somente HH:MM
    if (strlen($hora) === 5) {
        return $hora . ":00";
    }

    // Se vier HH:MM:SS
    if (strlen($hora) >= 8) {
        return substr($hora, 0, 8);
    }

    return $hora;
}

?>

<div class="container mt-4 mb-5">

    <!-- ===================================================== -->
    <!-- CABEÇALHO -->
    <!-- ===================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold text-primary mb-1">
                📋 Empréstimos
            </h2>

            <p class="text-muted mb-0">
                Controle dos empréstimos de notebooks.
            </p>
        </div>

        <a href="form.php" class="btn btn-primary">
            ➕ Novo Empréstimo
        </a>

    </div>


    <!-- ===================================================== -->
    <!-- TABELA -->
    <!-- ===================================================== -->

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle mb-0">

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

                        <?php if (empty($dados)): ?>

                            <tr>

                                <td colspan="9" class="text-center text-muted py-4">

                                    📋 Nenhum empréstimo encontrado.

                                </td>

                            </tr>

                        <?php else: ?>


                            <?php foreach ($dados as $emprestimo): ?>

                                <tr>

                                    <!-- PROFESSOR -->

                                    <td>

                                        <?= htmlspecialchars($emprestimo["professor_nome"]) ?>

                                    </td>


                                    <!-- CARRINHO -->

                                    <td>

                                        <?= htmlspecialchars($emprestimo["carrinho_cor"]) ?>

                                    </td>


                                    <!-- AULA -->

                                    <td>

                                        <?= htmlspecialchars($emprestimo["aula"]) ?>

                                    </td>


                                    <!-- DATA -->

                                    <td>

                                        <?php

                                        if (!empty($emprestimo["data"])) {

                                            echo date(
                                                "d/m/Y",
                                                strtotime($emprestimo["data"])
                                            );
                                        } else {

                                            echo "-";
                                        }

                                        ?>

                                    </td>


                                    <!-- HORA RETIRADA -->

                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                formatarHora(
                                                    $emprestimo["hora_retirada"]
                                                )
                                            ) ?>

                                        </strong>

                                    </td>


                                    <!-- HORA DEVOLUÇÃO -->

                                    <td>

                                        <?php

                                        if (!empty($emprestimo["hora_devolucao"])):

                                        ?>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    formatarHora(
                                                        $emprestimo["hora_devolucao"]
                                                    )
                                                ) ?>

                                            </strong>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <?php

                                        switch ($emprestimo["status"]) {

                                            case "ABERTO":

                                                echo '
                                                    <span class="badge bg-danger">
                                                        🔴 ABERTO
                                                    </span>
                                                ';

                                                break;


                                            case "DEVOLUÇÃO PARCIAL":

                                                echo '
                                                    <span class="badge bg-warning text-dark">
                                                        🟠 DEVOLUÇÃO PARCIAL
                                                    </span>
                                                ';

                                                break;


                                            case "FINALIZADO":

                                                echo '
                                                    <span class="badge bg-primary">
                                                        🔵 FINALIZADO
                                                    </span>
                                                ';

                                                break;


                                            default:

                                                echo '
                                                    <span class="badge bg-secondary">
                                                        ⚪ ' .
                                                    htmlspecialchars(
                                                        $emprestimo["status"]
                                                    ) .
                                                    '</span>
                                                ';

                                                break;
                                        }

                                        ?>

                                    </td>


                                    <!-- PENDENTES -->

                                    <td>

                                        <?php

                                        $pendentes = (int) $emprestimo["pendentes"];

                                        if ($pendentes === 0) {

                                            echo '
                                                <span class="badge bg-success">
                                                    🟢 0
                                                </span>
                                            ';
                                        } elseif ($pendentes <= 5) {

                                            echo '
                                                <span class="badge bg-warning text-dark">
                                                    🟡 ' .
                                                $pendentes .
                                                '</span>
                                            ';
                                        } else {

                                            echo '
                                                <span class="badge bg-danger">
                                                    🔴 ' .
                                                $pendentes .
                                                '</span>
                                            ';
                                        }

                                        ?>

                                    </td>


                                    <!-- AÇÕES -->

                                    <td>

                                        <div class="d-flex gap-2">

                                            <!-- DETALHES -->

                                            <a
                                                href="detalhes.php?id=<?= (int) $emprestimo["id"] ?>"
                                                class="btn btn-info btn-sm">
                                                👁 Detalhes
                                            </a>


                                            <!-- FINALIZAR -->

                                            <?php if ($emprestimo["status"] !== "FINALIZADO"): ?>

                                                <a
                                                    href="devolver.php?id=<?= (int) $emprestimo["id"] ?>"
                                                    class="btn btn-success btn-sm">
                                                    ✅ Finalizar
                                                </a>

                                            <?php endif; ?>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<?php

include("../../includes/footer.php");

?>