# VeCarCMS Content Directory

This directory is similar to WordPress's `wp-content/` directory. It contains themes, plugins, and uploads that can be managed independently from the core CMS.

## 📁 Directory Structure

```
content/
├── themes/          → Installable themes (via zip upload)
├── plugins/         → Custom plugins (via zip upload)
└── uploads/         → Symlink to storage/app/public/
```

## 🎨 Themes

Themes are located in `content/themes/` and can be:
- Installed via ZIP upload from the admin panel
- Manually placed in this directory
- Shared between different VeCarCMS installations

### Theme Structure

```
content/themes/mytheme/
├── theme.json              # Theme configuration
├── views/
│   ├── layouts/
│   │   └── main.blade.php
│   ├── partials/
│   │   ├── header.blade.php
│   │   └── footer.blade.php
│   ├── home.blade.php
│   ├── post.blade.php
│   └── page.blade.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── screenshot.png          # Theme preview (880x660px)
└── README.md              # Theme documentation
```

### Assets Access

Theme assets are automatically symlinked to `public/content/themes/themename/` for public access.

## 🔌 Plugins

Custom plugins are located in `content/plugins/`.

**Note:** Core plugins (Stripe, Mailchimp, etc.) remain in `Modules/` directory as they are part of the base CMS installation.

### Plugin Structure

```
content/plugins/myplugin/
├── plugin.json            # Plugin configuration
├── src/
│   ├── Controllers/
│   ├── Models/
│   └── Services/
├── routes/
│   └── web.php
├── views/
├── assets/
└── README.md
```

## 📤 Uploads

The `uploads/` directory is a symlink to `storage/app/public/` where media files are stored.

## 🔐 Version Control

By default, uploaded themes and plugins are **NOT** tracked in git (see `.gitignore`).

To track a custom theme/plugin, add it to `.gitignore`:

```gitignore
# Keep your custom theme
!themes/your-custom-theme/
```

## 🚀 Installing Themes/Plugins

### Via Admin Panel (Recommended)
1. Go to **Admin > Themes** or **Admin > Plugins**
2. Click **Upload**
3. Select ZIP file
4. Activate

### Manually
1. Extract ZIP to `content/themes/themename/` or `content/plugins/pluginname/`
2. Run `php artisan theme:scan` or `php artisan plugin:scan`
3. Activate from admin panel

## 📦 Creating Distributable Themes/Plugins

To share a theme/plugin:

```bash
# Create theme ZIP
cd content/themes
zip -r mytheme.zip mytheme/

# Create plugin ZIP
cd content/plugins
zip -r myplugin.zip myplugin/
```

The ZIP can be uploaded to any VeCarCMS installation!

---

**VeCarCMS** - WordPress-like flexibility with Laravel power! 🚀

