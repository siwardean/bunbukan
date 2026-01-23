# Installation Guide - Bunbukan Theme

## Prerequisites

- WordPress installation (via Local WP or other)
- Access to WordPress admin panel

## Installation Steps

### 1. Copy Theme Files

Copy the `bunbukan` theme folder to your WordPress installation:

**For Local WP:**
- Navigate to your Local WP site directory
- Copy `wp-content/themes/bunbukan` folder to `wp-content/themes/` in your Local WP site

**Path example for Local WP:**
```
C:\Users\alain\Local Sites\bunbukan\app\public\wp-content\themes\bunbukan
```

### 2. Required Images

Copy the following images to `wp-content/themes/bunbukan/assets/images/`:

- `okinawa-gate.jpg` - Already copied to theme root, move to `assets/images/` folder
- `logo.png` - Club logo (create or copy from React version)
- `instructors.jpg` - Photo of instructors (optional, placeholder will be used if missing)
- `karate.jpg` - Karate training photo (optional)
- `kobudo.jpg` - Kobudo weapons training photo (optional)
- `dojo.jpg` - Dojo interior photo (optional)
- `instructor-alain.jpg` - Photo of Alain Berckmans (optional)

**Note:** The theme will work without these images, but placeholders should be added for better appearance.

### 3. Activate Theme

1. Log in to WordPress Admin
2. Go to **Appearance → Themes**
3. Find "Bunbukan" theme
4. Click **Activate**

### 4. Configure Menus

1. Go to **Appearance → Menus**
2. Create a new menu named "Primary Menu"
3. Add menu items (you can use custom links with anchors like `#home`, `#about`, etc.)
4. Assign to "Primary Menu" location

### 5. Set Homepage

1. Go to **Settings → Reading**
2. Set "Your homepage displays" to **"A static page"**
3. Create a new page named "Home" or select existing
4. Assign as homepage
5. The `front-page.php` template will automatically be used

### 6. Customize Content (Optional)

The theme uses hardcoded content in `front-page.php`. To make it editable:

1. Install **Pods Framework** plugin (optional)
2. Create custom fields for sections
3. Update `front-page.php` to use `get_field()` or Pods functions

## File Structure

```
bunbukan/
├── assets/
│   ├── images/          (Place images here)
│   └── js/
│       └── script.js
├── template-parts/
│   ├── content.php
│   └── content-none.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── front-page.php
├── sidebar.php
├── style.css
├── README.md
└── INSTALLATION.md
```

## Notes

- The theme is based on Nutriflow theme structure
- Content is migrated from React version in `C:\Users\alain\Downloads\bunbukan_eu`
- Design is inspired by kabuki.es
- The hero background image is `okinawa-gate.jpg` (should be in `assets/images/` folder)

## Troubleshooting

**Theme doesn't appear in themes list:**
- Check that all required files are present (style.css, functions.php, index.php)
- Check file permissions

**Images not displaying:**
- Verify images are in `assets/images/` folder
- Check file names match exactly (case-sensitive)
- Check file permissions

**Menu not working:**
- Ensure menu is assigned to "Primary Menu" location
- Check JavaScript console for errors

## Next Steps

1. Add actual images to `assets/images/` folder
2. Customize colors in `style.css` if needed
3. Add additional pages as needed
4. Consider adding Pods Framework for editable content

