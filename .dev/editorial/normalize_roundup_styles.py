#!/usr/bin/env python3
"""Prepare a source-hash-guarded correction of the published article batch."""
import hashlib
import json
import re
from pathlib import Path
from article_components import article_format, component_css, remove_embedded_components, render_picks
from expand_colorlib_roundups import info, wpbalance

current = json.loads(Path('/tmp/colorlib-editorial-current.json').read_text())
posts = {int(p['ID']): p for p in current['articles']}
plan = json.loads(Path('/tmp/colorlib-editorial-plan.json').read_text())
media = json.loads(Path('/tmp/colorlib-editorial-media.json').read_text())
patches = []
for record in plan:
	post = posts[record['id']]
	old = post['post_content']
	pattern = r'<!-- wp:html -->\s*<style>\.colorlib-editorial-picks.*?</section>\s*<!-- /wp:html -->'
	matches = list(re.finditer(pattern, old, re.S))
	assert len(matches) == 1, (record['id'], 'Expected exactly one original expansion section')
	match = matches[0]
	reference = old[:match.start()] + old[match.end():]
	items = []
	for product in record['products']:
		item = info(product['key'])
		item['anchor'] = product['anchor']
		items.append(item)
	section = render_picks(items, media, reference)
	new = remove_embedded_components(old[:match.start()] + section + old[match.end():])
	assert wpbalance(new), (record['id'], 'Block comments must balance')
	assert 'colorlib-editorial-actions' not in new and 'colorlib-editorial-note' not in new
	assert 'data-colorlib-product=' not in new
	assert new.count('class="wp-block-group colorlib-theme-entry ') == len(items)
	assert len(re.findall(r'<h3\b', old)) == len(re.findall(r'<h3\b', new))
	# Outside the new entries, only the shared component CSS is removed.
	outside = remove_embedded_components(reference)
	new_outside = new.replace(remove_embedded_components(section), '', 1)
	assert outside == new_outside, (record['id'], 'Unrelated content changed')
	patches.append({'id': record['id'], 'url': record['url'], 'before_sha256': hashlib.sha256(old.encode()).hexdigest(), 'post_content': new, 'post_title': post['post_title'], 'format': article_format(reference), 'total_entries': record['total_entries'], 'display_count': record['display_count'], 'products': record['products']})
Path('/tmp/colorlib-consistency-patches.json').write_text(json.dumps(patches, ensure_ascii=False))
# Promote the existing article components to their one shared source.
original = json.loads(Path('/tmp/colorlib-editorial-inventory.json').read_text())
main = next(p for p in original['articles'] if int(p['ID']) == 9446)
styles = re.findall(r'<style>(.*?)</style>', main['post_content'], re.S)
shared = ''.join(component_css(style)[0] for style in styles).strip()
assert '.template-card__highlight' in shared and '.cltoc' in shared
Path(__file__).with_name('colorlib-article-components.css').write_text('/* Shared Colorlib article components. Keep styling out of article bodies. */\n' + shared + '\n')
before = Path('/tmp/colorlib-v3ct-before.css').read_text()
_, remaining = component_css(before)
Path('/tmp/colorlib-v3ct-after.css').write_text(remaining.rstrip() + '\n\n' + Path(__file__).with_name('colorlib-article-components.css').read_text())
assert '.nectar-button{border-radius:5px' in remaining
print('Prepared', len(patches), 'article corrections; existing global button design retained.')
for patch in patches: print(patch['id'], patch['format'], len(patch['products']), 'entries')
