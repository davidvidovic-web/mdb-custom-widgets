# MDB Slider Widget - Swiper Integration Guide

## Overview

The MDB Slider Widget has been upgraded to leverage Elementor's Swiper JS library, providing enhanced performance, better touch/swipe support, and more robust navigation features.

## Key Features

### Swiper Integration
- **Elementor Swiper**: Uses Elementor's built-in Swiper library for maximum compatibility
- **Dual Swiper Instances**: Separate Swiper instances for content and images with controller linking
- **Automatic Fallback**: Falls back to CDN Swiper if Elementor's version is unavailable
- **Progressive Enhancement**: Graceful degradation if Swiper fails to load

### Enhanced Navigation
- **Progress Bar Navigation**: Click progress bars to navigate to specific slides
- **Keyboard Navigation**: Arrow keys (left/right), Home, End keys
- **Touch/Swipe Support**: Native Swiper touch gestures plus custom fallback
- **Auto-play**: Configurable auto-play with pause on hover
- **Loop Mode**: Continuous looping of slides

### Accessibility
- **ARIA Labels**: Proper ARIA attributes for screen readers
- **Keyboard Focus**: Full keyboard navigation support
- **Focus Indicators**: Visual focus indicators for all interactive elements
- **Screen Reader Support**: Proper labeling and state announcements

## Technical Implementation

### File Structure
```
assets/js/
├── slider-widget.js        # New Swiper-integrated version
├── slider-widget-old.js    # Previous version (backup)
└── widgets.js              # Base widget utilities

assets/css/
├── slider-widget.css       # Updated with Swiper styles
└── widgets.css             # Base widget styles
```

### Dependencies
The widget now properly registers and loads:
1. **Swiper**: Elementor's Swiper library (with CDN fallback)
2. **jQuery**: For DOM manipulation and utilities  
3. **MDB Widgets Base**: Core widget functionality

### Class Structure

#### MDBSlider Class
```javascript
class MDBSlider {
    constructor(element)        // Initialize slider instance
    init()                     // Setup Swiper integration
    waitForSwiper()           // Async Swiper loading
    getConfig()               // Parse configuration
    setupSwiperStructure()    // Prepare DOM for Swiper
    initSwiper()              // Create Swiper instances
    bindEvents()              // Event handlers
    setupAccessibility()      // ARIA attributes
    
    // Navigation methods
    goToSlide(index)          // Navigate to specific slide
    nextSlide()               // Go to next slide
    previousSlide()           // Go to previous slide
    
    // State management
    onSlideChange()           // Handle slide transitions
    updateActiveStates()      // Update UI states
    updateCounter()           // Update slide counter
    updateAccessibility()     // Update ARIA states
    
    // Lifecycle
    destroy()                 // Clean up instances
    refresh()                 // Reinitialize slider
}
```

## Configuration Options

### JSON Configuration
```json
{
    "autoplay": true,
    "autoplaySpeed": 3000,
    "transitionSpeed": 500,
    "pauseOnHover": true,
    "loop": true,
    "totalSlides": 5,
    "counterFormat": "x_of_y"
}
```

### Data Attributes (Fallback)
```html
<div class="mdb-slider-widget" 
     data-settings='{"autoplay":true,"speed":3000}'>
```

### Counter Formats
- `x_of_y`: "1 of 5" (default)
- `x_slash_y`: "1 / 5"
- `x_pipe_y`: "1 | 5"

## HTML Structure

### Expected DOM Structure
```html
<div class="mdb-slider-widget">
    <div class="mdb-slider-container">
        <!-- Content Area (30%) -->
        <div class="mdb-slider-content">
            <div class="mdb-slider-content-inner">
                <div class="mdb-slide-content" data-slide="0">...</div>
                <div class="mdb-slide-content" data-slide="1">...</div>
            </div>
            <div class="mdb-slider-controls">
                <div class="mdb-progress-bars">
                    <div class="mdb-progress-bar" data-slide="0"></div>
                    <div class="mdb-progress-bar" data-slide="1"></div>
                </div>
                <div class="mdb-slide-counter">1 of 2</div>
            </div>
        </div>
        
        <!-- Images Area (70%) -->
        <div class="mdb-slider-images">
            <div class="mdb-slide-images" data-slide="0">...</div>
            <div class="mdb-slide-images" data-slide="1">...</div>
        </div>
    </div>
    
    <!-- Configuration -->
    <script type="application/json" class="mdb-slider-config">
    {"autoplay": true, "autoplaySpeed": 3000}
    </script>
</div>
```

