# Consistency Guidelines

## Layout & Container Styling

- Use consistent container classes across pages: `flex h-full flex-1 flex-col gap-4 rounded-xl p-4`
- Avoid `overflow-x-auto` on page containers when content is already responsive (uses `flex-wrap` or responsive grids)
- Use `overflow-hidden` on individual elements that need clipping, not on page-level containers

## Responsive Design

- Prefer `flex-wrap` for flexible layouts that adapt to screen size
- Use responsive grid classes like `md:grid-cols-3` for structured layouts
- Ensure all layouts work without horizontal scrolling on standard viewports

## Component Spacing

- Use `gap-4` for consistent spacing between major sections
- Use `gap-2` for tighter spacing within component groups (e.g., badges, small items)

## Border & Visual Styling

- Use `border-sidebar-border/70` for light mode borders
- Use `dark:border-sidebar-border` for dark mode borders
- Apply `rounded-xl` for card-like containers
