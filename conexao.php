<?php

// Define o fuso horário do Brasil
date_default_timezone_set('America/Sao_Paulo');

try {

    $caminhoBanco = __DIR__ . "/banco/escola.db";

    $pdo = new PDO("sqlite:" . $caminhoBanco);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {

    die("Erro ao conectar com o banco: " . $erro->getMessage());
}
