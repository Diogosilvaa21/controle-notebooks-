<?php

include("../../includes/header.php");
include("../../includes/menu.php");
include("../../conexao.php");

// ============================================================
// BUSCAR PROFESSORES
// ============================================================

$professores = $pdo->query("
    SELECT id, nome
    FROM professores
    ORDER BY nome
")->fetchAll();

?>

<div class="container mt-4 mb-5">

    <!-- ===================================================== -->
    <!-- CABEÇALHO -->
    <!-- ===================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold text-primary mb-1">
                📋 Novo Empréstimo
            </h2>

            <p class="text-muted mb-0">
                Preencha os dados para registrar um novo empréstimo.
            </p>

        </div>

        <a href="index.php" class="btn btn-outline-secondary">
            ← Voltar
        </a>

    </div>


    <form
        action="salvar.php"
        method="POST"
        id="formEmprestimo">


        <!-- ===================================================== -->
        <!-- PROFESSOR -->
        <!-- ===================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    👨‍🏫 Professor
                </h5>

            </div>

            <div class="card-body">

                <label
                    class="form-label fw-bold"
                    for="professor_id">

                    Professor

                </label>

                <select
                    name="professor_id"
                    id="professor_id"
                    class="form-select"
                    required>

                    <option value="">
                        Selecione o professor...
                    </option>

                    <?php foreach ($professores as $professor): ?>

                        <option value="<?= $professor["id"] ?>">

                            <?= htmlspecialchars($professor["nome"]) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- CARRINHO -->
        <!-- ===================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    🛒 Carrinho
                </h5>

            </div>

            <div class="card-body">

                <label
                    class="form-label fw-bold"
                    for="carrinho_id">

                    Carrinho

                </label>

                <select
                    id="carrinho_id"
                    name="carrinho_id"
                    class="form-select"
                    required>

                    <option value="">
                        ⏳ Carregando carrinhos...
                    </option>

                </select>

                <div class="form-text">
                    Todos os carrinhos disponíveis aparecem aqui.
                    O tipo será identificado automaticamente.
                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- TIPO DE EMPRÉSTIMO -->
        <!-- ===================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    💻 Tipo de Empréstimo
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <!-- COMPLETO -->

                    <div class="col-md-6 mb-2">

                        <div
                            class="form-check border rounded p-3"
                            id="boxCompleto">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="tipo_visual"
                                id="completo"
                                value="COMPLETO"
                                checked
                                disabled>

                            <label
                                class="form-check-label fw-bold"
                                for="completo">

                                🛒 Carrinho Completo

                            </label>

                            <div class="small text-muted mt-1">

                                Seleciona automaticamente todos os
                                notebooks disponíveis do carrinho.

                            </div>

                        </div>

                    </div>


                    <!-- PARCIAL -->

                    <div class="col-md-6 mb-2">

                        <div
                            class="form-check border rounded p-3"
                            id="boxParcial">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="tipo_visual"
                                id="parcial"
                                value="PARCIAL"
                                disabled>

                            <label
                                class="form-check-label fw-bold"
                                for="parcial">

                                💻 Empréstimo Parcial

                            </label>

                            <div class="small text-muted mt-1">

                                Permite escolher os notebooks
                                individualmente.

                            </div>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- TIPO REAL ENVIADO PARA salvar.php -->
                <!-- ================================================= -->

                <input
                    type="hidden"
                    name="tipo"
                    id="tipo"
                    value="COMPLETO">

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- NOTEBOOKS -->
        <!-- ===================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div
                class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    💻 Notebooks
                </h5>

                <span
                    id="contadorNotebooks"
                    class="badge bg-light text-primary">

                    0 selecionados

                </span>

            </div>

            <div class="card-body">

                <div id="listaNotebooks">

                    <div class="alert alert-info mb-0">

                        🛒 Selecione um carrinho para visualizar
                        os notebooks.

                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- TURMA / AULA -->
        <!-- ===================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    📚 Informações da Aula
                </h5>

            </div>

            <div class="card-body">

                <label
                    class="form-label fw-bold"
                    for="aula">

                    Turma / Aula

                </label>

                <input
                    type="text"
                    name="aula"
                    id="aula"
                    class="form-control"
                    placeholder="Ex.: 2ºA"
                    required>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- OBSERVAÇÃO -->
        <!-- ===================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    📝 Observação
                </h5>

            </div>

            <div class="card-body">

                <label
                    class="form-label fw-bold"
                    for="observacao">

                    Observação do empréstimo

                </label>

                <textarea
                    name="observacao"
                    id="observacao"
                    class="form-control"
                    rows="4"
                    maxlength="500"
                    placeholder="Digite alguma observação sobre este empréstimo..."></textarea>

                <div class="d-flex justify-content-end">

                    <small
                        id="contadorCaracteres"
                        class="text-muted">

                        0 / 500

                    </small>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- RESUMO -->
        <!-- ===================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    📋 Resumo do Empréstimo
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <strong>👨‍🏫 Professor:</strong>

                        <div
                            id="resumoProfessor"
                            class="text-muted">

                            Não selecionado

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <strong>🛒 Carrinho:</strong>

                        <div
                            id="resumoCarrinho"
                            class="text-muted">

                            Não selecionado

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <strong>📚 Turma:</strong>

                        <div
                            id="resumoAula"
                            class="text-muted">

                            Não informada

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <strong>💻 Tipo:</strong>

                        <div
                            id="resumoTipo"
                            class="text-muted">

                            Carrinho Completo

                        </div>

                    </div>


                    <div class="col-12">

                        <strong>💻 Notebooks:</strong>

                        <div
                            id="resumoNotebooks"
                            class="text-muted mt-1">

                            Nenhum notebook selecionado.

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- BOTÕES -->
        <!-- ===================================================== -->

        <div class="d-flex justify-content-end gap-2">

            <a
                href="index.php"
                class="btn btn-secondary">

                Cancelar

            </a>

            <button
                type="submit"
                class="btn btn-success"
                id="btnRegistrar">

                ✓ Registrar Empréstimo

            </button>

        </div>

    </form>

</div>


<script>
    // ============================================================
    // ELEMENTOS
    // ============================================================

    const carrinho =
        document.getElementById("carrinho_id");

    const completo =
        document.getElementById("completo");

    const parcial =
        document.getElementById("parcial");

    const tipo =
        document.getElementById("tipo");

    const listaNotebooks =
        document.getElementById("listaNotebooks");

    const contadorNotebooks =
        document.getElementById("contadorNotebooks");

    const observacao =
        document.getElementById("observacao");

    const contadorCaracteres =
        document.getElementById("contadorCaracteres");

    const professor =
        document.getElementById("professor_id");

    const aula =
        document.getElementById("aula");

    const formEmprestimo =
        document.getElementById("formEmprestimo");


    // ============================================================
    // CARREGAR TODOS OS CARRINHOS
    // ============================================================

    function carregarCarrinhos() {

        carrinho.innerHTML =
            "<option value=''>" +
            "⏳ Carregando carrinhos..." +
            "</option>";


        listaNotebooks.innerHTML =
            "<div class='alert alert-info'>" +
            "⏳ Carregando carrinhos..." +
            "</div>";


        /*
         * Busca os dois tipos separadamente.
         *
         * Não precisamos conhecer a estrutura da tabela carrinhos.
         * O seu buscar_carrinhos.php já sabe fazer isso.
         */

        Promise.all([

                fetch("buscar_carrinhos.php?tipo=COMPLETO")
                .then(function(response) {

                    if (!response.ok) {

                        throw new Error(
                            "Erro ao buscar carrinhos completos."
                        );

                    }

                    return response.text();

                }),

                fetch("buscar_carrinhos.php?tipo=PARCIAL")
                .then(function(response) {

                    if (!response.ok) {

                        throw new Error(
                            "Erro ao buscar carrinhos parciais."
                        );

                    }

                    return response.text();

                })

            ])

            .then(function(resultados) {

                const htmlCompleto = resultados[0];

                const htmlParcial = resultados[1];


                // ====================================================
                // CRIAR TEMPORARIAMENTE OS HTMLs
                // ====================================================

                const tempCompleto =
                    document.createElement("select");

                tempCompleto.innerHTML =
                    htmlCompleto;


                const tempParcial =
                    document.createElement("select");

                tempParcial.innerHTML =
                    htmlParcial;


                // ====================================================
                // LIMPAR SELECT
                // ====================================================

                carrinho.innerHTML =
                    "<option value=''>" +
                    "Selecione o carrinho..." +
                    "</option>";


                // ====================================================
                // ADICIONAR CARRINHOS COMPLETOS
                // ====================================================

                Array.from(
                    tempCompleto.options
                ).forEach(function(option) {

                    // Ignora opção vazia
                    if (!option.value) {
                        return;
                    }


                    const novaOpcao =
                        document.createElement("option");


                    novaOpcao.value =
                        option.value;


                    novaOpcao.textContent =
                        option.textContent.trim();


                    novaOpcao.dataset.tipo =
                        "COMPLETO";


                    carrinho.appendChild(
                        novaOpcao
                    );

                });


                // ====================================================
                // ADICIONAR CARRINHOS PARCIAIS
                // ====================================================

                Array.from(
                    tempParcial.options
                ).forEach(function(option) {

                    // Ignora opção vazia
                    if (!option.value) {
                        return;
                    }


                    /*
                     * Evita duplicar um carrinho caso ele apareça
                     * nos dois resultados.
                     */

                    const jaExiste =
                        Array.from(
                            carrinho.options
                        ).some(function(opcaoExistente) {

                            return (
                                opcaoExistente.value ===
                                option.value
                            );

                        });


                    if (jaExiste) {
                        return;
                    }


                    const novaOpcao =
                        document.createElement("option");


                    novaOpcao.value =
                        option.value;


                    novaOpcao.textContent =
                        option.textContent.trim();


                    novaOpcao.dataset.tipo =
                        "PARCIAL";


                    carrinho.appendChild(
                        novaOpcao
                    );

                });


                // ====================================================
                // ADICIONAR IDENTIFICAÇÃO VISUAL
                // ====================================================

                Array.from(
                    carrinho.options
                ).forEach(function(option) {

                    if (!option.value) {
                        return;
                    }


                    const texto =
                        option.textContent.trim();


                    if (
                        option.dataset.tipo ===
                        "COMPLETO"
                    ) {

                        option.textContent =
                            "🛒 " +
                            texto +
                            " - COMPLETO";

                    } else {

                        option.textContent =
                            "💻 " +
                            texto +
                            " - PARCIAL";

                    }

                });


                listaNotebooks.innerHTML =
                    "<div class='alert alert-info'>" +
                    "🛒 Selecione um carrinho para visualizar " +
                    "os notebooks." +
                    "</div>";


                atualizarContador();

                atualizarResumo();

            })

            .catch(function(error) {

                console.error(error);


                carrinho.innerHTML =
                    "<option value=''>" +
                    "❌ Erro ao carregar carrinhos" +
                    "</option>";


                listaNotebooks.innerHTML =
                    "<div class='alert alert-danger'>" +
                    "❌ Não foi possível carregar os carrinhos." +
                    "<br><small>" +
                    error.message +
                    "</small>" +
                    "</div>";

            });

    }


    // ============================================================
    // IDENTIFICAR AUTOMATICAMENTE O TIPO
    // ============================================================

    function atualizarTipo() {

        const opcao =
            carrinho.options[
                carrinho.selectedIndex
            ];


        if (
            !carrinho.value ||
            !opcao
        ) {

            completo.checked = true;
            parcial.checked = false;

            tipo.value = "COMPLETO";

            return;

        }


        const tipoCarrinho =
            opcao.dataset.tipo;


        if (
            tipoCarrinho ===
            "PARCIAL"
        ) {

            completo.checked = false;
            parcial.checked = true;

            tipo.value = "PARCIAL";

        } else {

            completo.checked = true;
            parcial.checked = false;

            tipo.value = "COMPLETO";

        }


        atualizarResumo();

    }


    // ============================================================
    // QUANDO SELECIONAR UM CARRINHO
    // ============================================================

    carrinho.addEventListener(
        "change",
        function() {

            // Descobre se é completo ou parcial
            atualizarTipo();


            const id =
                this.value;


            atualizarResumo();


            // ====================================================
            // NENHUM CARRINHO
            // ====================================================

            if (id === "") {

                listaNotebooks.innerHTML =
                    "<div class='alert alert-info'>" +
                    "🛒 Selecione um carrinho." +
                    "</div>";


                atualizarContador();

                return;

            }


            // ====================================================
            // CARREGANDO NOTEBOOKS
            // ====================================================

            listaNotebooks.innerHTML =
                "<div class='alert alert-info'>" +
                "⏳ Carregando notebooks..." +
                "</div>";


            fetch(
                    "buscar_notebooks.php?carrinho_id=" +
                    encodeURIComponent(id)
                )

                .then(function(response) {

                    if (!response.ok) {

                        throw new Error(
                            "Erro ao buscar notebooks."
                        );

                    }

                    return response.text();

                })

                .then(function(html) {

                    listaNotebooks.innerHTML =
                        html;


                    ativarEventosNotebooks();


                    atualizarContador();


                    atualizarResumo();

                })

                .catch(function(error) {

                    console.error(error);


                    listaNotebooks.innerHTML =
                        "<div class='alert alert-danger'>" +
                        "❌ Não foi possível carregar os notebooks." +
                        "</div>";

                });

        }
    );


    // ============================================================
    // ATIVAR EVENTOS DOS NOTEBOOKS
    // ============================================================

    function ativarEventosNotebooks() {

        const selecionarTodos =
            document.getElementById(
                "selecionarTodos"
            );


        // ========================================================
        // SELECIONAR TODOS
        // ========================================================

        if (selecionarTodos) {

            selecionarTodos.addEventListener(
                "change",
                function() {

                    const marcado =
                        this.checked;


                    document
                        .querySelectorAll(
                            "input[name='notebooks[]']:not(:disabled)"
                        )
                        .forEach(function(cb) {

                            cb.checked =
                                marcado;

                        });


                    atualizarContador();

                    atualizarResumo();

                }
            );

        }


        // ========================================================
        // CHECKBOXES INDIVIDUAIS
        // ========================================================

        document
            .querySelectorAll(
                "input[name='notebooks[]']"
            )
            .forEach(function(cb) {

                cb.addEventListener(
                    "change",
                    function() {

                        atualizarContador();

                        atualizarResumo();

                    }
                );

            });


        // ========================================================
        // CARRINHO COMPLETO
        // ========================================================

        if (
            tipo.value ===
            "COMPLETO"
        ) {

            document
                .querySelectorAll(
                    "input[name='notebooks[]']:not(:disabled)"
                )
                .forEach(function(cb) {

                    cb.checked = true;

                });


            atualizarContador();

            atualizarResumo();

        }

    }


    // ============================================================
    // CONTADOR DE NOTEBOOKS
    // ============================================================

    function atualizarContador() {

        const selecionados =
            document.querySelectorAll(
                "input[name='notebooks[]']:checked"
            ).length;


        contadorNotebooks.textContent =
            selecionados +
            (
                selecionados === 1 ?
                " selecionado" :
                " selecionados"
            );

    }


    // ============================================================
    // CONTADOR DA OBSERVAÇÃO
    // ============================================================

    observacao.addEventListener(
        "input",
        function() {

            contadorCaracteres.textContent =
                this.value.length +
                " / 500";

        }
    );


    // ============================================================
    // ATUALIZAR RESUMO
    // ============================================================

    function atualizarResumo() {

        // ========================================================
        // PROFESSOR
        // ========================================================

        const professorTexto =
            professor.options[
                professor.selectedIndex
            ]?.text || "";


        document.getElementById(
                "resumoProfessor"
            ).textContent =

            professor.value ?
            professorTexto.trim() :
            "Não selecionado";


        // ========================================================
        // CARRINHO
        // ========================================================

        const carrinhoTexto =
            carrinho.options[
                carrinho.selectedIndex
            ]?.text || "";


        document.getElementById(
                "resumoCarrinho"
            ).textContent =

            carrinho.value ?
            carrinhoTexto.trim() :
            "Não selecionado";


        // ========================================================
        // AULA
        // ========================================================

        const aulaTexto =
            aula.value.trim();


        document.getElementById(
                "resumoAula"
            ).textContent =

            aulaTexto ?
            aulaTexto :
            "Não informada";


        // ========================================================
        // TIPO
        // ========================================================

        document.getElementById(
                "resumoTipo"
            ).textContent =

            tipo.value === "PARCIAL"

            ?
            "💻 Empréstimo Parcial"

            :
            "🛒 Carrinho Completo";


        // ========================================================
        // NOTEBOOKS
        // ========================================================

        const selecionados =
            document.querySelectorAll(
                "input[name='notebooks[]']:checked"
            );


        const resumo =
            document.getElementById(
                "resumoNotebooks"
            );


        if (
            selecionados.length ===
            0
        ) {

            resumo.textContent =
                "Nenhum notebook selecionado.";

            return;

        }


        const notebooks = [];


        selecionados.forEach(
            function(cb) {

                const label =
                    document.querySelector(
                        "label[for='" +
                        cb.id +
                        "']"
                    );


                if (label) {

                    notebooks.push(
                        label.textContent.trim()
                    );

                } else {

                    notebooks.push(
                        cb.value
                    );

                }

            }
        );


        resumo.textContent =
            notebooks.join(", ");

    }


    // ============================================================
    // PROFESSOR
    // ============================================================

    professor.addEventListener(
        "change",
        atualizarResumo
    );


    // ============================================================
    // AULA
    // ============================================================

    aula.addEventListener(
        "input",
        atualizarResumo
    );


    // ============================================================
    // CONFIRMAÇÃO ANTES DE REGISTRAR
    // ============================================================

    formEmprestimo.addEventListener(
        "submit",
        function(event) {

            // ====================================================
            // PROFESSOR
            // ====================================================

            if (
                professor.value === ""
            ) {

                event.preventDefault();

                alert(
                    "Selecione um professor."
                );

                professor.focus();

                return;

            }


            // ====================================================
            // CARRINHO
            // ====================================================

            if (
                carrinho.value === ""
            ) {

                event.preventDefault();

                alert(
                    "Selecione um carrinho."
                );

                carrinho.focus();

                return;

            }


            // ====================================================
            // AULA
            // ====================================================

            if (
                aula.value.trim() === ""
            ) {

                event.preventDefault();

                alert(
                    "Informe a turma/aula."
                );

                aula.focus();

                return;

            }


            // ====================================================
            // NOTEBOOKS
            // ====================================================

            const quantidade =
                document.querySelectorAll(
                    "input[name='notebooks[]']:checked"
                ).length;


            if (
                quantidade === 0
            ) {

                event.preventDefault();

                alert(
                    "Selecione pelo menos um notebook."
                );

                return;

            }


            // ====================================================
            // CONFIRMAÇÃO
            // ====================================================

            const professorTexto =
                professor.options[
                    professor.selectedIndex
                ].text.trim();


            const carrinhoTexto =
                carrinho.options[
                    carrinho.selectedIndex
                ].text.trim();





            const confirmar =
                confirm(

                    "Deseja registrar este empréstimo?\n\n" +

                    "Professor: " +
                    professorTexto +

                    "\n" +

                    "Carrinho: " +
                    carrinhoTexto +

                    "\n" +

                    "Tipo: " +
                    tipoTexto +

                    "\n" +

                    "Turma/Aula: " +
                    aula.value.trim() +

                    "\n" +

                    "Notebooks selecionados: " +
                    quantidade +

                    "\n\n" +

                    "Clique em OK para registrar."

                );


            if (!confirmar) {

                event.preventDefault();

            }

        }
    );


    // ============================================================
    // INICIALIZAÇÃO
    // ============================================================

    carregarCarrinhos();

    atualizarResumo();
</script>


<?php

include("../../includes/footer.php");

?>