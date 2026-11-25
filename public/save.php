<?php
// Store a finished test with a prepared statement, then return to the page. Author: WR
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';

$lang = pick($_POST['lang'] ?? null, LANGS);
$op = pick($_POST['op'] ?? null, OPS);
$fields = array_map(fn ($key) => trim((string) ($_POST[$key] ?? '')), ['first_name', 'last_name', 'class']);
$points = filter_var($_POST['points'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 10]]);

$ok = $_SERVER['REQUEST_METHOD'] === 'POST' && !in_array('', $fields, true) && $points !== false
    && max(array_map('mb_strlen', $fields)) <= 60;
if ($ok) {
    try {
        db()->prepare('INSERT INTO results (first_name, last_name, class, points, operation) VALUES (?, ?, ?, ?, ?)')
            ->execute([...$fields, $points, $op]);
    } catch (PDOException $error) {
        error_log('save.php: ' . $error->getMessage());
        $ok = false;
    }
}
header('Location: index.php?' . http_build_query(['lang' => $lang, 'op' => $op, 'mode' => 'test', 'saved' => $ok ? 1 : 0]), true, 303);
