# MDB Slider Widget - Design Document

## Widget Overview

The MDB Slider Widget is a custom Elementor widget that combines content flexibility with dynamic image display. It features a split-screen layout with an editable content area and a unique triple-image display system.

## Layout Structure

### Container Layout (100% width)
```
┌─────────────────────────────────────────────────────────────┐
│ ┌─────────────────────┐ ┌─────────────────────────────────┐ │
│ │                     │ │                                 │ │
│ │   Content Area      │ │      Image Display Area        │ │
│ │     (30%)           │ │         (70%)                   │ │
│ │   Elementor         │ │    ┌───┐ ┌─────┐ ┌───┐        │ │
│ │   Editable          │ │    │ 1 │ │  2  │ │ 3 │        │ │
│ │                     │ │    └───┘ └─────┘ └───┘        │ │
│ └─────────────────────┘ └─────────────────────────────────┘ │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │           Progress & Counter Component                   │ │
│ │  ███ ■■■ ■■■ ─── ───  [1 of 5]                         │ │
│ └─────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Component Specifications

### 1. Slide Management System

#### Elementor Interface Features:
- **Repeater Control**: Add/remove slides dynamically
- **Slide Counter**: Current slide indicator (e.g., "1 of 5")
- **Progress Bars**: Flat progress indicators for slide position
- **Auto-play Options**: Enable/disable automatic slide progression
- **Transition Effects**: Fade, slide, or custom transitions

#### Slide Data Structure:
```php
[
    'slide_title' => 'Slide Title',
    'image_1' => ['url' => '...', 'alt' => '...'],
    'image_2' => ['url' => '...', 'alt' => '...'],
    'image_3' => ['url' => '...', 'alt' => '...'],
    'content_template_id' => 123, // Elementor template ID
]
```

### 2. Content Area (30% Container)

#### Specifications:
- **Width**: 30% of total container
- **Content Type**: Elementor Template/Container
- **Editability**: Full Elementor widget support
- **Responsive**: Adjustable on mobile/tablet
- **Vertical Alignment**: Top, center, bottom options

#### Elementor Integration:
- Use Elementor's template system for content
- Allow users to create/select templates
- Support for all standard Elementor widgets
- Live editing capabilities within the slider context

#### Content Controls:
```php
'content_template' => [
    'label' => 'Content Template',
    'type' => \Elementor\Controls_Manager::SELECT2,
    'options' => get_elementor_templates(), // Dynamic template list
]
```

### 4. Progress & Counter Component

#### Position:
- **Location**: Below the main content container (30% area)
- **Width**: Spans the full width of the content area (30%)
- **Alignment**: Left-aligned within the content container
- **Spacing**: 20px margin-top from content area

#### Component Elements:

##### Progress Bars:
- **Display**: Horizontal row of flat bars/indicators
- **Height**: 3px high for all bars
- **Active State**: Filled bar (███) for current slide
- **Inactive State**: Outlined/lighter bar (───) for other slides
- **Interaction**: Clickable to navigate to specific slide
- **Animation**: Smooth transition between active states
- **Gap**: 8px between bars

##### Slide Counter:
- **Format**: "X of Y" (e.g., "3 of 8")
- **Position**: Right of progress bars
- **Typography**: Small, readable font
- **Update**: Real-time update on slide change

#### Component Layout:
```
┌───────────────────────────────────────────────────────┐
│ ███ ■■■ ■■■ ─── ─── ───    3 of 8                     │
│ ↑                          ↑                          │
│ Progress Bars              Counter                     │
└───────────────────────────────────────────────────────┘
```

#### CSS Structure:
```css
.mdb-slider-controls {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 20px;
    width: 100%;
}

.mdb-progress-bars {
    display: flex;
    gap: 8px;
    align-items: center;
}

.mdb-progress-bar {
    height: 3px;
    width: 30px;
    background-color: #ddd;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.mdb-progress-bar.active {
    background-color: #007cba;
}

.mdb-progress-bar:hover {
    background-color: #0073aa;
}

