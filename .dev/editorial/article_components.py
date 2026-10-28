"""Render additions with the article's established components, without embedded CSS."""
import html
import json
import re

COMPONENTS = ('.template-card', '.quick-picks', '.editors-pick', '.clqp', '.cltoc', '.clstack')


def component_css(css):
	"""Split shared article rules from unrelated CSS, including nested media rules."""
	shared, other = [], []
	pos = 0
	while pos < len(css):
		brace = css.find('{', pos)
		if brace < 0:
			other.append(css[pos:])
			break
		depth, end = 1, brace + 1
		while end < len(css) and depth:
			depth += (css[end] == '{') - (css[end] == '}')
			end += 1
		header, body = css[pos:brace], css[brace + 1:end - 1]
		if header.strip().startswith('@media'):
			a, b = component_css(body)
			if a.strip(): shared.append(header + '{' + a + '}')
			if b.strip(): other.append(header + '{' + b + '}')
		elif any(prefix in header for prefix in COMPONENTS):
			shared.append(css[pos:end])
		else:
			other.append(css[pos:end])
		pos = end
	return ''.join(shared), ''.join(other)


def remove_embedded_components(content):
	def clean(match):
		_, remaining = component_css(match[1])
		return '<style>' + remaining + '</style>' if remaining.strip() else ''
	content = re.sub(r'<style\b[^>]*>(.*?)</style>', clean, content, flags=re.S)
	return re.sub(r'<!-- wp:html -->\s*<!-- /wp:html -->\s*', '', content)


def article_format(content):
	if 'class="template-card"' in content: return 'cards'
	if '<strong>At a glance:' in content: return 'glance'
	if 'Details</a></strong> |' in content: return 'details'
	return 'plain'


def paragraph(text):
	return '<!-- wp:paragraph -->\n<p>' + text + '</p>\n<!-- /wp:paragraph -->'


def render_picks(items, media, reference):
	"""Match the surrounding post's card, at-a-glance or plain editorial format."""
	fmt = article_format(reference)
	escape = html.escape
	out = ['<!-- wp:group {"className":"colorlib-editorial-picks"} -->', '<div class="wp-block-group colorlib-editorial-picks">', '<!-- wp:heading {"anchor":"colorlib-free-picks"} -->', '<h2 class="wp-block-heading" id="colorlib-free-picks">Our free Colorlib picks</h2>', '<!-- /wp:heading -->', paragraph('We develop and maintain these free themes at Colorlib. Download them directly from us and explore their live demos below. These editions use Colorlib updates; WordPress.org install counts and ratings do not apply. Any speed table on this page covers the themes already measured in that test.')]
	for number, item in enumerate(items, 1):
		im = media[item['key']]
		name = escape(item['name'])
		title = name + ' — ' + escape(item['subtitle'])
		classes = 'colorlib-theme-entry colorlib-product-' + item['canonical']
		out += ['<!-- wp:group ' + json.dumps({'className': classes}, separators=(',', ':')) + ' -->', '<div class="wp-block-group ' + classes + '">', '<!-- wp:heading ' + json.dumps({'level': 3, 'anchor': item['anchor']}, separators=(',', ':')) + ' -->', f'<h3 class="wp-block-heading" id="{item["anchor"]}">{number}. {title}</h3>', '<!-- /wp:heading -->', '<!-- wp:image ' + json.dumps({'id': im['id'], 'sizeSlug': 'large', 'linkDestination': 'none'}, separators=(',', ':')) + ' -->', f'<figure class="wp-block-image size-large"><img src="{im["url"]}" alt="{escape(im["alt"], quote=True)}" class="wp-image-{im["id"]}"/></figure>', '<!-- /wp:image -->']
		buttons = paragraph(f'<a class="nectar-button medium accent-color" target="_blank" rel="noopener" href="{item["page"]}">More info / Download</a> <a class="nectar-button medium extra-color-2" target="_blank" rel="noopener" href="{item["demo"]}">Demo</a>')
		if fmt == 'details':
			out.append(paragraph(f'<strong><a href="{item["page"]}" target="_blank" rel="noopener">Details</a></strong> | <strong><a href="{item["demo"]}" target="_blank" rel="noopener">Demo</a></strong>'))
		if fmt == 'cards':
			out += ['<!-- wp:html -->', '<div class="template-card"><div class="template-card__meta">', '<span class="template-card__badge template-card__badge--price-free">Free from Colorlib</span>', '<span class="template-card__badge template-card__badge--stack">Native WordPress editing</span>', f'<span class="template-card__badge template-card__badge--best-for">Best for: {escape(item["best"])}</span></div>', f'<p class="template-card__highlight"><strong>Why we like it:</strong> {escape(item["standout"])}.</p></div>', '<!-- /wp:html -->']
		out.append(paragraph(escape(item['copy'])))
		if fmt == 'glance':
			out.append(paragraph('<strong>At a glance:</strong> Free from Colorlib · Native WordPress editing · ' + escape(item['standout'])))
		if fmt in ('cards', 'glance'):
			out.append(paragraph('✅ <strong>Pros:</strong> ' + escape(item['standout']) + '. &nbsp; ⚠️ <strong>Cons:</strong> ' + escape(item['limit'])))
			if fmt == 'glance': out.append(paragraph('<strong>Best for:</strong> ' + escape(item['best']) + '.'))
		else:
			out.append(paragraph(escape(item['limit'])))
		if fmt != 'details': out.append(buttons)
		out += ['</div>', '<!-- /wp:group -->']
	out += ['</div>', '<!-- /wp:group -->']
	return '\n\n'.join(out) + '\n\n'
