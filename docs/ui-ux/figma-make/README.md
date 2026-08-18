# ANS — Figma Make UI/UX Reference Package

## Purpose

This directory contains a **reference artifact** generated from the Figma Make high-fidelity prototype for the PT Abhipraya Nawasena Sejahtera (ANS) website.

The contents are intended to help developers and AI coding agents understand the approved UI/UX direction and prototype implementation.

## IMPORTANT — NOT PRODUCTION SOURCE CODE

This package is **NOT** the production frontend of the ANS Laravel application.

The production application architecture remains:

- Laravel 12
- Blade
- Livewire 4
- Tailwind CSS 4
- Vite 6
- Filament 5 for the administration panel

The Figma Make prototype was generated as a separate React/Vite prototype. Its source code must therefore **not be copied directly into the Laravel application**.

## Reference Priority

When implementing the production application, use the following order of authority:

1. Project Architecture / Architecture Lock
2. Feature Specifications
3. UI/UX Specification
4. This Figma Make reference package
5. Prototype implementation details as supplementary guidance

If a prototype implementation conflicts with the locked project architecture or specifications, the project architecture and specifications take precedence.

## Contents

### `reference/`

Contains selected prototype source files such as:

- `App.tsx`
- `index.css`
- `main.tsx`
- `index.html`
- `package.json`

These files are preserved for visual/component/interaction reference only.

### `assets/`

Contains selected visual assets from the prototype, such as the ANS logo.

### `specifications/`

Contains specification documents that were bundled by Figma Make with the generated prototype.

These are reference copies and should not silently replace the canonical documents under the project's `docs/` directory.

## What May Be Reused

The following may be used as implementation reference:

- page hierarchy
- layout composition
- spacing and sizing relationships
- typography direction
- color/design tokens
- responsive behavior
- component hierarchy
- interaction behavior
- navigation structure
- visual treatment of cards, buttons, forms, tables, banners, and other UI elements

## What Must NOT Be Reused Directly

Do not directly import or copy:

- React components
- React routing
- Figma Make-specific tooling
- Figma Make plugins
- React/Vite dependencies
- Figma-specific runtime configuration
- prototype mock data as production business data
- prototype-only infrastructure

Production components must be implemented using the project's locked Laravel architecture.

## Design Token Note

`reference/index.css` may contain useful design tokens such as colors, typography, spacing, and component styling.

Treat these as a visual reference and translate them into the existing Laravel/Tailwind architecture rather than replacing the project's frontend architecture.

## Mock Data Warning

The prototype may contain hardcoded sample products, categories, brands, images, and other demonstration content.

These are **prototype data only** and must not be treated as authoritative ANS business data.

Production business data must come from the application's database and the approved feature specifications.

## Source

Generated from:

`High-Fidelity Website Prototype.zip`

created by Figma Make.

## Repository Policy

This reference package is intentionally separated from production source code.

Do not run `npm install`, `npm run dev`, or `npm run build` from this directory as part of the Laravel application's normal development workflow.
