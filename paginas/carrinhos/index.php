<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$sql = "SELECT * FROM carrinhos ORDER BY cor";
$dados = $pdo->query($sql);

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center">

        <h2>🛒 Carrinhos</h2>

        <a href="form.php" class="btn btn-primary">
            ➕ Novo Carrinho
        </a>

    </div>

    <hr>

    <table class="table table-striped table-hover">

        <thead>

            <tr>
                <th>ID</th>
                <th>Cor</th>
                <th>Ações</th>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($dados as $carrinho) { ?>

                <tr>

                    <td><?= $carrinho["id"] ?></td>

                    <td><?= $carrinho["cor"] ?></td>

                    <td>

                        <a href="form.php?id=<?= $carrinho["id"] ?>" class="btn btn-warning btn-sm">
                            ✏️
                        </a>

                        <a href="excluir.php?id=<?= $carrinho["id"] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Deseja realmente excluir este carrinho?')">
                            🗑️
                        </a>

                        <a href="gerar_notebooks.php?id=<?= $carrinho["id"] ?>"
                            class="btn btn-success btn-sm"
                            onclick="return confirm('Deseja gerar os 40 notebooks deste carrinho?')">

                            💻 Gerar Notebooks

                        </a>

                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

</div>

<?php

include("../../includes/footer.php");

?>