.mdb-slide-counter {
    font-size: 14px;
    color: #666;
    margin-left: auto;
}
```

### 3. Image Display Area (70% Container)

#### Layout Specifications:
- **Width**: 70% of total container
- **Image Count**: 3 images per slide
- **Gap**: 20px between images
- **Alignment**: Centrally aligned horizontally and vertically

#### Image Proportions & Sizing:

##### Image 1 (Left):
- **Height**: 100% (baseline/normal proportions)
- **Width**: Fixed width (same for all images)
- **Object-fit**: Cover (to maintain consistent width with cropping)
- **Position**: Left side of image container

##### Image 2 (Center):
- **Height**: 130% (30% taller than baseline)
- **Width**: Fixed width (same as other images)
- **Object-fit**: Cover (to maintain consistent width with cropping)
- **Position**: Center of image container
- **Visual Effect**: Creates emphasis as the hero image

##### Image 3 (Right):
- **Height**: 80% (20% shorter than baseline)
- **Width**: Fixed width (same as other images)
- **Object-fit**: Cover (to maintain consistent width with cropping)
- **Position**: Right side of image container

#### Image Container CSS Structure:
```css
.mdb-slider-images {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    height: 100%;
}

.mdb-slider-image {
    width: 150px; /* Fixed width for all images */
    object-fit: cover; /* Crop to maintain width */
    border-radius: 4px; /* Optional styling */
}

.mdb-image-1 { 
    height: 200px; /* Base height */
}

.mdb-image-2 { 
    height: 260px; /* 130% of base height */
}

.mdb-image-3 { 
    height: 160px; /* 80% of base height */
}
```

## Technical Implementation

### Widget Controls Structure

#### Content Tab:
1. **Slides Repeater**
   - Slide Title
   - Image 1 Upload
   - Image 2 Upload  
   - Image 3 Upload
   - Content Template Selection

2. **Slider Settings**
   - Auto-play toggle
   - Transition speed
   - Pause on hover
   - Loop slides

3. **Progress & Counter**
   - Show/hide progress bars
   - Show/hide slide counter
   - Progress bar width and colors
   - Bar spacing and alignment
   - Counter format options

#### Style Tab:
1. **Layout Controls**
   - Container height
   - Content/Image ratio adjustment
   - Responsive breakpoints

2. **Image Styling**
   - **Image Width**: Fixed width control (affects all images)
   - **Base Height**: Controls the baseline height (100% reference)
   - **Height Ratios**: Automatic (Image 1: 100%, Image 2: 130%, Image 3: 80%)
   - **Object Fit**: Cover (default) to maintain consistent width
   - Border radius
   - Box shadow
   - Hover effects
   - Image filters

3. **Content Area Styling**
   - Background options
   - Padding/margins
   - Border options

4. **Progress & Counter Styling**
   - Progress bar colors (active/inactive)
   - Progress bar width and height (fixed 3px height)
   - Counter typography and color
   - Component spacing and alignment
   - Hover effects and transitions

### Responsive Behavior

#### Desktop (1200px+):
- 30% / 70% split maintained
- All three images visible
- Full height proportions

#### Tablet (768px - 1199px):
- 40% / 60% split
- Reduced image gaps (15px)
- Smaller fixed image width (120px)
- Proportionally reduced heights maintaining ratios

#### Mobile (< 768px):
- Stack vertically OR
- 50% / 50% split
- Single image display (center image only) OR
- Horizontal scroll for images with smaller fixed width (100px)
- All images maintain same width but proportional heights
- Progress component maintains horizontal layout:
  - Progress bars on left
  - Counter on right
  - Smaller bar widths (20px instead of 30px)

### Performance Considerations

#### Image Optimization:
- Lazy loading for non-active slides
- WebP format support with fallbacks
- Responsive image srcset generation
- Image compression recommendations

#### JavaScript:
- Minimal DOM manipulation
- CSS transforms for transitions
- RequestAnimationFrame for smooth animations
- Intersection Observer for lazy loading

## Progress Component Functionality

### Interactive Features:

#### Progress Bars:
- **Click Navigation**: Click any bar to jump to that slide
- **Visual States**: 
  - Active: Filled bar (3px height) with primary color
  - Inactive: Lighter bar (3px height) with secondary color
  - Hover: Color change for feedback
- **Dimensions**: 30px width × 3px height (20px width on mobile)
- **Animation**: Smooth color transitions between states
- **Keyboard Support**: Tab navigation and Enter/Space to activate

#### Slide Counter:
- **Real-time Updates**: Automatically updates on slide change
- **Format Options**: 
  - "X of Y" (default)
  - "X / Y" 
  - "X | Y"
  - Custom separator via settings
- **Animation**: Fade or slide transition on number change

### Progress Component Controls:

#### Elementor Settings:
```php
// Show/Hide Controls
'show_progress_bars' => [
    'label' => 'Show Progress Bars',
    'type' => \Elementor\Controls_Manager::SWITCHER,
    'default' => 'yes',
],

