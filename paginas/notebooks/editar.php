<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$id = $_GET["id"] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM notebooks WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$notebook = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$notebook) {
    header("Location: index.php");
    exit;
}

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Editar Notebook</h2>

        <a href="index.php" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <hr>

    <form action="salvar.php" method="POST">

        <!-- ID do notebook -->
        <input
            type="hidden"
            name="id"
            value="<?= htmlspecialchars($notebook["id"]) ?>">

        <!-- Número -->
        <div class="mb-3">
            <label class="form-label">
                <strong>Número</strong>
            </label>

            <input
                type="text"
                class="form-control"
                value="<?= str_pad($notebook["numero"], 2, "0", STR_PAD_LEFT) ?>"
                readonly>
        </div>

        <!-- Status -->
        <div class="mb-3">
            <label for="status" class="form-label">
                <strong>Status</strong>
            </label>

            <select
                name="status"
                id="status"
                class="form-select"
                required>

                <option value="Disponível"
                    <?= $notebook["status"] == "Disponível" ? "selected" : "" ?>>
                    Disponível
                </option>

                <option value="Emprestado"
                    <?= $notebook["status"] == "Emprestado" ? "selected" : "" ?>>
                    Emprestado
                </option>

                <option value="Manutenção"
                    <?= $notebook["status"] == "Manutenção" ? "selected" : "" ?>>
                    Manutenção
                </option>

                <option value="Chamado Aberto"
                    <?= $notebook["status"] == "Chamado Aberto" ? "selected" : "" ?>>
                    Chamado Aberto
                </option>

            </select>
        </div>

        <!-- Observação -->
        <div class="mb-3">
            <label for="observacao" class="form-label">
                <strong>Observação</strong>
            </label>

            <textarea
                name="observacao"
                id="observacao"
                class="form-control"
                rows="4"
                placeholder="Digite uma observação sobre o notebook..."><?= htmlspecialchars($notebook["observacao"] ?? "") ?></textarea>
        </div>

        <!-- Botões -->
        <div class="d-flex gap-2">

            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg"></i>
                Salvar
            </button>

            <a href="index.php" class="btn btn-secondary">
                Cancelar
            </a>

        </div>

    </form>

</div>

<?php

include("../../includes/footer.php");

?>