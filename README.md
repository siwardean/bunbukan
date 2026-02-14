# Bunbukan Brussels (theme)

WordPress theme for **Bunbukan Brussels** — part of [Budo Club Berchem](https://bunbukan.eu), founded in 1977. Traditional Okinawan Martial Arts: Shitō-Ryū Karate & Ryūkyū Kobudō.

**Theme name:** Bunbukan Brussels  
**Text domain:** `bunbukan`  
**Author:** Budo Club Berchem  
**Author URI:** https://bunbukan.eu  
**Version:** 1.0.0  

---

## Description

A modern, responsive WordPress theme built for Bunbukan Brussels and focused on traditional Okinawan martial arts. The theme uses a bold, dark and red color scheme and custom typography (Naganoshi, Bebas Neue, Noto Sans JP) for a distinct, authentic style.

The theme is translation-ready (Japanese, English, French, Dutch), SEO-friendly, and supports custom content types with Pod and ACF plugins. It provides a custom front page with hero sections and SVG dividers, modular templates, and asset management helpers.

**Setup requirements:**
- WordPress (compatible version)
- PHP (compatible version)
- **Required plugins:**
  - [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/)  
    (for custom field management, used throughout various templates)
  - [Pods – Custom Content Types and Fields](https://pods.io/)  
    (for custom content types and fields. Note: Make sure Pods is triggered early—see `functions.php`)
- **Recommended plugins:**
  - [Polylang](https://wordpress.org/plugins/polylang/) or [WPML](https://wpml.org/)  
    (for multilingual support)
  - [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)  
    (for contact forms)
- Optional: If you wish to sync assets from a React build, place the React build output at `bunbukan-eu/public/`
- Make sure to activate the theme and import the provided `.pot` translation files for multilingual support.

> After activating, ensure both Pods and ACF are enabled and properly triggered. Refer to `functions.php` for the required hooks and usage details for custom fields and content.

See further customization details in the `inc/` and `template-parts/` folders.

---

## Structure

- **Templates:** `front-page.php`, `header.php`, `footer.php`, `index.php`, `sidebar.php`
- **Template parts:** `template-parts/` (e.g. cookie consent, content templates)
- **Assets:** `assets/js/`, `assets/images/`, `assets/fonts/`
- **Languages:** `languages/` — translation-ready with `.pot` and locale files for Japanese, English, French, Dutch
- **Inc:** `inc/pods-migration-data.php` — Pods custom data and migration helpers

---

## Features

- Custom front page with hero sections and SVG dividers
- Asset helper `bunbukan_asset_url()` and optional syncing from a React build (`bunbukan_sync_assets_from_react()` in `functions.php`) when `bunbukan-eu/public/images` exists
- **Advanced Custom Fields (ACF)** and **Pods** integrations for advanced and flexible custom content management (both plugins are required)
- Modular and easily extensible template structure

---

## Requirements

- WordPress (version required by your environment)
- PHP (version required by WordPress)
- **Plugins:**
  - Advanced Custom Fields (ACF) [**Required**]
  - Pods – Custom Content Types and Fields [**Required**]
  - Polylang or WPML [Optional]
  - Contact Form 7 [Optional]
- Optional: React build at `bunbukan-eu/public/` for asset sync

---

## License

**Proprietary — All rights reserved.** This theme is not open source. It is for use by Budo Club Berchem / Bunbukan only. No redistribution or reuse without permission.
