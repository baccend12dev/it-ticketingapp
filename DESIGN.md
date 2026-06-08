---
name: Infrastructure Management Core
colors:
  surface: '#f3fcef'
  surface-dim: '#d4ddd0'
  surface-bright: '#f3fcef'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#edf6ea'
  surface-container: '#e8f0e4'
  surface-container-high: '#e2ebde'
  surface-container-highest: '#dce5d9'
  on-surface: '#161d16'
  on-surface-variant: '#3d4a3d'
  inverse-surface: '#2a322a'
  inverse-on-surface: '#ebf3e7'
  outline: '#6d7b6c'
  outline-variant: '#bccbb9'
  surface-tint: '#006e2f'
  primary: '#006e2f'
  on-primary: '#ffffff'
  primary-container: '#22c55e'
  on-primary-container: '#004b1e'
  inverse-primary: '#4ae176'
  secondary: '#575e70'
  on-secondary: '#ffffff'
  secondary-container: '#d9dff5'
  on-secondary-container: '#5c6274'
  tertiary: '#9e4036'
  on-tertiary: '#ffffff'
  tertiary-container: '#ff8b7c'
  on-tertiary-container: '#76231b'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#6bff8f'
  primary-fixed-dim: '#4ae176'
  on-primary-fixed: '#002109'
  on-primary-fixed-variant: '#005321'
  secondary-fixed: '#dce2f7'
  secondary-fixed-dim: '#c0c6db'
  on-secondary-fixed: '#141b2b'
  on-secondary-fixed-variant: '#404758'
  tertiary-fixed: '#ffdad5'
  tertiary-fixed-dim: '#ffb4a9'
  on-tertiary-fixed: '#410001'
  on-tertiary-fixed-variant: '#7f2a21'
  background: '#f3fcef'
  on-background: '#161d16'
  surface-variant: '#dce5d9'
  success: '#22C55E'
  info: '#3B82F6'
  warning: '#F59E0B'
  danger: '#EF4444'
  surface-muted: '#F9FAFB'
  border-light: '#E5E7EB'
typography:
  display:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.4'
    letterSpacing: -0.02em
  h1:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '700'
    lineHeight: '1.4'
  h2:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  body:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  body-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: '1.4'
  caption:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '400'
    lineHeight: '1.4'
    letterSpacing: 0.01em
  label:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: '1'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  sidebar-width: 240px
  header-height: 56px
---

## Brand & Style

The design system is engineered for efficiency, clarity, and reliability in high-stakes IT operational environments. The brand personality is professional and utilitarian, prioritizing information density without sacrificing legibility. 

The visual style is **Corporate / Modern**, characterized by a systematic approach to whitespace, a crisp green-focused palette, and a focus on data visualization. It utilizes subtle elevation and soft rounded corners to create a friendly but orderly workspace. The goal is to evoke a sense of calm control, allowing IT administrators to process complex ticketing data and infrastructure metrics with minimal cognitive load.

## Colors

The palette is anchored by a vibrant **Success Green**, which serves as the primary brand identifier and indicates healthy system states. This is balanced against a deep **Navy Neutral** used for high-contrast typography and structural elements.

- **Primary:** Used for main actions, active states, and "Healthy/Resolved" statuses.
- **Secondary:** Used for sidebar backgrounds, primary headings, and grounding the interface.
- **Surface & Backgrounds:** A range of near-white grays (`#F9FAFB`) provides a soft canvas for cards and data tables.
- **Semantic Palette:** Standardized Info (Blue), Warning (Amber), and Danger (Red) colors are used strictly for system alerts and priority-level ticketing status.

## Typography

This design system exclusively utilizes **Inter** to maintain a clean, highly legible, and technical appearance. The type scale is optimized for data-heavy dashboard interfaces.

- **Headlines:** Use Bold (700) weights for page titles and Semi-Bold (600) for section headers.
- **Body:** Standardized at 14px for optimal balance between density and readability.
- **Captions & Labels:** Small utility text should use the `body-sm` or `caption` styles to maintain hierarchy in complex forms.
- **Letter Spacing:** Tighter tracking is applied to display headers for a more "locked-in" feel, while captions receive a slight increase in spacing for legibility at small sizes.

## Layout & Spacing

The system follows a **4px base grid** to ensure mathematical consistency across all margins, paddings, and component dimensions. 

- **Layout Model:** A **fixed-sidebar fluid-content** approach. The 240px sidebar stays persistent on the left for navigation, while the main content area utilizes a fluid grid that adapts to the viewport.
- **Grid:** Use a 12-column grid for the content area with 16px (md) gutters for desktop.
- **Breakpoints:**
  - **Mobile:** Single column layout, sidebar collapses into a hamburger menu. Margins reduce to 16px.
  - **Tablet:** 12-column grid, sidebar may become an icon-only rail (64px).
  - **Desktop:** Full navigation sidebar (240px) with content margins of 32px (xl).

## Elevation & Depth

Visual hierarchy is established through a combination of **Tonal Layering** and **Soft Ambient Shadows**.

- **Level 0 (Background):** The application background uses `#F9FAFB`.
- **Level 1 (Cards/Surfaces):** Main content containers are pure white (`#FFFFFF`) with a subtle border (`#E5E7EB`) or a very soft shadow to distinguish them from the background.
- **Shadow Styles:** 
  - `sm`: Used for small interactive elements like buttons. (0 1px 2px rgba(0,0,0,0.05))
  - `md`: Used for standard cards and dropdowns. (0 4px 8px rgba(0,0,0,0.06))
  - `lg`: Reserved for modals and critical pop-overs. (0 8px 24px rgba(0,0,0,0.08))

## Shapes

The shape language uses a **Rounded** aesthetic (8px default) to soften the technical nature of the application.

- **Standard (8px):** Buttons, Input fields, and standard Card containers.
- **Large (16px):** Outer wrappers or feature-heavy dashboard panels.
- **Pill (Full):** Used exclusively for status badges and tags (e.g., Ticket Status: "Open", "Pending").

## Components

- **Buttons:** Primary buttons use a solid green background with white text. Ghost buttons use green text with no background. All buttons have an 8px corner radius and a 40px height for standard actions.
- **Inputs:** Form fields feature a 1px border (`#D1D5DB`), 8px radius, and use the `body` type size. Focus states are indicated by a 2px primary green ring.
- **Cards:** White backgrounds, subtle `md` shadows, and 8px padding-x for content. Card headers should be separated by a light border line.
- **Status Badges:** High-contrast pill-shaped tags. The background should be a light tint (10-15% opacity) of the semantic color, with the text using the full-saturation semantic color for readability.
- **Tables:** Use a "zebra-stripe" or clean-border style. Headers should be `body-sm` with a medium-gray color and Semi-Bold weight.
- **Sidebar:** Dark background (`#111827`) with active navigation items highlighted using a green left-border accent or a low-opacity green background tint.