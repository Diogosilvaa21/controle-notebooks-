<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

$id = $_GET["id"];

// Busca dados do empréstimo
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

WHERE e.id=?

GROUP BY e.id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

$emprestimo = $stmt->fetch();


// Busca os notebooks

$sql = "
SELECT
ie.id,
n.numero,
n.status,
ie.devolvido

FROM itens_emprestimo ie

INNER JOIN notebooks n
ON n.id=ie.notebook_id

WHERE ie.emprestimo_id=?

ORDER BY n.numero
";

$stmt = $pdo->prepare($sql);

$stmt->execute([$id]);

$notebooks = $stmt->fetchAll();

?>

<div class="container mt-4">

    <h2>📦 Devolução de Empréstimo</h2>

    <hr>

    <div class="card shadow">

        <div class="card-body">

            <p><strong>Professor:</strong> <?= $emprestimo["nome"] ?></p>

            <p><strong>Carrinho:</strong> <?= $emprestimo["cor"] ?></p>

            <p><strong>Turma:</strong> <?= $emprestimo["aula"] ?></p>

        </div>

    </div>

    <br>

    <form action="finalizar.php" method="POST">

        <input type="hidden" name="emprestimo_id" value="<?= $id ?>">

        <div class="form-check mb-3">

            <input
                class="form-check-input"
                type="checkbox"
                id="todos"
                checked>

            <label class="form-check-label">

                <strong>Selecionar Todos</strong>

            </label>

        </div>

        <hr>
        <?php foreach ($notebooks as $n) { ?>

            <div class="form-check">

                <input
                    class="form-check-input notebook"
                    type="checkbox"
                    name="notebooks[]"
                    value="<?= $n["id"] ?>"
                    <?= $n["devolvido"] == 0 ? "checked" : "disabled" ?>>

                <label class="form-check-label">

                    Notebook <?= str_pad($n["numero"], 2, "0", STR_PAD_LEFT) ?>

                    <?php if ($n["devolvido"] == 1) { ?>

                        <span class="badge bg-success">
                            ✅ Devolvido
                        </span>

                    <?php } else { ?>

                        <span class="badge bg-danger">
                            🔴 Pendente
                        </span>

                    <?php } ?>

                </label>

            </div>

        <?php } ?>

        <hr>

        <p>

            Selecionados:

            <strong>

                <span id="contador">

                    <?= count($notebooks) ?>

                </span>

                /

                <?= count($notebooks) ?>

            </strong>

        </p>

        <div class="mb-3">

            <label>

                Observação da devolução

            </label>

            <textarea
                name="observacao"
                class="form-control"
                rows="3"></textarea>

        </div>

        <button class="btn btn-success">

            ✅ Finalizar Devolução

        </button>

    </form>

</div>

<script>
    const todos = document.getElementById("todos");

    const notebooks = document.querySelectorAll(".notebook");

    const contador = document.getElementById("contador");

    todos.addEventListener("change", () => {

        notebooks.forEach(n => {

            n.checked = todos.checked;

        });

        atualizar();

    });

    notebooks.forEach(n => {

        if (!n.disabled) {
            n.checked = todos.checked;
        }

    });

    function atualizar() {

        let total = 0;

        notebooks.forEach(n => {

            if (!n.disabled && n.checked) {
                total++;
            }

        });

        contador.innerHTML = total;

    }
</script>

<?php

include("../../includes/footer.php");

?>