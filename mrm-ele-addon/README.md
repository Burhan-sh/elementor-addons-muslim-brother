# MRM Ele Addon - Elementor Custom Widgets Plugin

WordPress ke liye ek custom Elementor addon plugin jo drag and drop widgets provide karta hai.

## Features

- ✅ Elementor ke saath fully integrated
- ✅ Drag and drop widgets
- ✅ Custom widget category "MRM Elements"
- ✅ Demo widget with customizable options
- ✅ Style controls (colors, typography, spacing)
- ✅ Responsive controls
- ✅ Live preview support

## Requirements

- WordPress 5.0 ya usse zyada
- Elementor 3.0.0 ya usse zyada
- PHP 7.0 ya usse zyada

## Installation

### Method 1: Manual Installation

1. `mrm-ele-addon` folder ko apne WordPress installation ke `wp-content/plugins/` directory mein copy karein
2. WordPress admin panel mein Plugins page par jayein
3. "MRM Ele Addon" plugin ko activate karein

### Method 2: Zip Upload

1. Is folder ko zip karein
2. WordPress admin panel mein Plugins → Add New par jayein
3. "Upload Plugin" button par click karein
4. Zip file select karke upload karein
5. Plugin ko activate karein

## Usage

### Plugin Activate Karne Ke Baad:

1. Koi bhi page ya post ko Elementor se edit karein
2. Left sidebar mein widgets search karein
3. "MRM Elements" category dekhegi
4. "MRM Demo Widget" ko drag karke page par drop karein
5. Widget settings customize karein

### Demo Widget Features:

**Content Tab:**
- Title field
- Description textarea
- Button text
- Button link with external/nofollow options

**Style Tab:**
- Title styling (color, typography)
- Description styling (color, typography)  
- Button styling (background color, text color, typography, padding, border radius)

## File Structure

```
mrm-ele-addon/
│
├── mrm-ele-addon.php          # Main plugin file
├── widgets/                    # Widgets folder
│   └── demo-widget.php        # Demo widget class
└── README.md                   # Plugin documentation
```

## Widget Development

Nayi widgets add karne ke liye:

1. `widgets` folder mein nayi PHP file banayein
2. `Widget_Base` class ko extend karein
3. Required methods implement karein:
   - `get_name()`
   - `get_title()`
   - `get_icon()`
   - `get_categories()`
   - `register_controls()`
   - `render()`
   - `content_template()` (optional)
4. Main plugin file mein widget ko register karein

## Customization

### Nayi Widget Category Add Karna:

`mrm-ele-addon.php` mein `add_elementor_widget_categories()` method ko modify karein.

### Widget Settings Customize Karna:

Widget file mein `register_controls()` method mein naye controls add karein:
- Text controls
- Textarea controls
- Number controls
- Color controls
- Typography controls
- Spacing controls
- And many more...

## Support

Kisi bhi tarah ki help ke liye documentation check karein ya support team se contact karein.

## License

GPL v2 or later

## Changelog

### Version 1.0.0
- Initial release
- Demo widget with basic functionality
- Custom category support
- Style controls added

