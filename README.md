# MDB Custom Widgets

Custom Elementor widgets for MyDirectBlinds website, providing specialized functionality for slider presentations, reviews navigation, and category scrolling.

## Description

MDB Custom Widgets is a comprehensive WordPress plugin that extends Elementor with custom widgets specifically designed for MyDirectBlinds. The plugin includes four main widgets that provide advanced functionality for content presentation and user interaction.

## Features

### 🎨 MDB Slider Widget
- **Split-screen Layout**: 30% content area / 70% image area
- **Triple Image Display**: 3 images with different heights (100%, 130%, 80%)
- **Progress Navigation**: Flat 3px progress bars for slide navigation
- **Elementor Integration**: Full support for Elementor templates in content area
- **Auto-play Support**: Configurable auto-play with pause on hover
- **Responsive Design**: Optimized for desktop, tablet, and mobile
- **Accessibility**: Keyboard navigation and screen reader support
- **Touch Support**: Swipe gestures for mobile devices

### 🔄 Reviews Navigation Widget
- **Slider Control**: Navigation buttons for reviews slider widget
- **Customizable Appearance**: Full Elementor style integration
- **Target Flexibility**: Can control any slider by ID
- **Accessibility**: ARIA labels and keyboard support
- **Responsive**: Works across all device sizes
- **Elementor Icons**: Uses reliable Elementor icon system

### 📱 Category Scroller Widget
- **Horizontal Scrolling**: Smooth category navigation
- **Image Support**: Category images with hover effects
- **Navigation Controls**: Previous/next buttons
- **Responsive Grid**: Adaptive layout for different screen sizes
- **Performance Optimized**: Lazy loading and efficient rendering

### 🧩 Reviews Slider Widget
- **Fade Transitions**: Smooth slide transitions
- **Auto-play**: Configurable automatic progression
- **Navigation Integration**: Works with Reviews Navigation Widget
- **Custom Styling**: Full Elementor integration
- **Event System**: Custom events for widget communication

## Installation

1. Download the plugin files
2. Upload to `/wp-content/plugins/mdb-custom-widgets/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Widgets will appear in Elementor under "MDB Widgets" category

## Requirements

- WordPress 5.0+
- PHP 8.2+
- Elementor 3.0.0+
- Elementor Pro 3.16.0+ (recommended)

## File Structure

```
mdb-custom-widgets/
├── mdb-custom-widgets.php          # Main plugin file
├── README.md                       # Documentation
├── includes/
│   └── widgets/
│       ├── widget-base.php         # Base widget class
│       ├── slider-widget.php       # MDB Slider Widget
│       ├── reviews-navigation-widget.php  # Reviews Navigation
│       ├── reviews-slider-widget.php      # Reviews Slider
│       └── category-scroller-widget.php   # Category Scroller
├── assets/
│   ├── css/
│   │   ├── widgets.css             # Common widget styles
│   │   ├── slider-widget.css       # Slider widget styles
│   │   ├── reviews-navigation-widget.css  # Navigation styles
│   │   ├── reviews-slider-widget.css      # Reviews slider styles
│   │   └── category-scroller-widget.css   # Category scroller styles
│   └── js/
│       ├── widgets.js              # Common widget scripts
│       ├── slider-widget.js        # Slider functionality
│       ├── reviews-navigation-widget.js   # Navigation functionality
│       ├── reviews-slider-widget.js       # Reviews slider functionality
│       └── category-scroller-widget.js    # Category scroller functionality
└── docs/                           # Comprehensive documentation
    ├── slider-usage-guide.md       # Slider widget guide
    ├── slider-widget-spec.md       # Slider specifications
    ├── swiper-integration-guide.md # Swiper integration
    ├── reviews-navigation-troubleshooting.md  # Navigation fixes
    ├── reviews-navigation-icon-fix.md         # Icon fixes
    ├── reviews-navigation-css-architecture.md # CSS structure
    └── slider-slide-count-fix.md   # Slide counting fix
