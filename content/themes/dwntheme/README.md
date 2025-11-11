# DWNTheme Theme for VeCarCMS

A modern, responsive Bootstrap 5 theme for VeCarCMS, perfect for business websites, SAAS applications, and corporate landing pages.

## Features

- **Bootstrap 5** - Built on the latest Bootstrap framework
- **Fully Responsive** - Looks great on all devices
- **Modern Design** - Clean and professional aesthetic
- **AOS Animations** - Smooth scroll animations
- **Page Builder Support** - Full GrapesJS integration
- **Widget Ready** - 6 widget zones (sidebar-blog, sidebar-page, footer-1 through footer-4)
- **Menu System** - Integrated with VeCarCMS menu builder
- **SEO Optimized** - Includes structured data and meta tags
- **Comment System** - Full comment support on blog posts
- **Multiple Page Templates** - Default, Full Width, and Landing page templates
- **Custom Error Pages** - Stylized 403, 404, 500, and 503 pages

## Installation

The theme is already installed and activated. To switch to this theme from the admin panel:

1. Go to **Admin → Themes**
2. Find "DWNTheme" in the list
3. Click "Activate"

## Theme Structure

```
content/themes/dwntheme/
├── theme.json              # Theme configuration
├── README.md               # This file
├── screenshot.png          # Theme screenshot
├── layouts/
│   └── main.blade.php      # Main layout file
├── partials/
│   ├── header.blade.php    # Header/navbar
│   └── footer.blade.php    # Footer
├── views/
│   ├── home.blade.php      # Homepage
│   ├── blog.blade.php      # Blog archive
│   ├── post.blade.php      # Single post
│   ├── page.blade.php      # Single page
│   ├── category.blade.php  # Category archive
│   ├── tag.blade.php       # Tag archive
│   ├── search.blade.php    # Search results
│   └── errors/
│       ├── 403.blade.php   # Access denied
│       ├── 404.blade.php   # Not found
│       ├── 500.blade.php   # Server error
│       └── 503.blade.php   # Maintenance
└── assets/
    ├── css/
    │   ├── libs.bundle.css      # Vendor styles
    │   └── theme.bundle.css     # Theme styles
    ├── js/
    │   ├── vendor.bundle.js     # Vendor scripts
    │   └── theme.bundle.js      # Theme scripts
    ├── fonts/
    │   └── Metropolis-*.woff    # Custom fonts
    └── images/
        └── ...                  # Theme images
```

## Menu Locations

The theme supports 2 menu locations:

1. **Primary Menu** - Main navigation in the header
2. **Footer Menu** - Links in the footer copyright area

## Widget Zones

The theme includes 6 widget zones:

1. **sidebar-blog** - Displayed on blog archive and single post pages
2. **sidebar-page** - Displayed on standard pages
3. **footer-1** - First footer column
4. **footer-2** - Second footer column
5. **footer-3** - Third footer column
6. **footer-4** - Fourth footer column

## Page Templates

### Default Template
Standard layout with optional sidebar.

### Full Width Template
Content spans full width without sidebar.

### Landing Page Template
Designed for landing pages and promotional content.

## Customization

### Theme Customizer

Access the theme customizer from **Admin → Themes → Customizer** to adjust:

- **Colors**: Primary, secondary, text, and link colors
- **Typography**: Font families and sizes
- **Layout**: Container width and sidebar position
- **Custom CSS**: Add your own CSS rules

### Settings Integration

The theme uses VeCarCMS settings for:

- `site_name` - Site title
- `site_description` - Site tagline
- `hero_title` - Homepage hero title
- `hero_description` - Homepage hero description
- `hero_image` - Homepage hero image
- `cta_title` - Call-to-action title
- `cta_description` - Call-to-action description
- `allow_registration` - Show/hide registration buttons
- `enable_comments` - Enable/disable comments
- `social_*` - Social media links (facebook, twitter, instagram, linkedin, youtube)

## Credits

- **Bootstrap**: [getbootstrap.com](https://getbootstrap.com/)
- **AOS.js**: [michalsnik.github.io/aos](https://michalsnik.github.io/aos/)
- **RemixIcon**: [remixicon.com](https://remixicon.com/)

## Support

For support and documentation, visit [VeCarCMS Documentation](#) or contact the development team.

## License

This theme is part of VeCarCMS and follows the same license as the main application.

---

**Version**: 1.0.0  
**Author**: VeCarCMS Team  
**Last Updated**: November 2025

