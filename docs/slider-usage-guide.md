# MDB Slider Widget - Usage Guide

## Overview

The MDB Slider Widget is a custom Elementor widget that provides a split-screen layout with content on the left (30%) and images on the right (70%). It includes progress bars and slide counter for navigation.

## Features

- **Split-screen Layout**: 30% content area / 70% image area
- **Triple Image Display**: 3 images with different heights (100%, 130%, 80%)
- **Progress Navigation**: Flat 3px progress bars for slide navigation
- **Elementor Integration**: Full support for Elementor templates in content area
- **Responsive Design**: Optimized for desktop, tablet, and mobile
- **Auto-play Support**: Configurable auto-play with pause on hover
- **Accessibility**: Keyboard navigation and screen reader support
- **Touch Support**: Swipe gestures for mobile devices

## Usage

### Adding the Widget

1. Open Elementor editor
2. Search for "MDB Slider" in the widget panel
3. Drag the widget to your desired location
4. The widget will appear in the "MDB Widgets" category

### Configuring Slides

#### Content Tab - Slides Section:
- **Add Slides**: Use the repeater control to add multiple slides
- **Slide Title**: Enter a descriptive title for each slide
- **Images**: Upload 3 images for each slide (Left, Center, Right)
- **Content Template**: Select an Elementor template for the content area

#### Content Tab - Slider Settings:
- **Auto-play**: Enable/disable automatic slide progression
- **Auto-play Speed**: Set interval between slides (1000-10000ms)
- **Transition Speed**: Set animation duration (100-2000ms)
- **Pause on Hover**: Auto-pause when hovering over slider
- **Loop Slides**: Enable continuous looping of slides

#### Content Tab - Progress & Counter:
- **Show Progress Bars**: Toggle progress bar visibility
- **Show Slide Counter**: Toggle counter display
- **Counter Format**: Choose between "X of Y", "X / Y", or "X | Y"

### Styling Options

#### Style Tab - Layout:
- **Container Height**: Set overall slider height (300-800px or viewport height)
- **Content Width**: Adjust content/image ratio (20-50%)

#### Style Tab - Images:
- **Image Width**: Set fixed width for all images (100-300px)
- **Base Height**: Set height for Image 1 (others auto-calculate as 130% and 80%)
- **Gap Between Images**: Spacing between the 3 images (5-50px)
- **Border**: Add borders to images
- **Border Radius**: Round image corners
- **Box Shadow**: Add shadow effects

#### Style Tab - Content Area:
- **Background**: Set background color or gradient
- **Padding**: Adjust internal spacing
- **Vertical Alignment**: Align content top, center, or bottom

#### Style Tab - Progress & Counter:
- **Progress Bar Width**: Adjust bar width (20-50px)
- **Active Bar Color**: Color for current slide indicator
- **Inactive Bar Color**: Color for other slide indicators
- **Hover Bar Color**: Color on hover
- **Counter Typography**: Font settings for counter text
- **Counter Color**: Text color for counter
- **Spacing**: Distance from content area

## Content Templates

### Creating Content Templates:

1. Go to **Templates > Saved Templates** in WordPress admin
2. Click **Add New**
3. Choose **Section** or **Page** template type
4. Design your content using Elementor widgets
5. Save the template
6. Return to your slider and select the template from the dropdown

### Template Best Practices:

- Keep content concise for the 30% width constraint
- Use responsive design for mobile compatibility
- Consider vertical space constraints
- Test with different viewport sizes
- Avoid overly complex layouts in the content area

## Navigation

### Progress Bars:
- Click any progress bar to jump to that slide
- Keyboard navigation: Arrow keys, Enter/Space, Home/End
- Each bar represents one slide
- Active slide shows filled bar, others show outlined bars

### Counter Display:
- Shows current slide position (e.g., "2 of 5")
- Updates automatically on slide change
- Multiple format options available

## Responsive Behavior

### Desktop (1200px+):
- Full 30/70 split maintained
- All images visible with full proportions
- Progress bars and counter on single line

### Tablet (768px-1199px):
- Adjusts to 40/60 split
- Smaller image dimensions
- Maintains horizontal layout

### Mobile (<768px):
- Can stack vertically or maintain side-by-side
- Smaller fixed image widths
- Progress component may stack on very small screens

## JavaScript Events

The slider fires custom events that you can listen to:

```javascript
$('.mdb-slider-widget').on('mdb:slideChange', function(e, data) {
    console.log('Slide changed to:', data.currentSlide);
    console.log('Previous slide was:', data.previousSlide);
    console.log('Direction:', data.direction);
});

$('.mdb-slider-widget').on('mdb:sliderResize', function(e) {
    console.log('Slider resized');
});
```

## Troubleshooting

### Common Issues:

1. **Images not displaying**: Check image URLs and file permissions
2. **Content not showing**: Verify Elementor template is published and accessible
3. **Auto-play not working**: Ensure auto-play is enabled and speed is set
4. **Progress bars not clickable**: Check for JavaScript errors in console
5. **Responsive issues**: Test with different viewport sizes

### Performance Tips:

1. Optimize images before uploading
2. Use WebP format where supported
3. Keep Elementor templates lightweight
4. Limit number of slides for better performance
5. Test on various devices and connection speeds

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Accessibility Features

- Keyboard navigation support
- ARIA labels for screen readers
- Focus indicators
- High contrast mode support
- Reduced motion support (respects user preferences)

## Examples

### Basic Setup:
1. Add 3-5 slides with different images
2. Create simple content templates with headings and text
3. Enable auto-play with 4-second intervals
4. Use default progress bar styling

### Advanced Setup:
1. Create detailed content templates with buttons and forms
2. Customize image dimensions for brand consistency
3. Style progress bars to match brand colors
4. Add custom CSS for unique hover effects

## Support

For technical support and customizations, contact the development team or refer to the plugin documentation in the `/docs` folder.