```

## Usage

### Adding Widgets

1. Edit a page with Elementor
2. Look for "MDB Widgets" in the widget panel
3. Drag desired widget to your page
4. Configure options in the widget settings

### Widget Configuration

#### MDB Slider Widget
- **Content Tab**: Add slides with titles, text, and buttons
- **Style Tab**: Customize colors, spacing, and animations
- **Advanced Tab**: Set container options and CSS

#### Reviews Navigation Widget
- **Content Tab**: Set target slider ID and button visibility
- **Style Tab**: Customize button appearance and spacing
- **Advanced Tab**: Container styling and positioning

#### Category Scroller Widget
- **Content Tab**: Add categories with images and links
- **Style Tab**: Grid layout, image styling, and navigation
- **Advanced Tab**: Performance and behavior options

#### Reviews Slider Widget
- **Content Tab**: Add review slides with content
- **Style Tab**: Transition effects and styling
- **Advanced Tab**: Auto-play and advanced options

## Recent Fixes & Improvements

### ✅ Reviews Navigation Widget
- **Icon System**: Switched from FontAwesome to Elementor icons for better compatibility
- **CSS Architecture**: Separated critical visibility rules from customizable properties
- **Elementor Integration**: Full support for all Elementor style controls
- **Responsive Design**: Mobile-optimized button sizing

### ✅ Slider Widget
- **Slide Counting Bug**: Fixed incorrect slide count (was showing 2/9 instead of 2/3)
- **Counter Display**: Accurate slide counters in all formats
- **Navigation**: Smooth transitions between slides
- **Performance**: Optimized slide detection logic

### ✅ CSS Framework
- **Modular Structure**: Each widget has dedicated stylesheet
- **CSS Variables**: Consistent theming across widgets
- **Responsive**: Mobile-first responsive design
- **Accessibility**: ARIA support and keyboard navigation

## Adding New Widgets

To add a new widget:

1. Create a new PHP file in `includes/widgets/`
2. Extend the `MDB_Widget_Base` class
3. Register the widget in the main plugin file's `init_widgets()` method

### Example Widget Structure

```php
<?php
class MDB_Example_Widget extends MDB_Widget_Base {
    
    public function get_name() {
        return 'mdb-example';
    }
    
    public function get_title() {
        return __('MDB Example Widget', 'mdb-custom-widgets');
    }
    
    public function get_icon() {
        return 'eicon-button';
    }
    
    protected function _register_controls() {
        // Add your controls here
    }
    
    protected function render() {
        // Render your widget here
    }
}
```

## Widget Category

All MDB widgets are grouped under the "MDB Widgets" category in Elementor's widget panel.

## Available Utility Methods

The base class provides several utility methods:

- `add_responsive_control_args()` - Add responsive controls with common settings
- `add_typography_control()` - Add typography group control
- `add_color_control()` - Add color control with selector
- `add_background_control()` - Add background group control
- `add_border_control()` - Add border group control
- `add_box_shadow_control()` - Add box shadow group control
- `add_spacing_controls()` - Add margin and padding controls
- `get_alignment_control()` - Get alignment control configuration

## JavaScript Utilities

The plugin includes JavaScript utilities accessible via `window.MDBWidgets`:

- `handleResponsive()` - Handle responsive behavior
- `smoothScroll()` - Smooth scrolling functionality
- `showLoading()` - Display loading states
- `showError()` - Display error messages
- `debounce()` - Debounce function for performance

## Hooks and Filters

The plugin uses standard Elementor hooks:

- `elementor/widgets/widgets_registered` - Register widgets
- `elementor/controls/controls_registered` - Register custom controls
- `elementor/frontend/after_enqueue_styles` - Enqueue styles
- `elementor/frontend/after_register_scripts` - Register scripts

## Support

This plugin is custom-developed for MyDirectBlinds. For support and customizations, contact the development team.

## Changelog

### Version 1.0.0 (Current)
- ✅ **MDB Slider Widget**: Split-screen layout with triple image display
- ✅ **Reviews Navigation Widget**: Customizable navigation controls with Elementor integration
- ✅ **Reviews Slider Widget**: Fade transitions with auto-play support
- ✅ **Category Scroller Widget**: Horizontal scrolling with responsive grid
- ✅ **Base Architecture**: Robust widget base class with utilities
- ✅ **CSS Framework**: Modular stylesheets with CSS variables
- ✅ **JavaScript Framework**: Event-driven widget communication
- ✅ **Documentation**: Comprehensive guides and troubleshooting
- ✅ **Accessibility**: ARIA labels, keyboard navigation, screen reader support
- ✅ **Performance**: Optimized asset loading and efficient rendering
- ✅ **Bug Fixes**: Slider counting issue, icon compatibility, Elementor integration

### Recent Updates (October 2025)
- 🔧 **Fixed**: Slider counter showing incorrect totals (2/9 instead of 2/3)
- 🔧 **Fixed**: Reviews navigation arrows not visible on frontend
- 🔧 **Fixed**: Elementor style controls not applying to navigation buttons
- 🎨 **Improved**: Icon system switched to Elementor icons for reliability
- 📚 **Added**: Comprehensive documentation and troubleshooting guides
- ⚡ **Optimized**: CSS architecture for better Elementor integration

## Author

**David Vidovic**  
Website: [https://davidvidovic.com](https://davidvidovic.com)

## License

This plugin is proprietary software developed specifically for MyDirectBlinds. All rights reserved.

---

*Last updated: October 23, 2025*