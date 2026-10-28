/** Validate the published editorial batch against its prepared manifest. */
const fs = require('node:fs');
const assert = require('node:assert/strict');
const consistency = process.env.EDITORIAL_CONSISTENCY === '1';
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
(async () => {
 const patches = JSON.parse(fs.readFileSync(process.env.EDITORIAL_PATCHES || (consistency ? '/tmp/colorlib-consistency-patches.json' : '/tmp/colorlib-editorial-patches.json')));
 const browser = await chromium.launch({ headless: true, ...(process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {}) });
 const context = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
 const results = [];
 let cursor = 0;
 await Promise.all([0, 1].map(async () => {
  const page = await context.newPage();
  while (cursor < patches.length) {
   const patch = patches[cursor++];
   try {
    const response = await page.goto(patch.url, { waitUntil: 'networkidle', timeout: 60000 });
    assert.equal(response.status(), 200);
    const reject = page.getByRole('button', { name: 'Reject All', exact: true });
    if (await reject.count() && await reject.isVisible()) await reject.click();
    const content = page.locator('.single-blog-content');
    const picks = content.locator('.colorlib-editorial-picks');
    assert.equal(await picks.locator('.colorlib-theme-entry, article[data-colorlib-product]').count(), patch.products.length);
    if (consistency) {
     assert.equal(await picks.locator('style, .colorlib-editorial-actions, .colorlib-editorial-note').count(), 0);
     const first = picks.locator('.colorlib-theme-entry').first();
     if (patch.format === 'cards') {
      assert.equal(await first.locator('.template-card').count(), 1);
      assert.equal(await first.locator('.template-card__badge--best-for').count(), 1);
     } else assert.equal(await first.locator('.template-card').count(), 0);
     if (patch.format !== 'details') {
      assert.equal(await first.locator('.nectar-button.accent-color, .nectar-button.extra-color-2').count(), 2);
      const newButton = await first.locator('.nectar-button').first().evaluate(el => {
       const s = getComputedStyle(el); return [s.backgroundColor, s.borderRadius, s.fontSize, s.padding, s.textTransform];
      });
      const oldButton = await content.locator('.nectar-button').evaluateAll(els => {
       const el = els.find(e => !e.closest('.colorlib-editorial-picks') && e.classList.contains('accent-color'));
       if (!el) return null; const s = getComputedStyle(el); return [s.backgroundColor, s.borderRadius, s.fontSize, s.padding, s.textTransform];
      });
      if (oldButton) assert.deepEqual(newButton, oldButton, 'Existing and new button styles match');
     }
     assert.ok(await page.locator('link#wpex-style-css').getAttribute('href').then(h => /ver=\d{10}/.test(h)), 'Shared stylesheet uses file version');
    }
    const headings = await picks.locator('h3').allTextContents();
    assert.ok(headings[0].startsWith('1. '));
    assert.ok(headings[0].includes(patch.products[0].canonical === 'creative-agency' ? 'Creative Agency' : patch.products[0].canonical.charAt(0).toUpperCase() + patch.products[0].canonical.slice(1)));
    assert.ok((await page.title()).startsWith(String(patch.display_count)), 'SEO title count');
    assert.ok((await page.locator('h1').first().innerText()).startsWith(String(patch.display_count)), 'Visible title count');
    const toc = content.locator('.cltoc');
    assert.equal(await toc.locator('li').count(), patch.total_entries);
    const hashes = await toc.locator('a').evaluateAll(links => links.map(a => a.hash));
    for (const hash of hashes) assert.equal(await content.locator('[id="' + hash.slice(1) + '"]').count(), 1, 'TOC destination ' + hash);
    const schemas = await content.locator('script[type="application/ld+json"]').allTextContents();
    for (const text of schemas) {
     const data = JSON.parse(text);
     if (data['@type'] === 'ItemList') {
      assert.equal(data.numberOfItems, patch.total_entries);
      assert.equal(data.itemListElement.length, patch.total_entries);
     }
    }
    await picks.locator('img').evaluateAll(async images => {
     await Promise.all(images.map(async image => { image.loading = 'eager'; await image.decode(); if (!image.naturalWidth || !image.alt) throw new Error('Screenshot missing'); }));
    });
    for (const width of [1440, 390, 320]) {
     await page.setViewportSize({ width, height: 1000 });
     assert.equal(await picks.evaluate(el => el.scrollWidth > el.clientWidth + 1), false, 'Pick section overflow at ' + width);
     const buttons = picks.locator('.colorlib-theme-entry, .colorlib-editorial-actions').first();
     await buttons.scrollIntoViewIfNeeded();
     assert.ok(await buttons.locator('a').first().isVisible());
     if ([9446, 10966, 116484, 12945, 109209, 328812, 90803].includes(patch.id) && [1440, 390].includes(width)) {
      await picks.locator('.colorlib-theme-entry, article[data-colorlib-product]').first().scrollIntoViewIfNeeded();
      await page.screenshot({ path: (consistency ? '/tmp/colorlib-consistent-' : '/tmp/colorlib-published-') + patch.id + '-' + width + '.png' });
     }
    }
    results.push({ id: patch.id, url: patch.url, ok: true, entries: patch.total_entries, leading: patch.products.map(p => p.canonical) });
    console.log('Verified', patch.id, patch.total_entries, 'entries, SEO, TOC, images, 3 widths');
   } catch (error) {
    results.push({ id: patch.id, url: patch.url, ok: false, error: error.message });
    console.error('FAILED', patch.id, error.message.slice(0, 180));
   }
  }
  await page.close();
 }));
 fs.writeFileSync(consistency ? '/tmp/colorlib-consistency-live-checks.json' : '/tmp/colorlib-editorial-live-checks.json', JSON.stringify(results, null, 2));
 await browser.close();
 assert.equal(results.filter(r => !r.ok).length, 0, 'Published checks failed');
})();
