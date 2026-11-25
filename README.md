<p>
  <img src=".github/readme/banner.svg" width="100%" alt="WR · mathLearningSite: Arithmetic practice site, EN and PL">
</p>

<p><img alt="English" src="https://img.shields.io/badge/English-20C9B0?style=flat-square">&nbsp;<a href="README.pl.md"><img alt="Polski" src="https://img.shields.io/badge/Polski-526077?style=flat-square"></a></p>

<p>
  <img alt="status: active" src="https://img.shields.io/badge/status-active-20C9B0?style=flat-square&labelColor=121B2B">
  <img alt="stack: PHP · JavaScript · MariaDB" src="https://img.shields.io/badge/stack-PHP%20%C2%B7%20JavaScript%20%C2%B7%20MariaDB-3267E9?style=flat-square&labelColor=121B2B">
  <img alt="type: learning" src="https://img.shields.io/badge/type-learning-3267E9?style=flat-square&labelColor=121B2B">
  <a href="../../actions/workflows/ci.yml"><img alt="CI" src="../../actions/workflows/ci.yml/badge.svg"></a>
  <img alt="license: public" src="https://img.shields.io/badge/license-public-526077?style=flat-square&labelColor=121B2B">
</p>

**Website for practising addition, subtraction, multiplication and division.**
Two modes: endless practice with instant feedback, or a 10-question test whose score is saved to the database with the pupil's name and class.

<p align="center"><img alt="Preview: Multiplication practice with a correct answer" src="docs/screenshots/learn.en.png" width="85%"></p>

## Features

- **Four operations**: subtraction never goes below zero, division always has a whole result.
- **Practice and Test** modes; the test saves the score with a prepared statement (no SQL injection).
- **English and Polish** on one page (`?lang=en|pl`, browser language by default).
- **WR look**: logo, ink and signal colours, Poppins and JetBrains Mono; works on phones.

## Quick start

Requirements: PHP 8.1+ with `pdo_mysql`, MariaDB or MySQL.

```bash
git clone https://github.com/vviktooor/mathLearningSite.git
cd mathLearningSite
mysql -e 'CREATE DATABASE math_learning' && mysql math_learning < db/schema.sql
DB_USER=... DB_PASSWORD=... php -S localhost:8080 -t public
```

Open http://localhost:8080. On XAMPP point the virtual host at `public/`.

## Configuration

Database settings come from environment variables (see `.env.example`): `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`.

## Test mode

After ten questions the pupil sees the score and saves it; on a phone the layout stacks:

<p align="center"><img alt="Finished test with the save form" src="docs/screenshots/test-finished.en.png" width="70%">&nbsp;<img alt="Mobile layout" src="docs/screenshots/mobile.en.png" width="24%"></p>

## Languages

This README is available in English (default) and [Polish](README.pl.md). Interface: English by default (or the browser language), Polish via the EN/PL switch or `?lang=pl`. Strings live in `lang/en.php` and `lang/pl.php`.

## Repository layout

| Where | What |
|---|---|
| `public/index.php` | The only page: language, operation and mode from the URL |
| `public/save.php` | Saves a finished test (prepared statement) |
| `public/assets/` | `quiz.js` (logic), `app.js` (page), `style.css`, WR logo |
| `lang/` | `en.php` and `pl.php` |
| `db/schema.sql` | Results table |
| `tests/` | Node tests for the quiz logic, PHP check for language parity |
| `docs/screenshots/` | Screenshots from the running site |

## Tests and quality

```bash
npm test
php tests/lang_test.php
for f in public/*.php lang/*.php; do php -l "$f"; done
```

| Check | Result | Date |
|---|---|---|
| Tests | PASS (3/3 Node, 26/26 language keys) | 2026-11-25 |
| php -l | PASS | 2026-11-25 |
| Screenshots | PASS (PHP 8.3 + MariaDB, test saved to DB, EN and PL, desktop and phone) | 2026-11-25 |

## License

No licence file: all rights reserved. Ask before reusing the code. Author: WR.

Found a bug or have an idea? [Open an issue](../../issues).
