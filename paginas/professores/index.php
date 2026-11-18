<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center">

        <h2>👨 Professores</h2>

        <a href="form.php" class="btn btn-primary">

            ➕ Novo Professor

        </a>

    </div>

    <hr>
    <table class="table table-hover">

        <thead>

            <tr>

                <th>ID</th>

                <th>Nome</th>

                <th width="150">

                    Ações

                </th>

            </tr>

        </thead>

        <tbody>

            <?php

            $sql = "SELECT * FROM professores ORDER BY nome";

            $dados = $pdo->query($sql);

            foreach ($dados as $professor) {

            ?>

                <tr>

                    <td><?= $professor["id"] ?></td>

                    <td><?= $professor["nome"] ?></td>

                    <td>

                        <a
                            href="form.php?id=<?= $professor["id"] ?>"
                            class="btn btn-warning btn-sm">

                            ✏️

                        </a>

                        <a
                            href="excluir.php?id=<?= $professor["id"] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Deseja excluir?')">

                            🗑️

                        </a>

                    </td>

                </tr>

            <?php

            }

            ?>

        </tbody>

    </table>

</div>

<?php

include("../../includes/footer.php");

?>