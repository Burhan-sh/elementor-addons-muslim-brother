# MRM Elementor Addon - Widget Documentation

## Overview
This Elementor addon provides 10+ custom widgets based on your charity/nonprofit HTML design. All widgets are fully customizable through the Elementor interface with drag-and-drop functionality.

## Widget List

### 1. Hero Slider Widget
**Widget Name:** `Hero Slider`  
**Category:** MRM Elements  
**Purpose:** Full-width hero section with multiple slides

**Features:**
- Multiple slides with individual customization
- Background image for each slide
- Adjustable overlay opacity and color per slide
- Subtitle, title, and description for each slide
- Primary and secondary buttons (can be hidden per slide)
- Navigation dots
- Autoplay with customizable speed
- Adjustable slider height
- Full typography and color controls

**Usage:** Add this widget at the top of your page for an impressive hero section. Each slide can have different content and styling.

---

### 2. Feature Box Widget
**Widget Name:** `Feature Box`  
**Category:** MRM Elements  
**Purpose:** Single feature/service box (repeatable)

**Features:**
- Icon with gradient background
- Title and description
- Optional link
- Hover effects
- Full customization: colors, typography, spacing
- Adjustable icon size and box size

**Usage:** Create one widget and duplicate it manually to create a grid of features (e.g., Medical Treatment, Education, etc.). Place 4 boxes in a row for the design shown in the HTML.

---

### 3. Cause Box Widget
**Widget Name:** `Cause Box`  
**Category:** MRM Elements  
**Purpose:** Single donation cause/campaign box (repeatable)

**Features:**
- Featured image
- Category badge (can be hidden)
- Title and description
- Progress bar with percentage
- Raised amount and goal amount display
- Donate button (can be hidden)
- Circular percentage badge
- Full customization options

**Usage:** Create one widget per cause. Users can add multiple instances to create a causes grid. Perfect for "Education for Children" style boxes.

---

### 4. About Charity Section Widget
**Widget Name:** `About Charity Section`  
**Category:** MRM Elements  
**Purpose:** Complete about section with image and content

**Features:**
- Image with optional badge overlay
- Subtitle, title, and rich text description
- **Repeatable features list** (Impactful Causes, Transparency, etc.)
  - Each feature can be hidden individually
  - Icon, title, and description per feature
  - Add/remove features dynamically
- Statistics section (2 stats with values and labels)
- All elements can be hidden
- Full section background and padding controls

**Usage:** Use this for your main "About Charity" section. Add or remove feature items as needed through the repeater control.

---

### 5. Event Box Widget
**Widget Name:** `Event Box`  
**Category:** MRM Elements  
**Purpose:** Single event card (repeatable)

**Features:**
- Event image
- Date badge with day and month
- Time (with icon, can be hidden)
- Location (with icon, can be hidden)
- Event title and description
- "Learn More" link (can be hidden)
- Full customization for all elements

**Usage:** Create one widget for events like "Community Health Camp". Duplicate to show multiple events. Users can hide time or location if not needed.

---

### 6. Volunteer Box Widget
**Widget Name:** `Volunteer Box`  
**Category:** MRM Elements  
**Purpose:** Single team member/volunteer card (repeatable)

**Features:**
- Volunteer photo
- Name and position
- **Repeatable social media links**
  - Add/remove social icons dynamically
  - Icons show on hover
  - If no icons added, social section won't display
- Hover effects
- Adjustable image height

**Usage:** Create one widget per volunteer (like "Rahul Sharma"). Add social links only if needed - empty social links won't display.

---

### 7. Blog Box Widget
**Widget Name:** `Blog Box`  
**Category:** MRM Elements  
**Purpose:** Single blog post card (repeatable)

**Features:**
- Featured image
- Date badge (can be hidden)
- Author meta (can be hidden)
- Comments count (can be hidden)
- Title and excerpt
- "Read More" link (can be hidden)
- Custom icons for meta items

**Usage:** Create one widget per blog post. Duplicate for multiple posts. Hide any meta information you don't need.

---

### 8. Get In Touch Widget
**Widget Name:** `Get In Touch`  
**Category:** MRM Elements  
**Purpose:** Contact information section

**Features:**
- Section title and description
- **Repeatable contact items**
  - Icon, title, and content per item
  - Optional link per item
  - Hide individual items
  - Add/remove items dynamically (Address, Phone, Email, Hours, etc.)
- Full icon and text customization

**Usage:** Use this to display contact information. Add or remove contact items as needed. Each item can have a link (e.g., phone numbers, email addresses).

---

### 9. Contact Form 7 Widget
**Widget Name:** `Contact Form 7`  
**Category:** MRM Elements  
**Purpose:** Display Contact Form 7 forms with custom styling

**Features:**
- Dropdown to select any Contact Form 7 form
- Optional redirect after submission
- Option to hide labels
- Full styling controls:
  - Form container (background, padding, border radius, shadow)
  - Input fields (colors, typography, padding, border)
  - Submit button (colors, hover effects, padding)
  - Labels typography
