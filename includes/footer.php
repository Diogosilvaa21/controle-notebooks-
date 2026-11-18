<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const btnTema =
            document.getElementById("btnTema");


        // ========================================================
        // RECUPERAR TEMA SALVO
        // ========================================================

        const temaSalvo =
            localStorage.getItem("tema");


        if (temaSalvo === "escuro") {

            document.body.classList.add("modo-escuro");

        }


        atualizarBotao();


        // ========================================================
        // BOTÃO
        // ========================================================

        if (btnTema) {

            btnTema.addEventListener(
                "click",
                function() {

                    document.body.classList.toggle(
                        "modo-escuro"
                    );


                    const escuro =
                        document.body.classList.contains(
                            "modo-escuro"
                        );


                    if (escuro) {

                        localStorage.setItem(
                            "tema",
                            "escuro"
                        );

                    } else {

                        localStorage.setItem(
                            "tema",
                            "claro"
                        );

                    }


                    atualizarBotao();

                }
            );

        }


        // ========================================================
        // ATUALIZAR BOTÃO
        // ========================================================

        function atualizarBotao() {

            if (!btnTema) {
                return;
            }


            const escuro =
                document.body.classList.contains(
                    "modo-escuro"
                );


            if (escuro) {

                btnTema.innerHTML =
                    "☀️ Modo claro";

                btnTema.classList.remove(
                    "btn-outline-secondary"
                );

                btnTema.classList.add(
                    "btn-outline-warning"
                );

            } else {

                btnTema.innerHTML =
                    "🌙 Modo escuro";

                btnTema.classList.remove(
                    "btn-outline-warning"
                );

                btnTema.classList.add(
                    "btn-outline-secondary"
                );

            }

        }

    });
</script>

</html>