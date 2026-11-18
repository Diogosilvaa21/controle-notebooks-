<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min
<meta charset=" UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Controle de Notebooks Escolar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/estilo.css">
    <style>
        /* ============================================================
   MODO ESCURO GLOBAL
   ============================================================ */

        body.modo-escuro {

            background-color: #121212 !important;

            color: #e9ecef !important;
        }


        /* Textos */

        body.modo-escuro .text-dark {

            color: #f8f9fa !important;
        }

        body.modo-escuro .text-muted {

            color: #adb5bd !important;
        }


        /* Cards */

        body.modo-escuro .card {

            background-color: #1e1e1e !important;

            color: #e9ecef !important;

            border-color: #343a40 !important;
        }


        /* Cabeçalho dos cards */

        body.modo-escuro .card-header {

            background-color: #252525 !important;

            color: #ffffff !important;

            border-color: #343a40 !important;
        }


        /* Tabelas */

        body.modo-escuro .table {

            color: #e9ecef !important;

            --bs-table-bg: #1e1e1e;

            --bs-table-color: #e9ecef;

            --bs-table-border-color: #343a40;
        }

        body.modo-escuro .table-light {

            --bs-table-bg: #2b2b2b !important;

            --bs-table-color: #ffffff !important;

            background-color: #2b2b2b !important;

            color: #ffffff !important;
        }


        /* Inputs */

        body.modo-escuro .form-control,
        body.modo-escuro .form-select {

            background-color: #252525 !important;

            color: #ffffff !important;

            border-color: #495057 !important;
        }

        body.modo-escuro .form-control::placeholder {

            color: #999 !important;
        }

        body.modo-escuro .form-select option {

            background-color: #252525;

            color: #ffffff;
        }


        /* Labels */

        body.modo-escuro .form-label {

            color: #e9ecef !important;
        }


        /* HR */

        body.modo-escuro hr {

            color: #6c757d;

            opacity: .4;
        }


        /* Links */

        body.modo-escuro a {

            color: #6ea8fe;
        }


        /* Navbar */

        body.modo-escuro .navbar {

            background-color: #1a1a1a !important;

            border-bottom: 1px solid #343a40;
        }


        /* Dropdown */

        body.modo-escuro .dropdown-menu {

            background-color: #252525;

            border-color: #343a40;
        }

        body.modo-escuro .dropdown-item {

            color: #ffffff;
        }

        body.modo-escuro .dropdown-item:hover {

            background-color: #343a40;

            color: #ffffff;
        }


        /* Botões outline */

        body.modo-escuro .btn-outline-secondary {

            color: #dee2e6;

            border-color: #6c757d;
        }


        /* Modal */

        body.modo-escuro .modal-content {

            background-color: #1e1e1e;

            color: #ffffff;

            border-color: #343a40;
        }


        /* Alertas */

        body.modo-escuro .alert {

            border-color: #495057;
        }


        /* ============================================================
   TRANSIÇÃO
   ============================================================ */

        body {

            transition:
                background-color .2s ease,
                color .2s ease;
        }

        .card,
        .form-control,
        .form-select,
        .navbar,
        .table {

            transition:
                background-color .2s ease,
                color .2s ease,
                border-color .2s ease;
        }
    </style>
</head>

<body>