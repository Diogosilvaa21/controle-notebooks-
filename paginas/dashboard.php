<?php

include("../includes/header.php");
include("../includes/menu.php");
include("../conexao.php");

// Professores
$totalProfessores = $pdo->query("SELECT COUNT(*) FROM professores")->fetchColumn();

// Carrinhos
$totalCarrinhos = $pdo->query("SELECT COUNT(*) FROM carrinhos")->fetchColumn();

// Notebooks
$totalNotebooks = $pdo->query("SELECT COUNT(*) FROM notebooks")->fetchColumn();

// Disponíveis
$disponiveis = $pdo->query("
SELECT COUNT(*)
FROM notebooks
WHERE status='Disponível'
")->fetchColumn();

// Emprestados
$emprestados = $pdo->query("
SELECT COUNT(*)
FROM notebooks
WHERE status='Emprestado'
")->fetchColumn();

// Manutenção
$manutencao = $pdo->query("
SELECT COUNT(*)
FROM notebooks
WHERE status='Manutenção'
")->fetchColumn();

// Empréstimos Abertos
$abertos = $pdo->query("
SELECT COUNT(*)
FROM emprestimos
WHERE status='ABERTO'
")->fetchColumn();

// Devolução Parcial
$parciais = $pdo->query("
SELECT COUNT(*)
FROM emprestimos
WHERE status='DEVOLUÇÃO PARCIAL'
")->fetchColumn();

?>

<div class="container mt-4">

    <div class="hero mb-4">
        <h2>👋 Bem-vindo ao Controle de Notebooks</h2>
        <p class="mb-0">
            Hoje é <?= date('d/m/Y') ?> | Utilize o painel para acompanhar os equipamentos e empréstimos.
        </p>
    </div>

    <?php if ($manutencao > 5): ?>
        <div class="alert alert-warning">
            ⚠ Existem <?= $manutencao ?> notebooks aguardando manutenção.
        </div>
    <?php endif; ?>

    <h2 class="mb-4">📊 Dashboard</h2>

    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="card card-professores"
                onclick="window.location='professores/index.php'">
                <div class="card-body text-center">
                    <h5>👨‍🏫 Professores</h5>
                    <h2 class="numero"><?= $totalProfessores ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-carrinhos"
                onclick="window.location='../carrinhos/index.php'">
                <div class="card-body text-center">
                    <h5>🛒 Carrinhos</h5>
                    <h2 class="numero"><?= $totalCarrinhos ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-notebooks"
                onclick="window.location='notebooks/index.php'">
                <div class="card-body text-center">
                    <h5>💻 Notebooks</h5>
                    <h2 class="numero"><?= $totalNotebooks ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-emprestados"
                onclick="window.location='emprestimos/index.php'">
                <div class="card-body text-center">
                    <h5>🔴 Emprestados</h5>
                    <h2 class="numero"><?= $emprestados ?></h2>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="card card-disponiveis"
                onclick="window.location='notebooks/index.php'">
                <div class="card-body text-center">
                    <h5>🟢 Disponíveis</h5>
                    <h2 class="numero"><?= $disponiveis ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-manutencao"
                onclick="window.location='notebooks/manutencao.php'">
                <div class="card-body text-center">
                    <h5>🟠 Manutenção</h5>
                    <h2 class="numero"><?= $manutencao ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-abertos"
                onclick="window.location='emprestimos/index.php'">
                <div class="card-body text-center">
                    <h5>📋 Empréstimos Abertos</h5>
                    <h2 class="numero"><?= $abertos ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card card-parciais"
                onclick="window.location='devolucao.php'">
                <div class="card-body text-center">
                    <h5>🟡 Devolução Parcial</h5>
                    <h2 class="numero"><?= $parciais ?></h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h4 class="mb-4">📈 Situação dos Notebooks</h4>
            <canvas id="graficoStatus"></canvas>
        </div>
    </div>

</div>

https://cdn.jsdelivr.net/npm/chart.jsscript>

<script>
    document.querySelectorAll('.numero').forEach(el => {

        let valorFinal = parseInt(el.innerText);
        let atual = 0;

        const timer = setInterval(() => {

            atual += Math.ceil(valorFinal / 40);

            if (atual >= valorFinal) {
                atual = valorFinal;
                clearInterval(timer);
            }

            el.innerText = atual;

        }, 30);

    });

    new Chart(document.getElementById('graficoStatus'), {

        type: 'doughnut',

        data: {

            labels: [
                'Disponíveis',
                'Emprestados',
                'Manutenção'
            ],

            datasets: [{
                data: [
                    <?= $disponiveis ?>,
                    <?= $emprestados ?>,
                    <?= $manutencao ?>
                ],
                backgroundColor: [
                    '#22c55e',
                    '#ef4444',
                    '#f97316'
                ]
            }]
        }

    });
</script>

<?php
include("../includes/footer.php");
?>