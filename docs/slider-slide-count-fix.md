# Slider Widget - Slide Count Bug Fix

**Date**: October 20, 2025  
**Issue**: Slider shows "1/3" initially but changes to "2/9", "3/9" after clicking
**Status**: ✅ FIXED

## Problem Description

When a slider widget has 3 slides with 3 images each:
- **Initial state**: Counter shows "1 of 3" ✅ (correct)
- **After clicking**: Counter shows "2 of 9", "3 of 9" ❌ (wrong)

## Root Cause

The JavaScript was counting ALL elements with `[data-slide]` attribute:

```javascript
// BEFORE (BUGGY):
this.totalSlides = this.$element.find("[data-slide]").length;
```

### What Gets Counted

For a slider with 3 slides, the HTML structure has:

1. **Content slides**: 3× `.mdb-slide-content[data-slide]` (0, 1, 2)
2. **Image slides**: 3× `.mdb-slide-images[data-slide]` (0, 1, 2)
3. **Progress bars**: 3× `.mdb-progress-bar[data-slide]` (0, 1, 2)

**Total**: 9 elements with `[data-slide]` 🐛

### Why It Showed "1/3" Initially

The widget PHP passes the correct count via `data-settings`:

```php
$widget_settings = array(
    'totalSlides' => $slide_count,  // Correctly set to 3
    // ... other settings
);
```

This is used for the **initial render** of the counter in PHP:

```php
<?php echo $this->get_counter_text(1, count($slides), $settings['counter_format']); ?>
// Outputs: "1 of 3"
```

### Why It Changed After Clicking

When JavaScript takes over navigation, it recalculates using:

```javascript
this.totalSlides = this.$element.find("[data-slide]").length;
// Returns 9 instead of 3!
```

This overwrites the correct value from the config, so the counter shows "2 of 9", "3 of 9", etc.

## Solution

Changed the selector to count **only content slides**:

```javascript
// AFTER (FIXED):
this.totalSlides = this.$element.find(".mdb-slide-content[data-slide]").length;
```

This ensures we count each slide only once, ignoring the duplicate `data-slide` attributes on images and progress bars.

## Files Modified

### ✅ `/assets/js/slider-widget.js`

**Line 20** (in constructor):

```javascript
// OLD:
this.totalSlides = this.$element.find("[data-slide]").length;

// NEW:
// Count only .mdb-slide-content elements to avoid counting duplicates
// (both content and images have data-slide attributes)
this.totalSlides = this.$element.find(".mdb-slide-content[data-slide]").length;
```

## Testing

### Test Case 1: 3 Slides
- ✅ Initial load: "1 of 3"
- ✅ Click next: "2 of 3"
- ✅ Click next: "3 of 3"
- ✅ Loop back: "1 of 3"

### Test Case 2: 5 Slides
- ✅ Initial load: "1 of 5"
- ✅ Navigate: "2 of 5", "3 of 5", "4 of 5", "5 of 5"

### Test Case 3: 1 Slide
- ✅ Shows: "1 of 1"
- ✅ No navigation (only one slide)

## Why This Bug Happened

The HTML structure uses `data-slide` attributes on multiple element types for synchronization:

```html
<!-- Content slide -->
<div class="mdb-slide-content" data-slide="0">...</div>

<!-- Image slide (same index) -->
<div class="mdb-slide-images" data-slide="0">...</div>

<!-- Progress bar (same index) -->
<div class="mdb-progress-bar" data-slide="0"></div>
```

The original code assumed `[data-slide]` would only match slide content, but it actually matched all three types.

## Prevention

To prevent similar issues in the future:

1. **Use specific selectors** when counting elements
2. **Avoid generic attribute selectors** like `[data-*]` when duplicates exist
3. **Test navigation** not just initial render
4. **Log totalSlides** value during debugging

## Alternative Solutions Considered

### Option 1: Remove data-slide from images/progress bars
**Rejected**: Would require refactoring all click handlers and animations

### Option 2: Use a different attribute
**Rejected**: Would require HTML and CSS changes

### Option 3: Count and divide by 3
**Rejected**: Fragile, would break if structure changes

### Option 4: Use specific selector ✅
**Chosen**: Minimal change, clear intent, robust

## Verification Steps

1. **Clear browser cache**
2. **Reload page with slider**
3. **Check initial counter**: Should show "1 of [N]" where N = actual slide count
4. **Click navigation**: Counter should increment correctly
5. **Loop through all slides**: Numbers should stay consistent

## Debug Commands

To verify the fix in browser console:

```javascript
// Get slider instance
const slider = $('.mdb-slider-widget').data('mdb-slider');

// Check totalSlides
console.log('Total Slides:', slider.totalSlides);

// Count elements manually
console.log('Content slides:', $('.mdb-slide-content[data-slide]').length);
console.log('Image slides:', $('.mdb-slide-images[data-slide]').length);
console.log('Progress bars:', $('.mdb-progress-bar[data-slide]').length);
console.log('All [data-slide]:', $('[data-slide]').length);
```

Expected output for 3 slides:
```
Total Slides: 3
Content slides: 3
Image slides: 3
Progress bars: 3
All [data-slide]: 9
```

## Related Code

The config system has a fallback that uses `totalSlides` from data-settings:

```javascript
getConfig() {
    return {
        // ... other config
        totalSlides: config.totalSlides || this.totalSlides,
    };
}
```

However, since `this.totalSlides` is set in the constructor BEFORE `getConfig()` is called, the buggy value (9) was already in place and overrode the correct value from `config.totalSlides` (3).

## Impact

- ✅ **No breaking changes**
- ✅ **No API changes**
- ✅ **No CSS changes**
- ✅ **Backward compatible**
- ✅ **Works with existing sliders**

Users will see the fix immediately after clearing cache - no data migration needed.
