<?php
// Fails when the English and Polish string files drift apart. Author: WR
$en = require __DIR__ . '/../lang/en.php';
$pl = require __DIR__ . '/../lang/pl.php';
$keys = fn (array $a) => array_keys($a) === range(0, count($a) - 1) ? [] : array_merge(array_keys($a), ...array_map(fn ($k) => array_map(fn ($s) => "$k.$s", is_array($a[$k]) ? array_keys($a[$k]) : []), array_keys($a)));
$diff = array_merge(array_diff($keys($en), $keys($pl)), array_diff($keys($pl), $keys($en)));
if ($diff) {
    fwrite(STDERR, 'FAIL: keys differ: ' . implode(', ', $diff) . PHP_EOL);
    exit(1);
}
echo 'PASS: ' . count($keys($en)) . " keys in both languages\n";