'show_slide_counter' => [
    'label' => 'Show Slide Counter',
    'type' => \Elementor\Controls_Manager::SWITCHER,
    'default' => 'yes',
],

// Styling Controls
'bar_width' => [
    'label' => 'Progress Bar Width',
    'type' => \Elementor\Controls_Manager::SLIDER,
    'range' => ['px' => ['min' => 20, 'max' => 50]],
    'default' => ['size' => 30, 'unit' => 'px'],
],

'bar_active_color' => [
    'label' => 'Active Bar Color',
    'type' => \Elementor\Controls_Manager::COLOR,
    'default' => '#007cba',
],

'bar_inactive_color' => [
    'label' => 'Inactive Bar Color',
    'type' => \Elementor\Controls_Manager::COLOR,
    'default' => '#ddd',
],

'counter_format' => [
    'label' => 'Counter Format',
    'type' => \Elementor\Controls_Manager::SELECT,
    'options' => [
        'x_of_y' => 'X of Y',
        'x_slash_y' => 'X / Y',
        'x_pipe_y' => 'X | Y',
    ],
    'default' => 'x_of_y',
],

// Image Dimension Controls
'image_width' => [
    'label' => 'Image Width',
    'type' => \Elementor\Controls_Manager::SLIDER,
    'range' => ['px' => ['min' => 100, 'max' => 300]],
    'default' => ['size' => 150, 'unit' => 'px'],
    'selectors' => [
        '{{WRAPPER}} .mdb-slider-image' => 'width: {{SIZE}}{{UNIT}};',
    ],
],

'base_height' => [
    'label' => 'Base Height (Image 1)',
    'type' => \Elementor\Controls_Manager::SLIDER,
    'range' => ['px' => ['min' => 150, 'max' => 400]],
    'default' => ['size' => 200, 'unit' => 'px'],
    'selectors' => [
        '{{WRAPPER}} .mdb-image-1' => 'height: {{SIZE}}{{UNIT}};',
        '{{WRAPPER}} .mdb-image-2' => 'height: calc({{SIZE}}{{UNIT}} * 1.3);',
        '{{WRAPPER}} .mdb-image-3' => 'height: calc({{SIZE}}{{UNIT}} * 0.8);',
    ],
],
```

## User Experience Features

### Accessibility:
- ARIA labels for progress bars
- Keyboard navigation support
- Screen reader friendly
- Focus management
- Progress bars announced to screen readers
- Clear focus indicators on progress bars

### Touch/Mobile:
- Swipe gesture support
- Touch-friendly navigation
- Optimized touch targets

### Loading States:
- Skeleton loaders for images
- Progressive image loading
- Smooth transition between slides

## File Structure

```
includes/widgets/
├── slider-widget/
│   ├── slider-widget.php           # Main widget class
│   ├── slider-template.php         # Template rendering
│   └── slider-controls.php         # Control definitions
assets/
├── css/
│   └── slider-widget.css          # Widget-specific styles
└── js/
    └── slider-widget.js           # Widget functionality
```

## Future Enhancements

### Phase 2 Features:
- Video slide support
- Advanced transition effects
- Parallax scrolling options
- Multi-row image layouts

### Phase 3 Features:
- Dynamic content from ACF/Custom Fields
- E-commerce integration
- Analytics tracking
- A/B testing capabilities

## Development Notes

### Dependencies:
- Elementor Pro (for template system)
- Modern browser support (CSS Grid/Flexbox)
- jQuery (for compatibility)

### Browser Support:
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

### Performance Targets:
- First Contentful Paint < 2s
- Largest Contentful Paint < 2.5s
- Cumulative Layout Shift < 0.1