### Generated Swiper Structure
After initialization, the DOM is transformed to:
```html
<div class="mdb-slider-widget">
    <div class="mdb-slider-container">
        <div class="mdb-slider-content">
            <!-- Content Swiper -->
            <div class="mdb-content-swiper swiper">
                <div class="mdb-slider-content-inner swiper-wrapper">
                    <div class="mdb-slide-content swiper-slide">...</div>
                    <div class="mdb-slide-content swiper-slide">...</div>
                </div>
            </div>
            <div class="mdb-slider-controls">...</div>
        </div>
        
        <div class="mdb-slider-content">
            <!-- Images Swiper -->
            <div class="mdb-images-swiper swiper">
                <div class="mdb-slider-images swiper-wrapper">
                    <div class="mdb-slide-images swiper-slide">...</div>
                    <div class="mdb-slide-images swiper-slide">...</div>
                </div>
            </div>
        </div>
    </div>
</div>
```

## Integration Points

### WordPress/Elementor
```php
// Widget dependencies
public function get_script_depends() {
    return [ 'mdb-custom-widgets', 'mdb-slider-widget', 'swiper' ];
}

// Swiper registration in main plugin
wp_register_script( 'swiper', ELEMENTOR_ASSETS_URL . 'lib/swiper/swiper.min.js' );
```

### Elementor Frontend Hooks
```javascript
// Widget initialization
window.elementorFrontend.hooks.addAction(
    'frontend/element_ready/mdb-slider.default', 
    function($scope) {
        $scope.find('.mdb-slider-widget').mdbSlider();
    }
);
```

## Events

### Custom Events
```javascript
// Slide change event
$('.mdb-slider-widget').on('mdb:slideChange', function(e, data) {
    console.log('Slide changed to:', data.currentSlide);
});

// Swiper initialization event
$('.mdb-slider-widget').on('mdb:swiperInit', function(e, data) {
    console.log('Swiper initialized:', data.swiper);
});
```

### Native Swiper Events
All standard Swiper events are available through the instance:
```javascript
const slider = $('.mdb-slider-widget').data('mdb-slider');
if (slider.primarySwiper) {
    slider.primarySwiper.on('slideChange', function() {
        // Handle Swiper slide change
    });
}
```

## API Methods

### jQuery Plugin
```javascript
// Initialize
$('.mdb-slider-widget').mdbSlider();

// Get instance
const slider = $('.mdb-slider-widget').data('mdb-slider');

// Navigation
slider.goToSlide(2);
slider.nextSlide();
slider.previousSlide();

// Lifecycle
slider.destroy();
slider.refresh();
```

### Global Class
```javascript
// Direct instantiation
const slider = new MDBSlider(element);
```

## Error Handling

### Swiper Loading
- Waits up to 5 seconds (50 attempts) for Swiper to load
- Provides informative console logging
- Graceful fallback if Swiper unavailable

### Validation
- Validates slider element existence
- Checks for minimum slide count
- Prevents double initialization

### Console Logging
- Initialization status
- Configuration details
- Error messages with context
- Performance timing

## Performance Considerations

### Async Loading
- Non-blocking Swiper initialization
- Progressive enhancement approach
- Multiple fallback strategies

### Memory Management
- Proper cleanup on destroy
- Event unbinding
- Swiper instance disposal

### Optimization
- Single Swiper instance per content/image area
- Efficient DOM manipulation
- Debounced resize handling

## Browser Support

### Modern Browsers
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

### Mobile Support
- iOS Safari 12+
- Chrome Mobile 60+
- Samsung Internet 8+

### Fallbacks
- Graceful degradation for older browsers
- Basic functionality without Swiper
- Progressive enhancement approach

## Testing

### Test Files
- `test-swiper-slider.html`: Comprehensive Swiper integration test
- `test-assets.html`: Asset loading verification

### Test Scenarios
1. Swiper library loading
2. Dual Swiper synchronization
3. Progress bar navigation
4. Keyboard navigation
5. Touch/swipe gestures
6. Auto-play functionality
7. Configuration parsing
8. Error handling
9. Accessibility features
10. Responsive behavior

## Migration Guide

### From Previous Version
1. The new version is backwards compatible
2. Existing HTML structure works without changes
3. Configuration options remain the same
4. Additional Swiper features now available

### Debugging
1. Check browser console for initialization logs
2. Verify Swiper library loading
3. Test with provided test files
4. Use browser dev tools to inspect generated DOM

## Future Enhancements

### Planned Features
- Custom Swiper effects
- Thumbnail navigation
- Video slide support
- Dynamic slide loading
- Advanced transition options

### Customization Points
- Swiper configuration overrides
- Custom transition effects
- Additional navigation elements
- Theme integration hooks