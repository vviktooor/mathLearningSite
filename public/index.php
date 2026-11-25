<?php
// One page for every language, operation and mode. Author: WR
declare(strict_types=1);
require __DIR__ . '/bootstrap.php';

$lang = lang();
$op = pick($_GET['op'] ?? null, OPS);
$mode = pick($_GET['mode'] ?? null, MODES);
$t = strings($lang);
$link = fn (array $change) => 'index.php?' . http_build_query(array_merge(['lang' => $lang, 'op' => $op, 'mode' => $mode], $change));
$js = ['correct', 'wrong', 'empty', 'progress', 'score'];
?>
<!doctype html>
<html lang="<?= e($t['html_lang']) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($t['title']) ?> · WR</title>
  <link rel="icon" href="assets/favicon.svg" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=JetBrains+Mono:wght@500;700&display=swap">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header class="top">
    <a class="brand" href="<?= e($link(['op' => 'add', 'mode' => 'learn'])) ?>">
      <img src="assets/wr-logo.svg" alt="WR" width="62" height="32"><span><?= e($t['title']) ?></span>
    </a>
    <nav class="lang" aria-label="Language">
      <?php foreach (LANGS as $code): ?>
        <a href="<?= e($link(['lang' => $code])) ?>" <?= $code === $lang ? 'aria-current="true"' : '' ?>><?= strtoupper($code) ?></a>
      <?php endforeach ?>
    </nav>
  </header>

  <main class="wrap">
    <p class="tagline"><?= e($t['tagline']) ?></p>
    <nav class="tabs" aria-label="Operation">
      <?php foreach (OPS as $code): ?>
        <a href="<?= e($link(['op' => $code])) ?>" <?= $code === $op ? 'aria-current="true"' : '' ?>><?= e($t['ops'][$code]) ?></a>
      <?php endforeach ?>
    </nav>
    <nav class="modes" aria-label="Mode">
      <?php foreach (MODES as $code): ?>
        <a href="<?= e($link(['mode' => $code])) ?>" <?= $code === $mode ? 'aria-current="true"' : '' ?>><?= e($t['modes'][$code]) ?></a>
      <?php endforeach ?>
    </nav>

    <section class="card" data-op="<?= e($op) ?>" data-mode="<?= e($mode) ?>">
      <?php if (isset($_GET['saved'])): ?>
        <p class="feedback <?= $_GET['saved'] === '1' ? 'correct' : 'wrong' ?>"><?= e($_GET['saved'] === '1' ? $t['saved'] : $t['save_error']) ?></p>
      <?php endif ?>
      <?php if ($mode === 'test'): ?><p class="progress" id="progress"></p><?php endif ?>
      <form id="form" class="task" autocomplete="off">
        <span class="num" id="left"></span><span class="sign" id="sign"></span><span class="num" id="right"></span>
        <span class="sign">=</span>
        <label class="sr-only" for="answer"><?= e($t['answer']) ?></label>
        <input id="answer" class="num" inputmode="numeric" placeholder="?">
        <button type="submit"><?= e($t['check']) ?></button>
      </form>
      <p class="feedback" id="feedback" aria-live="polite"></p>

      <form id="finish" class="finish" method="post" action="save.php" hidden>
        <p class="score" id="score"></p>
        <input type="hidden" name="lang" value="<?= e($lang) ?>">
        <input type="hidden" name="op" value="<?= e($op) ?>">
        <input type="hidden" name="points" id="points">
        <?php foreach (['first_name', 'last_name', 'class'] as $field): ?>
          <label><?= e($t[$field]) ?><input name="<?= $field ?>" maxlength="60" required></label>
        <?php endforeach ?>
        <div class="row"><button type="submit"><?= e($t['save']) ?></button><a class="ghost" href="<?= e($link([])) ?>"><?= e($t['again']) ?></a></div>
      </form>
    </section>
  </main>

  <footer class="foot"><?= e($t['footer']) ?></footer>
  <script type="application/json" id="i18n"><?= json_encode(array_intersect_key($t, array_flip($js)), JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) ?></script>
  <script type="module" src="assets/app.js"></script>
</body>
</html>
