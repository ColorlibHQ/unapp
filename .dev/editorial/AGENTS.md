# Editorial presentation

- Reuse Colorlib's existing article components. Never introduce a separate button, badge, screenshot, disclosure or feature-card design for a new theme entry.
- Match the surrounding post: `template-card` feature cards, plain “At a glance” paragraphs, or ordinary descriptions. Preserve its order of headings, images, copy, features and actions.
- Buttons use the global `.nectar-button.medium` component with `.accent-color` and `.extra-color-2`, or the existing plain Details/Demo links where that is the article's format. Use the established “More info / Download” and “Demo” labels.
- Keep presentation rules in the shared Colorlib child-theme stylesheet. `colorlib-article-components.css` records the deployed shared article rules; `colorlib-article-assets.php` versions that stylesheet by modification time. Do not embed copies or scoped overrides inside posts.
- Retain ownership disclosures, factual limitations, screenshots, destinations, numbering, contents links and schema during presentation changes. Keep customer notifications suppressed during editorial maintenance.
- Compare new and existing entries on the same public article at desktop and phone widths. Verify matching computed button styles and no section overflow. Back up post source, metadata and shared assets before publishing with source-hash guards.
