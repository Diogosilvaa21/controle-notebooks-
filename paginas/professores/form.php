<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

// Variáveis padrão
$id = "";
$nome = "";

// Se existir um ID, estamos editando
if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "SELECT * FROM professores WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$id]);

    $professor = $stmt->fetch();

    if ($professor) {
        $nome = $professor["nome"];
    }
}

?>

<div class="container mt-4">

    <h2>

        <?= $id ? "✏️ Editar Professor" : "➕ Novo Professor" ?>

    </h2>

    <hr>

    <form action="salvar.php" method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= $id ?>">

        <div class="mb-3">

            <label class="form-label">

                Nome do Professor

            </label>

            <input
                type="text"
                name="nome"
                class="form-control"
                value="<?= htmlspecialchars($nome) ?>"
                required>

        </div>

        <button class="btn btn-success">

            💾 Salvar

        </button>

        <a href="index.php" class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

<?php

include("../../includes/footer.php");

?>