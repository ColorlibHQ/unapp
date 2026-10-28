# Visual getting-started assets

Captured with Playwright from Unapp 2.6.0 running on WordPress 7.1.3 in an isolated local installation. Oak & Stone and Jamie are illustrative tutorial details. No customer site or public demo was changed to capture this guide.

Screenshots use a 1100 x 920 viewport; the native upload screen is cropped to 1100 x 430. Lossless WebP keeps interface labels readable. The native editor screenshot selects the Image block so its Replace toolbar and setup return shortcuts are visible.

The public documentation project (`colorlib-docs`) serves the same images under `/unapp/img/getting-started/`, together with a 20-second H.264 walkthrough, English WebVTT action captions and the visual guide's scoped CSS/JavaScript. The clip records starter search, business details and device preview without applying another starter. It has controls, a text alternative, no autoplay and `preload="none"`.

When refreshing: use a disposable WordPress installation, capture the actual interface, update screenshot dimensions if needed, verify captions against the recording, and run the documentation project's `tools/check-unapp-guide.cjs`. Check 1440, 390 and 320 px widths, keyboard enlargement/closing, actual-size viewing, playback, and the no-JavaScript reference links. Do not commit login storage state or credentials.
