<?php
// Shared helpers: language choice, escaping, database connection. Author: WR
declare(strict_types=1);

const LANGS = ['en', 'pl'];
const OPS = ['add', 'sub', 'mul', 'div'];
const MODES = ['learn', 'test'];

function pick(?string $value, array $allowed): string
{
    return in_array($value, $allowed, true) ? $value : $allowed[0];
}

function lang(): string
{
    if (isset($_GET['lang'])) {
        return pick($_GET['lang'], LANGS);
    }
    $header = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2));
    return pick($header, LANGS);
}

function strings(string $lang): array
{
    return require __DIR__ . "/../lang/{$lang}.php";
}

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function db(): PDO
{
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST') ?: 'localhost', getenv('DB_NAME') ?: 'math_learning');
    return new PDO($dsn, getenv('DB_USER') ?: 'math', getenv('DB_PASSWORD') ?: '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
