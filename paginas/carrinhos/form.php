<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$id = "";
$cor = "";

// Se estiver editando
if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "SELECT * FROM carrinhos WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$id]);

    $carrinho = $stmt->fetch();

    if ($carrinho) {

        $cor = $carrinho["cor"];
    }
}

?>

<div class="container mt-4">

    <h2>

        <?= $id ? "✏️ Editar Carrinho" : "➕ Novo Carrinho" ?>

    </h2>

    <hr>

    <form action="salvar.php" method="POST">

        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="mb-3">

            <label class="form-label">

                Cor do Carrinho

            </label>

            <select name="cor" class="form-select" required>

                <option value="">Selecione...</option>

                <?php
                $cores = [
                    "Amarelo",
                    "Azul",
                    "Branco",
                    "Cinza",
                    "Laranja",
                    "Preto",
                    "Rosa",
                    "Verde",
                    "Vermelho"
                ];
                foreach ($cores as $c) {

                    $selected = ($cor == $c) ? "selected" : "";

                    echo "<option value='$c' $selected>$c</option>";
                }

                ?>

            </select>

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