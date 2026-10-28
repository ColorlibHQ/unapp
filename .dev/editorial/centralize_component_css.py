#!/usr/bin/env python3
"""Prepare removal of remaining duplicate shared article component rules."""
import hashlib
import json
from pathlib import Path
import re
from article_components import component_css, remove_embedded_components

root = Path(__file__).resolve().parent
current = json.loads(Path('/tmp/colorlib-editorial-current.json').read_text())
corrected = {p['id'] for p in json.loads(Path('/tmp/colorlib-consistency-patches.json').read_text())}
extra_rule = '.cltoc .cltoc__more{margin:0;padding:0 22px 16px;font-size:14px;color:#475569;border-top:1px solid #f1f5f9;padding-top:12px}'
shared_path = root / 'colorlib-article-components.css'
shared = shared_path.read_text()
if extra_rule not in shared:
	shared += extra_rule + '\n'
	shared_path.write_text(shared)
patches = []
for post in current['articles']:
	id = int(post['ID'])
	if id in corrected: continue
	old = post['post_content']
	components = ''.join(component_css(s)[0] for s in re.findall(r'<style[^>]*>(.*?)</style>', old, re.S))
	if not components.strip(): continue
	# Every removed declaration must already have an identical shared declaration.
	for rule in re.finditer(r'([.#][^{}]+)\{([^{}]*)\}', components):
		assert re.sub(r'\s+', '', rule[0]) in re.sub(r'\s+', '', shared), (id, 'Rule missing from shared stylesheet', rule[0])
	new = remove_embedded_components(old)
	assert new != old
	patches.append({'id': id, 'url': post['url'], 'post_title': post['post_title'], 'before_sha256': hashlib.sha256(old.encode()).hexdigest(), 'post_content': new})
assert len(patches) == 23
Path('/tmp/colorlib-shared-css-patches.json').write_text(json.dumps(patches, ensure_ascii=False))
before = Path('/tmp/colorlib-v3ct-after.css').read_text()
Path('/tmp/colorlib-v3ct-centralized.css').write_text(before.rstrip() + '\n' + extra_rule + '\n')
print('Prepared', len(patches), 'CSS-only corrections; all removed rules have identical shared declarations.')
