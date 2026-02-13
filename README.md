# Bunbukan Brussels (theme)

WordPress theme for **Bunbukan Brussels** — part of [Budo Club Berchem](https://bunbukan.eu), founded in 1977. Traditional Okinawan Martial Arts: Shitō-Ryū Karate & Ryūkyū Kobudō.

**Theme name:** Bunbukan Brussels  
**Text domain:** `bunbukan`  
**Author:** Budo Club Berchem  
**Author URI:** https://bunbukan.eu  
**Version:** 1.0.0  

---

## Description

Modern, responsive theme inspired by [kabuki.es](https://kabuki.es), with a dark/red palette and custom typography (Naganoshi, Bebas Neue, Noto Sans JP). Structure based on Nutriflow, adapted for martial-arts content.

---

## Structure

- **Templates:** `front-page.php`, `header.php`, `footer.php`, `index.php`, `sidebar.php`
- **Template parts:** `template-parts/` (e.g. cookie consent, content templates)
- **Assets:** `assets/js/`, `assets/images/`, `assets/fonts/`
- **Languages:** `languages/` — translation-ready with `.pot` and locale files for Japanese, English, French, Dutch
- **Inc:** `inc/pods-migration-data.php` — Pods custom data

---

## Features

- Custom front page with hero, sections, and SVG dividers
- Asset helper `bunbukan_asset_url()` and optional sync from a React build (`bunbukan_sync_assets_from_react()` in `functions.php`) when `bunbukan-eu/public/images` exists
- **Pods** integration for custom content (requires Pods plugin)

---

## Requirements

- WordPress (version supported by your environment)
- PHP (version required by WordPress)  
- Optional: **Pods** plugin, React build at `bunbukan-eu/public/` for asset sync

---

## License

**Proprietary — All rights reserved.** This theme is not open source. It is for use by Budo Club Berchem / Bunbukan only. No redistribution or reuse without permission.
