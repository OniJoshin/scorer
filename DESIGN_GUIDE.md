# Scorer Design System

Mobile-first design system for Scorer board game tracking app with light/dark theme support.

## Design Principles

1. **Mobile-First**: Optimized for touch interactions, thumb-friendly tap zones
2. **Clean & Focused**: Minimal chrome, content-first approach
3. **Accessible**: WCAG AA contrast ratios, clear visual hierarchy
4. **Fast & Responsive**: Instant feedback, smooth transitions
5. **App-Native Feel**: Bottom tabs on mobile, persistent navigation on desktop

---

## Icon System

**Library**: [Heroicons v2](https://heroicons.com/) - Outline style (24px) for navigation, Solid style (20px) for UI elements

**Usage**: Inline SVG via CDN or blade components

**Primary Icons:**
- Home: `home`
- Sessions: `puzzle-piece`
- Games: `cube`
- Players: `user-group`
- More: `ellipsis-horizontal`
- Settings: `cog-6-tooth`
- Leaderboard: `trophy`
- Scores: `star`
- Add: `plus-circle`
- Edit: `pencil-square`
- Delete: `trash`
- Complete: `check-circle`
- Search: `magnifying-glass`
- Filter: `funnel`
- Calendar: `calendar`
- Light Mode: `sun`
- Dark Mode: `moon`
- Auto Theme: `computer-desktop`

---

## Color System

### Light Theme

**Backgrounds:**
```css
--color-bg-primary: #FFFFFF       /* Main content area */
--color-bg-secondary: #F9FAFB     /* Secondary surfaces, alternating rows */
--color-bg-tertiary: #F3F4F6      /* Disabled states, subtle backgrounds */
--color-bg-elevated: #FFFFFF      /* Cards, modals (with shadow) */
```

**Text:**
```css
--color-text-primary: #111827     /* Body text, headings */
--color-text-secondary: #6B7280   /* Supporting text, labels */
--color-text-tertiary: #9CA3AF    /* Placeholder text, disabled */
```

**Brand (Purple):**
```css
--color-primary: #7C3AED          /* Primary buttons, active states */
--color-primary-hover: #6D28D9    /* Button hover */
--color-primary-light: #EDE9FE    /* Light backgrounds, badges */
--color-primary-dark: #5B21B6     /* Dark text, borders */
```

**Semantic:**
```css
--color-success: #10B981          /* Success messages, positive indicators */
--color-success-bg: #D1FAE5
--color-error: #EF4444            /* Errors, destructive actions */
--color-error-bg: #FEE2E2
--color-warning: #F59E0B          /* Warnings, alerts */
--color-warning-bg: #FEF3C7
--color-info: #3B82F6             /* Info messages */
--color-info-bg: #DBEAFE
```

**Borders & Dividers:**
```css
--color-border: #E5E7EB           /* Subtle borders */
--color-border-dark: #D1D5DB      /* Prominent borders */
--color-divider: #F3F4F6          /* Section dividers */
```

### Dark Theme

**Backgrounds:**
```css
--color-bg-primary: #111827       /* Main content area */
--color-bg-secondary: #1F2937     /* Secondary surfaces */
--color-bg-tertiary: #374151      /* Disabled states */
--color-bg-elevated: #1F2937      /* Cards, modals (with border) */
```

**Text:**
```css
--color-text-primary: #F9FAFB     /* Body text, headings */
--color-text-secondary: #D1D5DB   /* Supporting text, labels */
--color-text-tertiary: #9CA3AF    /* Placeholder, disabled */
```

**Brand (Purple):**
```css
--color-primary: #8B5CF6          /* Brighter in dark mode */
--color-primary-hover: #A78BFA
--color-primary-light: #312E81    /* Darker in dark mode */
--color-primary-dark: #C4B5FD     /* Lighter in dark mode */
```

**Semantic:** (Adjusted for dark mode visibility)
```css
--color-success: #34D399
--color-success-bg: #064E3B
--color-error: #F87171
--color-error-bg: #7F1D1D
--color-warning: #FBBF24
--color-warning-bg: #78350F
--color-info: #60A5FA
--color-info-bg: #1E3A8A
```

**Borders & Dividers:**
```css
--color-border: #374151
--color-border-dark: #4B5563
--color-divider: #1F2937
```

---

## Typography

**Font Family**: 'Instrument Sans' (custom), fallback: system-ui, sans-serif

**Scale** (Mobile-first, larger on desktop):

| Element | Mobile | Desktop | Weight | Usage |
|---------|---------|---------|--------|-------|
| Display | 28px | 36px | 700 | Hero sections, marketing |
| H1 | 24px | 32px | 700 | Page titles |
| H2 | 20px | 24px | 600 | Section headings |
| H3 | 18px | 20px | 600 | Card titles |
| Body Large | 16px | 18px | 400 | Prominent body text |
| Body | 14px | 16px | 400 | Default body text |
| Caption | 12px | 14px | 400 | Labels, metadata |
| Small | 10px | 12px | 400 | Tiny labels, legal text |

**Line Heights:**
- Headings: 1.2
- Body: 1.5
- Captions: 1.4

**Letter Spacing:**
- Headings: -0.02em
- Body: 0
- All Caps: 0.05em

---

## Spacing System

**Base Unit**: 4px

**Scale**: 0, 1, 2, 3, 4, 5, 6, 8, 10, 12, 16, 20, 24, 32, 40, 48, 64

**Common Usage:**
- Tight: 8px (between related items)
- Default: 16px (standard padding, gaps)
- Comfortable: 24px (section spacing)
- Loose: 32px (page margins)
- Extra Loose: 48px+ (dramatic separation)

**Component Padding:**
- Buttons: 12px vertical, 20px horizontal (min 44px height)
- Inputs: 12px vertical, 16px horizontal
- Cards: 16px (mobile), 20px (desktop)
- Page Margins: 16px (mobile), 24px (tablet), 32px (desktop)

**Bottom Navigation Clearance**: 80px padding-bottom on main content when bottom tabs visible

---

## Layout Structure

### Mobile (< 768px)

```
┌─────────────────────────┐
│   Main Content Area     │
│   (full width)          │
│   pb-20 for tab bar     │
│                         │
│                         │
├─────────────────────────┤
│ ■ Home  ■ Sessions      │  ← Bottom Tab Bar (fixed)
│ ■ Games ■ Players ■ More│     height: 64px
└─────────────────────────┘
```

### Desktop (≥ 768px)

```
┌──────┬──────────────────┐
│ Nav  │  Main Content    │
│ Bar  │  (left-padded)   │
│(64px)│                  │
│fixed │                  │
│left  │                  │
└──────┴──────────────────┘
```

**Breakpoints:**
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: ≥ 1024px

---

## Components

### Bottom Tab Bar

**Structure:**
- 5 tabs maximum
- Icon (24px) + Label (12px)
- Active state: primary color + bolder label
- Inactive: tertiary text color
- Tap target: full tab width × 64px height

**Layout:**
```
[Icon]     [Icon]     [Icon]     [Icon]     [Icon]
 Home     Sessions   Games     Players     More
```

**Active Indicator**: Fill icon with primary color, bold label text

### Buttons

**Primary Button:**
- Background: `--color-primary`
- Text: white
- Height: 44px minimum
- Border radius: 8px
- Padding: 12px 20px
- Font: Body weight 600
- Hover: `--color-primary-hover`
- Active: scale(0.98), darker shade

**Secondary Button:**
- Background: transparent
- Border: 2px solid `--color-border-dark`
- Text: `--color-text-primary`
- Same sizing as primary
- Hover: `--color-bg-secondary` background

**Ghost Button:**
- Background: transparent
- Text: `--color-primary`
- No border
- Hover: `--color-primary-light` background

**Floating Action Button (FAB):**
- Circle, 56px diameter
- Background: `--color-primary`
- Icon: white (24px)
- Shadow: 0 4px 12px rgba(0,0,0,0.15)
- Position: fixed bottom-24 right-6
- Hover: lift with larger shadow

### Cards

**Default Card:**
- Background: `--color-bg-elevated`
- Border radius: 12px
- Padding: 16px (mobile), 20px (desktop)
- Shadow (light mode): 0 1px 3px rgba(0,0,0,0.1)
- Border (dark mode): 1px solid `--color-border`

**Interactive Card** (tappable):
- Add hover state: transform: translateY(-2px), shadow increase
- Active state: transform: scale(0.98)
- Cursor: pointer

**List Item Card:**
- Horizontal layout
- Avatar/Icon (40px) | Content | Action (chevron)
- Height: 72px minimum
- Divider between items

### Form Inputs

**Text Input:**
- Height: 44px
- Border: 1px solid `--color-border`
- Border radius: 8px
- Padding: 12px 16px
- Font: Body
- Focus: 2px solid `--color-primary`, remove border
- Dark mode: background `--color-bg-secondary`

**Label:**
- Caption size
- Color: `--color-text-secondary`
- Margin bottom: 8px

**Floating Label** (optional):
- Position absolute, transitions to top on focus/filled
- Font size shrinks from Body to Caption

**Checkbox/Radio:**
- Size: 20px (mobile), 18px (desktop)
- Border: 2px solid `--color-border-dark`
- Checked: `--color-primary` background with checkmark
- Spacing: 12px from label

**Select/Dropdown:**
- Same styling as text input
- Arrow icon (16px) on right
- Mobile: Use native `<select>` for OS picker

### Modal/Bottom Sheet

**Mobile (< 768px) - Bottom Sheet:**
- Slides up from bottom
- Rounded top corners: 20px
- Max height: 90vh
- Handle bar at top (optional)
- Backdrop: rgba(0,0,0,0.5)

**Desktop (≥ 768px) - Modal:**
- Centered overlay
- Max width: 600px
- Border radius: 16px
- Shadow: 0 20px 25px rgba(0,0,0,0.15)
- Padding: 24px

**Close Button:**
- Top-right corner
- Ghost button style
- Icon: X (20px)

### Lists

**Standard List Item:**
- Height: 60-72px
- Padding: 12px 16px
- Divider: 1px solid `--color-divider`
- Content: Title (Body) + Subtitle (Caption, secondary text)

**Avatar List Item:**
- Avatar: 40px circle, left aligned
- Content: 12px margin left
- Action: Chevron icon (20px), right aligned

**Swipe Actions** (mobile):
- Swipe left: Edit action (blue background)
- Swipe right: Delete action (red background)
- Icon + label appear on swipe

### Navigation

**Active Route Detection:**
- Check current route in Blade: `{{ request()->routeIs('routename') ? 'active-class' : '' }}`
- Active tab: Bold text, primary color icon
- Inactive tab: Regular text, secondary color icon

---

## Animation Guidelines

**Duration:**
- Instant: 0ms (toggle states)
- Quick: 150ms (hover, small movements)
- Normal: 250ms (page transitions, modals)
- Slow: 400ms (large movements, complex animations)

**Easing:**
- UI Elements: `ease-in-out`
- Enter: `ease-out`
- Exit: `ease-in`
- Bouncy (optional): `cubic-bezier(0.68, -0.55, 0.265, 1.55)`

**Types:**
- Fade: opacity 0 → 1
- Slide: transform translateY/translateX
- Scale: transform scale(0.95) → 1
- Lift: translateY(0) → translateY(-4px) + shadow increase

**Disable Animations:**
- Respect user preference: `prefers-reduced-motion: reduce`
- Settings toggle: add `.no-animations` class to body

---

## Touch Targets

**Minimum Size:** 44px × 44px (Apple/WCAG recommendation)

**Preferred Size:** 48px × 48px (Material Design)

**Spacing Between Targets:** 8px minimum

**Safe Zones:**
- Bottom 80px: Reserved for tab bar (avoid placing important actions)
- Top 44px: Consider system status bar on mobile
- Edges: 16px margin from screen edges

**Thumb Zones** (right-handed priority):
- Easy: Bottom-right corner, center screen
- Hard: Top-left corner
- Position FABs and primary actions in easy zones

---

## Page Templates

### List Page (Index)

**Structure:**
```
┌─────────────────────────┐
│ [Search/Filter Bar]     │  ← Sticky
├─────────────────────────┤
│ ─────────────────────   │  ← Section Header
│ □ List Item             │
│ □ List Item             │
│ □ List Item             │
│ ...                     │
│                    (●)  │  ← FAB (fixed)
└─────────────────────────┘
```

**Elements:**
- Page title: H1 (optional, can be in nav)
- Search bar: Sticky at top
- FAB: "Add Item" (bottom right)
- Empty state: Illustration + message + CTA button
- Loading state: Skeleton cards

### Detail Page (Show)

**Structure:**
```
┌─────────────────────────┐
│ ← Back    Title   Edit  │  ← Header
├─────────────────────────┤
│ ■ Main Content Card     │
│   Details, info         │
├─────────────────────────┤
│ ■ Related Items         │
│ ■ Actions               │
└─────────────────────────┘
```

**Elements:**
- Back button (top-left)
- Page title (center or left)
- Action button (top-right, Edit/Save/Complete)
- Content cards with spacing
- Sticky action buttons (bottom, above tab bar)

### Form Page (Create/Edit)

**Structure:**
```
┌─────────────────────────┐
│ Cancel    Title    Save │  ← Header
├─────────────────────────┤
│ ▼ Section 1             │  ← Collapsible sections
│   [Input Field]         │
│   [Input Field]         │
│                         │
│ ▶ Section 2             │
│                         │
│ [Delete Button]         │  ← Danger zone (edit only)
└─────────────────────────┘
```

**Elements:**
- Cancel button (top-left)
- Save button (top-right, primary style)
- Form sections (collapsible on mobile)
- Input fields (full width, stacked)
- Delete button at bottom (edit mode only)
- Validation errors inline below fields

---

## Accessibility

**Color Contrast:**
- Normal text (16px): 4.5:1 minimum
- Large text (18px+): 3:1 minimum
- UI elements: 3:1 minimum

**Focus Indicators:**
- 2px solid outline
- Color: `--color-primary`
- Offset: 2px from element
- Never remove focus styles

**Screen Readers:**
- Semantic HTML (nav, main, section)
- ARIA labels for icons: `aria-label="Home"`
- Hidden text for icon-only buttons: `.sr-only` class
- Form labels: Always associate `<label for="id">`
- Status messages: `role="alert"` or `aria-live="polite"`

**Keyboard Navigation:**
- Tab order: Logical flow
- Skip links: "Skip to main content"
- Modal focus trap: Keep focus within modal
- Enter/Space: Activate buttons
- Escape: Close modals/dropdowns

---

## Implementation Notes

### Heroicons Integration

**Option 1: CDN (Quick)**
```html
<!-- In layout head -->
<script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/24/outline/index.js"></script>
```

**Option 2: Blade Components (Recommended)**
```bash
composer require blade-ui-kit/blade-heroicons
```

Usage:
```blade
<x-heroicon-o-home class="w-6 h-6" />
<x-heroicon-s-plus class="w-5 h-5" />
```

### Tailwind CSS v4 Theme Integration

All color tokens will be defined in `resources/css/app.css` using `@theme` directive:

```css
@theme {
  /* Colors */
  --color-primary: #7C3AED;
  
  /* Reference in Tailwind */
  --color-bg-primary: light-dark(#FFFFFF, #111827);
}
```

Use in HTML:
```html
<div class="bg-primary text-white">
<!-- or -->
<div class="bg-[var(--color-bg-primary)]">
```

### Alpine.js Theme Manager

Global component initialized in layout:
```js
Alpine.data('themeManager', () => ({
  theme: localStorage.getItem('theme') || 'auto',
  setTheme(value) { /* implementation */ }
}))
```

### NativePHP Settings

Store theme preference:
```php
use Native\Laravel\Facades\Settings;

Settings::set('theme', 'dark');
$theme = Settings::get('theme', 'auto');
```

---

## Design Checklist

When implementing each page, verify:

- [ ] Mobile-first CSS (default styles for mobile, `md:` for desktop)
- [ ] All tap targets ≥ 44px
- [ ] Icons use Heroicons with proper size
- [ ] Colors use theme variables, support light/dark
- [ ] Text sizes follow typography scale
- [ ] Spacing uses 4px base unit
- [ ] Navigation highlights active route
- [ ] Forms have proper labels and validation
- [ ] Empty states have illustrations + CTA
- [ ] Loading states use skeletons or spinners
- [ ] Animations respect `prefers-reduced-motion`
- [ ] Color contrast meets WCAG AA
- [ ] Focus indicators visible
- [ ] Screen reader labels on icon buttons

---

## Resources

- **Heroicons**: https://heroicons.com/
- **Tailwind CSS v4**: https://tailwindcss.com/docs
- **Alpine.js**: https://alpinejs.dev/
- **WCAG Contrast Checker**: https://webaim.org/resources/contrastchecker/
- **Touch Target Sizes**: https://web.dev/accessible-tap-targets/
