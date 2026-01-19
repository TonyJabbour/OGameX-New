---
trigger: file_match
patterns:
    - "resources/views/**/*.blade.php"
    - "public/css/**/*.css"
    - "public/js/**/*.js"
    - "resources/css/**"
    - "resources/js/**"

description: UI/UX enhancement guidelines
---

✅ UI Enhancement - Full Freedom
You Can Freely Modify:
All Blade templates

All CSS/styling

JavaScript for UI interactions

Images and assets

Layout structures

Responsive design

Animations and transitions

Modern Design Principles:
Clean & Modern: Contemporary web aesthetics

Responsive: Mobile-first approach

Accessible: WCAG 2.1 AA compliance

Performance: Optimized assets, lazy loading

Consistent: Unified design language

CSS Best Practices:
Use CSS custom properties (variables)

Prefix custom classes: ogx-

BEM methodology: block__element--modifier

Mobile-first media queries

Semantic color variables

Example:

css
:root {
    --ogx-primary: #3b82f6;
    --ogx-success: #10b981;
    --ogx-spacing-md: 1rem;
}

.ogx-card {
    padding: var(--ogx-spacing-md);
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
Testing Checklist:
 Desktop (1920x1080, 1366x768)

 Tablet (768x1024)

 Mobile (375x667, 414x896)

 Cross-browser (Chrome, Firefox, Safari)

 Keyboard navigation