- Displays warning in editor if CF7 not installed

**Usage:** 
1. Create a Contact Form 7 form in WordPress (Contact > Contact Forms)
2. Add this widget to your page
3. Select your form from the dropdown
4. Customize styling to match your design

**Note:** This is a separate widget from "Get In Touch" so you can place the form anywhere independently.

---

### 10. Footer Widget
**Widget Name:** `Footer`  
**Category:** MRM Elements  
**Purpose:** Complete footer section

**Features:**
- **4 Columns (each can be hidden):**
  1. **About Column:** Title, description, social media links (repeatable)
  2. **Quick Links Column:** Title, links list (repeatable)
  3. **Support Column:** Title, links list (repeatable)
  4. **Contact Info Column:** Title, contact items with icons (repeatable)
- Footer bottom with copyright text
- Social media icons (add/remove dynamically)
- All links support external/nofollow options
- Full customization:
  - Background and text colors
  - Heading typography
  - Link colors and hover effects
  - Social icon colors and hover effects
  - Bottom border customization

**Usage:** Add once at the bottom of your site. Customize each column independently. Hide any column you don't need.

---

## Widget Categories

All widgets are organized under the **"MRM Elements"** category in the Elementor widget panel for easy access.

## General Features

### Common to All Widgets:
- Responsive controls (Desktop, Tablet, Mobile)
- Full typography controls
- Color customization
- Spacing controls (padding, margin)
- Show/hide options for most elements
- Hover effects where applicable
- Clean, semantic HTML output
- Inline CSS for styling

### Repeatable Elements:
Several widgets support repeatable/dynamic content:
- Hero Slider: Multiple slides
- About Charity: Features list
- Volunteer Box: Social links
- Blog Box: Meta items (author, comments)
- Get In Touch: Contact items
- Footer: All links and social icons

This allows you to add or remove items without code changes.

## Installation & Usage

1. **Activate the Plugin:** The plugin is located in `mrm-ele-addon` folder
2. **Open Elementor:** Edit any page with Elementor
3. **Find Widgets:** Look for "MRM Elements" category in the widget panel
4. **Drag & Drop:** Add widgets to your page
5. **Customize:** Use the left panel to customize each widget
6. **Repeat Widgets:** For repeatable widgets (Feature Box, Cause Box, etc.), duplicate the widget to create grids

## Widget Naming Convention

| HTML Section | Widget Name | Repeatable |
|--------------|-------------|------------|
| Hero Section | Hero Slider | No (multi-slide) |
| Medical Treatment Box | Feature Box | Yes |
| Education for Children | Cause Box | Yes |
| About Charity | About Charity Section | No (has repeater) |
| Community Health Camp | Event Box | Yes |
| Volunteer (Rahul Sharma) | Volunteer Box | Yes |
| Blog Post | Blog Box | Yes |
| Get In Touch | Get In Touch | No (has repeater) |
| Contact Form | Contact Form 7 | No |
| Footer | Footer | No (has repeaters) |

## Tips for Best Results

1. **Hero Slider:** Use high-quality images (1920x1080px recommended). Adjust overlay opacity for better text readability.

2. **Feature/Cause Boxes:** Create consistent designs by duplicating widgets instead of creating new ones from scratch.

3. **About Section:** Use the repeater to add/remove features. Each feature can be hidden without deleting.

4. **Events:** Format dates consistently (e.g., "15 DEC"). Use clear, descriptive titles.

5. **Volunteers:** Social links appear on hover. Leave social links empty if the volunteer has no social media.

6. **Blog Posts:** Use engaging excerpts (2-3 sentences). Dates should be short (e.g., "20 NOV").

7. **Contact Form 7:** Create your form first in CF7, then select it in the widget. Use placeholder text in form fields.

8. **Footer:** Hide unused columns to create flexible layouts (2, 3, or 4 columns).

9. **Responsive Design:** Test on mobile devices and adjust responsive settings as needed.

10. **Performance:** Use optimized images and consider lazy loading for better performance.

## Support & Customization

All widgets follow WordPress and Elementor best practices:
- Escaped output for security
- Translation-ready
- Supports RTL languages
- Clean, maintainable code
- Follows Elementor's style guidelines

For custom modifications, edit the widget files in `/widgets/` directory.

## Widget Files

- `hero-slider-widget.php` - Hero Slider
- `feature-box-widget.php` - Feature Box
- `cause-box-widget.php` - Cause Box
- `about-charity-widget.php` - About Charity Section
- `event-box-widget.php` - Event Box
- `volunteer-box-widget.php` - Volunteer Box
- `blog-box-widget.php` - Blog Box
- `get-in-touch-widget.php` - Get In Touch
- `contact-form-widget.php` - Contact Form 7
- `footer-widget.php` - Footer

All widgets are registered in `mrm-ele-addon.php`.

---

**Version:** 1.1.0  
**Last Updated:** 2024  
**Requires:** Elementor 3.0.0+, WordPress 5.0+, PHP 7.0+  
**Optional:** Contact Form 7 (for Contact Form widget)
