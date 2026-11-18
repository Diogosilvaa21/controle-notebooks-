<?php

include("../includes/header.php");
include("../includes/menu.php");
include("../conexao.php");
$nome = "";
$idEditar = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);

    if ($nome != "") {

        $sql = "INSERT INTO professores (nome) VALUES (?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$nome]);

        header("Location: professores.php");
        exit;
    }
}

if (isset($_GET["excluir"])) {

    $id = $_GET["excluir"];

    $sql = "DELETE FROM professores WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$id]);

    header("Location: professores.php");
    exit;
}

if (isset($_GET["editar"])) {

    $idEditar = $_GET["editar"];

    $sql = "SELECT * FROM professores WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$idEditar]);

    $professor = $stmt->fetch();

    $nome = $professor["nome"];
}

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h2>👨 Cadastro de Professores</h2>

            <p class="text-secondary">

                Gerencie os professores da escola.

            </p>

        </div>

    </div>

    <hr>

    <form method="POST">

        <div class="mb-3">

            <label class="form-label">

                Nome do Professor

            </label>

            <input
                type="text"
                name="nome"
                class="form-control"
                placeholder="Digite o nome do professor"
                value="<?= $nome ?>"
                required>

        </div>

        <button class="btn btn-primary">

            💾 Salvar Professor

        </button>

    </form>
    <hr>

    <h4>Professores Cadastrados</h4>

    <table class="table table-striped">

        <thead>

            <tr>

                <th>ID</th>

                <th>Nome do Professor</th>

                <th width="150">

                    Ações

                </th>

            </tr>

        </thead>

        <tbody>

            <?php

            $sql = "SELECT * FROM professores ORDER BY nome";

            $resultado = $pdo->query($sql);

            foreach ($resultado as $professor) {

            ?>

                <tr>

                    <td><?= $professor["id"] ?></td>

                    <td><?= $professor["nome"] ?></td>

                    <td>

                        <a
                            href="professores.php?editar=<?= $professor["id"] ?>"
                            class="btn btn-warning btn-sm">

                            ✏️

                        </a>

                        <a
                            href="professores.php?excluir=<?= $professor["id"] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Deseja realmente excluir este professor?')">

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

include("../includes/footer.php");

?>