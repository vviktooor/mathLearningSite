<p>
  <img src=".github/readme/banner.svg" width="100%" alt="WR · mathLearningSite: Arithmetic practice site, EN and PL">
</p>

<p><img alt="English" src="https://img.shields.io/badge/English-20C9B0?style=flat-square">&nbsp;<a href="README.pl.md"><img alt="Polski" src="https://img.shields.io/badge/Polski-526077?style=flat-square"></a></p>

<p>
  <img alt="status: active" src="https://img.shields.io/badge/status-active-20C9B0?style=flat-square&labelColor=121B2B">
  <img alt="stack: PHP · MySQL · HTML · CSS" src="https://img.shields.io/badge/stack-PHP%20%C2%B7%20MySQL%20%C2%B7%20HTML%20%C2%B7%20CSS-3267E9?style=flat-square&labelColor=121B2B">
  <img alt="type: learning" src="https://img.shields.io/badge/type-learning-3267E9?style=flat-square&labelColor=121B2B">
  <img alt="license: public" src="https://img.shields.io/badge/license-public-526077?style=flat-square&labelColor=121B2B">
</p>

**Website for practising addition, subtraction, multiplication and division.**
Two modes: endless random tasks, or a test of ten tasks whose result is saved to a database.


<p align="center"><img alt="Preview: Math learning site on desktop" src="pc_version.PNG" width="85%"></p>

## What I practised

- **Four operations** with random and test modes.
- **English and Polish** versions (`en/`, `pl/`).
- **Responsive** layout for desktop and mobile.

## Quick start

Requirements: XAMPP (Apache + MySQL) or a similar PHP server.

```bash
git clone https://github.com/vviktooor/mathLearningSite.git
# copy the folder to xampp/htdocs, start Apache and MySQL
# import kalkulator.sql in phpMyAdmin, then open http://localhost/mathLearningSite
```

## Languages

This README is available in English (default) and [Polish](README.pl.md). The site has English (`index_en.html`) and Polish (`index.html`) versions.

## Repository layout

| Where | What |
|---|---|
| `index.html, index_en.html` | Start pages (PL, EN) |
| `pl/, en/` | Exercises per language |
| `kalkulator.sql` | Database for test results |
| `css/, img/` | Styles and images |

## Tests and quality

| Check | Result | Date |
|---|---|---|
| Tests | NOT VERIFIED (learning repository) | 2026-11-25 |

## License

No licence file: all rights reserved. Ask before reusing the code. Author: WR.

Found a bug or have an idea? [Open an issue](../../issues).
