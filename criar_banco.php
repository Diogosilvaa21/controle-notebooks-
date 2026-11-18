<?php

require 'conexao.php';

echo "<h1>Instalando Controle de Notebooks Escolar</h1>";

$pdo->exec("
CREATE TABLE IF NOT EXISTS professores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL
);
");

$pdo->exec("
CREATE TABLE IF NOT EXISTS carrinhos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cor TEXT NOT NULL UNIQUE,
    capacidade INTEGER DEFAULT 40
);
");

$pdo->exec("
CREATE TABLE IF NOT EXISTS notebooks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero INTEGER NOT NULL,
    carrinho_id INTEGER NOT NULL,
    status TEXT DEFAULT 'Disponível',
    observacao TEXT,
    FOREIGN KEY(carrinho_id) REFERENCES carrinhos(id)
);
");

$pdo->exec("
CREATE TABLE IF NOT EXISTS emprestimos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    professor_id INTEGER NOT NULL,
    tipo TEXT NOT NULL,
    aula TEXT NOT NULL,
    data DATE NOT NULL,
    hora_retirada TIME NOT NULL,
    hora_devolucao TIME,
    status TEXT DEFAULT 'ABERTO',
    observacao TEXT,
    FOREIGN KEY(professor_id) REFERENCES professores(id)
);
");

$pdo->exec("
CREATE TABLE IF NOT EXISTS itens_emprestimo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    emprestimo_id INTEGER NOT NULL,
    notebook_id INTEGER NOT NULL,
    devolvido INTEGER DEFAULT 0,
    data_devolucao DATETIME,
    observacao TEXT,
    FOREIGN KEY(emprestimo_id) REFERENCES emprestimos(id),
    FOREIGN KEY(notebook_id) REFERENCES notebooks(id)
);
");

echo "<hr>";

echo "✅ Banco criado.<br>";
echo "✅ Professores criada.<br>";
echo "✅ Carrinhos criada.<br>";
echo "✅ Notebooks criada.<br>";
echo "✅ Empréstimos criada.<br>";
echo "✅ Itens do empréstimo criada.<br>";

echo "<hr>";
echo "<h2>Sistema instalado com sucesso!</h2>";

?>