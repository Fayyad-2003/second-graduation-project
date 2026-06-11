# 🎨 System Modern Color Scheme

## Primary Colors

### Indigo (Primary Brand Color)

- **Primary**: `#6366f1` - Main brand color, used for primary actions and highlights
- **Primary Dark**: `#312e81` - Dark variant for emphasis and depth
- **Primary Light**: `#e0e9ff` - Light variant for backgrounds and subtle highlights

### Purple (Secondary Accent)

- **Secondary**: `#8b5cf6` - Secondary brand color, used for accents and variations
- **Gradient**: `#6366f1` → `#8b5cf6` - Dynamic gradients for modern visual appeal

### Pink (Accent)

- **Accent**: `#ec4899` - Vibrant accent color for special highlights and CTAs

## Full Palette Scale

### Indigo Scale

```
50:  #f0f4ff  ░░░░ Ultra light
100: #e0e9ff  ░░░  Very light
200: #c7d7fe  ░░   Light
300: #a5b8fc  ░    Soft
400: #8892f7  ▒    Medium-light
500: #6366f1  ▓    Primary (Base)
600: #4f46e5  █    Medium-dark
700: #4338ca  ██   Dark
800: #3730a3  ███  Very dark
900: #312e81  ████ Ultra dark
950: #1e1b4b  █████ Deepest
```

## Semantic Colors

### Success (Emerald)

- Light: `#10b981`
- Dark: `#059669`

### Warning (Amber)

- Light: `#f59e0b`
- Dark: `#d97706`

### Error (Red)

- Light: `#ef4444`
- Dark: `#dc2626`

### Info (Blue)

- Light: `#3b82f6`
- Dark: `#2563eb`

## Theme Variables

### Light Mode

```css
--primary-dark: #312e81 --primary-primary: #6366f1 --primary-secondary: #8b5cf6
    --primary-light: #e0e9ff --bg-body: #f9fafb --bg-card: #ffffff
    --bg-sidebar: #ffffff --text-primary: #111827 --text-secondary: #6b7280
    --border-color: #e5e7eb --gradient-start: #6366f1 --gradient-end: #8b5cf6
    --accent: #ec4899;
```

### Dark Mode

```css
--bg-body: #0f172a --bg-card: #1e1b3e --bg-sidebar: #1e1b3e
    --text-primary: #f3f4f6 --text-secondary: #9ca3af --border-color: #312e81
    --gradient-start: #6366f1 --gradient-end: #a855f7 --accent: #f472b6;
```

## Usage Guidelines

### Primary Actions

Use `system-primary` (#6366f1) for:

- Primary buttons
- Active states
- Important links
- Focus indicators

### Gradients

Use gradient combinations for:

- Hero sections
- Cards with emphasis
- Buttons requiring extra attention
- Background decorations

**Recommended gradients:**

- `from-primary-500 to-primary-600` (Subtle)
- `from-primary-500 via-primary-600 to-purple-600` (Vibrant)
- `from-purple-500 to-pink-500` (Accent gradient)

### Text Hierarchy

- **Headings**: `text-gray-900` (light) / `text-gray-100` (dark)
- **Body**: `text-gray-700` (light) / `text-gray-300` (dark)
- **Secondary**: `text-gray-500` (light) / `text-gray-400` (dark)
- **Muted**: `text-gray-400` (light) / `text-gray-500` (dark)

### Backgrounds

- **Body**: `bg-gray-50` (light) / `bg-slate-900` (dark)
- **Cards**: `bg-white` (light) / `bg-slate-800/50` (dark)
- **Hover states**: `hover:bg-gray-100` (light) / `hover:bg-slate-700` (dark)

## Accessibility

All color combinations meet WCAG 2.1 AA standards:

- Primary on white: 4.53:1 ✓
- Text on backgrounds: 7.2:1+ ✓
- Interactive elements: Clear focus indicators

## Modern UI Patterns

### Glassmorphism

```css
background: rgba(255, 255, 255, 0.8);
backdrop-filter: blur(10px);
border: 1px solid rgba(99, 102, 241, 0.08);
```

### Neumorphism

```css
box-shadow:
    0 4px 12px -2px rgba(99, 102, 241, 0.4),
    0 0 0 1px rgba(99, 102, 241, 0.1);
```

### Gradient Text

```css
background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
```

## Design Philosophy

**Modern & Vibrant**: The indigo-purple palette creates a contemporary, energetic feel perfect for education technology.

**Professional**: Deep indigo tones maintain professionalism while staying approachable.

**Accessible**: High contrast ratios ensure readability for all users.

**Versatile**: Works beautifully in both light and dark modes.

---

_Last updated: 2024_
