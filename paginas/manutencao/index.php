<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$sql = "
SELECT
    n.id,
    n.numero,
    n.observacao,
    c.cor

FROM notebooks n

INNER JOIN carrinhos c
    ON c.id = n.carrinho_id

WHERE n.status = 'Manutenção'

ORDER BY n.numero
";

$stmt = $pdo->query($sql);

$notebooks = $stmt->fetchAll();

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center">

        <h2>🔧 Notebooks em Manutenção</h2>

    </div>

    <hr>

    <?php if (count($notebooks) == 0) { ?>

        <div class="alert alert-success">

            🟢 Nenhum notebook está em manutenção.

        </div>

    <?php } else { ?>

        <table class="table table-striped">

            <thead>

                <tr>

                    <th>Notebook</th>

                    <th>Carrinho</th>

                    <th>Observação</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($notebooks as $notebook) { ?>

                    <tr>

                        <td>

                            Notebook
                            <?= str_pad($notebook["numero"], 2, "0", STR_PAD_LEFT) ?>

                        </td>

                        <td>

                            <?= $notebook["cor"] ?>

                        </td>

                        <td>

                            <?= $notebook["observacao"] ?: "-" ?>

                        </td>

                        <td>

                            <span class="badge bg-warning text-dark">

                                🟠 Manutenção

                            </span>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    <?php } ?>

</div>

<?php

include("../../includes/footer.php");

?>