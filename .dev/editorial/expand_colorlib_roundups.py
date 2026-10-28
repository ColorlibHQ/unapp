#!/usr/bin/env python3
"""Prepare reviewed WordPress article patches; publishing is a separate guarded step."""
import hashlib,html,json,re
from pathlib import Path
from article_components import render_picks, remove_embedded_components
ROOT=Path(__file__).resolve().parent
COPY=json.loads((ROOT/'colorlib-roundup-copy.json').read_text())
DATA=json.loads(Path('/tmp/colorlib-editorial-inventory.json').read_text())
META=json.loads(Path('/tmp/colorlib-editorial-meta.json').read_text())
MEDIA=json.loads(Path('/tmp/colorlib-editorial-media.json').read_text())
DEMOS={d['key']:d for d in json.loads(Path('/tmp/colorlib-editorial-demos.json').read_text())}
POSTS={int(p['ID']):p for p in DATA['articles']}
MAIN=['unapp','tyche','academia','pato','creative-agency','south','unioncorp','dreamrs','daren','philosophy','wemeet','docmed','nonprofit','sunshine','martine','montana','industry','lagoon','horseclub']
PLANS={
9446:MAIN,10966:['unapp-photographer'],116484:['nonprofit','unapp-nonprofit'],229751:['unapp-portfolio','daren','philosophy'],12945:['tyche','unapp-shop'],109209:['docmed','unapp-practice'],116669:['wemeet','unapp-events'],3158:['creative-agency','unapp-portfolio'],9352:['unapp-home-services','unioncorp'],116741:['unapp-photographer'],13365:['martine'],3639:['sunshine'],116713:['tyche','daren'],230474:['pato'],8891:['daren','philosophy','unapp-blog'],30174:['creative-agency','unapp-agency'],15476:['unioncorp','unapp-finance'],90803:['lagoon'],328812:['horseclub'],15402:['industry','unapp-home-services'],4198:['dreamrs'],176118:['unapp','tyche','academia','creative-agency','daren','philosophy'],2420:['south','dreamrs','unapp-realestate'],95536:['martine'],163549:['martine'],106592:['wemeet'],3321:['academia','unapp-education'],12439:['pato','unapp-restaurant'],15626:['unapp']}
VARIANTS={
'photographer':('Photographer starter','Photographers and image-led portfolios','Selected work, commissions and native photo editing','The free Photographer starter gives Unapp an image-led portfolio, selected work, an approach page and an enquiry path. Choose a one-page site or supporting pages, then replace the illustrative images through the guided content pass. Your own name, details and main action appear in a private preview before the starter is applied.','Client proofing, watermarks and paid print delivery need separate tools.'),
'nonprofit':('Nonprofit starter','Community groups and small nonprofits','Programmes, volunteering and ways to support','Unapp includes a free Nonprofit starter with programme, about, support and contact pages. It is a useful route for a community organisation that wants a native block site and clear ways for visitors to help. Setup previews your own details, and the guided pass helps replace sample copy and photographs.','Configure your actual giving destination; the starter does not process donations.'),
'portfolio':('Portfolio starter','Freelancers and independent professionals','Project pages, services and guided setup','Unapp offers a free Portfolio starter for independent creatives and a Coach & Consultant starter for service-led personal sites. Start with one page or supporting pages for work, approach and enquiries. Real service-card fields carry your offer into the page, and the setup preview shows your name, details and main action before building.','Advanced project filtering, client proofing and private client areas require separate tools.'),
'shop':('Shop starter','A small store with a matching brochure site','Free Shop starter and WooCommerce templates','Unapp has a free Shop starter with a storefront introduction, product sections and supporting information. Its WooCommerce templates cover the shop, product, cart and checkout, while native WordPress blocks keep the rest of the site editable without a page builder. The finishing workspace guides content review and image replacement.','WooCommerce must be active before applying Shop; configure payments, shipping and taxes in WooCommerce.'),
'practice':('Practice starter','A small clinic or dental practice','Treatments, team and a clear contact path','Unapp’s free Practice starter provides a home page, treatment information, team content and contact details. A one-page or full-site setup lets a small practice begin simply, and the guided pass helps replace demonstration names, photographs and claims. Everything remains native WordPress blocks.','Use an appropriate separate service for live appointment scheduling and patient records.'),
'events':('Conference starter','An individual conference or summit','Programme, speakers, ticket information and venue','Unapp’s free Conference starter gives an event a programme, speakers, ticket information and venue details. You can start with one scrolling page or create supporting pages, preview your details before applying, and change the main action to the event’s registration or ticket destination. No page builder is required.','The theme does not sell tickets or manage attendee registration itself.'),
'home-services':('Home Services starter','A one-page local-service website','Section navigation and supporting pages later','Unapp’s recommended one-page setup is a practical option for trades and local services. The free Home Services starter includes service cards, coverage information and a quote-request action. Replace the business details, choose optional sections and preview the result, then add supporting pages later without rebuilding the homepage.','The starter collects interest through contact links or an optional form plugin; quotes are not automatic.'),
'agency':('Agency starter','A small agency selling services','Work, capabilities and a main enquiry action','Unapp’s free Agency starter combines featured work, service capabilities and a clear enquiry path. Personalize the real service cards, choose one page or supporting pages and preview your business details before building. Header/footer presets and compatible section swaps provide more layout choices while the content stays in native blocks.','Replace sample projects, prices and testimonials before sharing the site.'),
'finance':('Finance & advisory starter','Consultants and independent advisers','Services, process, fees and enquiries','Unapp includes a free Finance & advisory starter with services, process and contact pages written for professional firms. Its service-card fields carry your actual offer into the layout, and the main action can point to a call, email or consultation destination. Review and edit every claim in WordPress before launch.','Scheduling, client portals and financial-data services are separate from the theme.'),
'education':('Courses starter','Tutors and small course providers','Course information and contact pages','Unapp’s free Courses starter presents course descriptions, tutors and contact information without a page builder. Start with a single page or supporting pages and use the personalized preview to check your details before applying. It suits a tutor or small provider that wants an editable course-information website.','It is not an LMS: lessons, learner accounts, enrolment and payments need separate tools.'),
'restaurant':('Restaurant starter','An independent restaurant or cafe','Editable menu, opening hours and booking enquiries','Unapp’s free Restaurant starter covers a priced menu, the kitchen, reviews, opening hours and a booking-request action. Its ordinary WordPress blocks make the food, photographs and wording straightforward to change, with a personalized preview and guided finishing workspace for the rest of the site.','Use email, phone or a form plugin for requests; live table availability and confirmed reservations are separate.'),
'realestate':('Property starter','A small estate agency with editable listings','Property information, published fees and valuation enquiries','Unapp’s free Property starter provides an agency website with listings, published fees and a valuation enquiry path. The listing sections are ordinary blocks, so it is a simple option for a small selection of properties you edit yourself. Native setup and guided content review help replace the sample information.','It does not include a structured listings database, map search or an IDX/MLS feed.'),
'blog':('Blog & magazine starter','An editorial blog with a broader site','Masthead, real posts and category sections','Unapp’s free Blog & magazine starter includes an editorial masthead, featured-post presentation and category sections that use your actual posts. Its one-page/full-site setup and native Styles controls suit a writer who also wants supporting information pages. The guided content pass helps finish the static sections while posts stay managed in WordPress.','A blog needs your own posts, categories and featured images; the starter’s sample wording is for review.')}


def plain(s):return html.unescape(re.sub('<[^>]+>','',s)).strip()
def hstart(c,pos):
 m=re.search(r'<!-- wp:heading\b[^>]*-->\s*$',c[:pos]);return m.start() if m else pos

def entries(c):
 headings=list(re.finditer(r'<h([23])\b[^>]*>(.*?)</h\1>',c,re.S));out=[]
 for i,h in enumerate(headings):
  if h[1]!='3':continue
  label=plain(h[2]);end=next((hstart(c,k.start()) for k in headings[i+1:] if int(k[1])<=3),len(c));body=c[h.end():end]
  # Numbered advice headings and FAQ questions do not identify theme entries.
  owned = bool(re.search(r'<article data-colorlib-product="[^"]+">\s*$', c[:h.start()]))
  if not (owned or re.match(r'^\d+\.\s',label) or label in ['Unapp','Academia','Pato','Philosophy']):continue
  if not ('[button ' in body or 'more info' in body.lower() or 'colorlib-editorial-actions' in body or re.search(r'>\s*(?:Demo|Details|Download)\s*</a>',body,re.I)):continue
  attrs=h[0].split('>',1)[0];aid=re.search(r'\bid="([^"]+)"',attrs)
  anchor=aid[1] if aid else 'theme-'+re.sub(r'[^a-z0-9]+','-',re.sub(r'^\d+\.\s*','',label).lower()).strip('-')
  out.append({'name':re.sub(r'^\d+\.\s*','',label),'label':label,'id':anchor,'start':hstart(c,h.start()),'head_start':h.start(),'head_end':h.end(),'end':end,'block':c[hstart(c,h.start()):end]})
 return out

def info(key):
 base='unapp' if key.startswith('unapp-') else key;d=dict(COPY[base]);d.update(DEMOS[key]);d['canonical']=base;d['name']=DEMOS[base]['name'];d['subtitle']='Block edition'
 if key.startswith('unapp-'):
  v=VARIANTS[key[6:]];d.update(subtitle=v[0],best=v[1],standout=v[2],copy=v[3],limit=v[4])
 if key=='philosophy':d['subtitle']='Block and classic editions'
 if key=='tyche':d['subtitle']='Current block edition'
 return d

def renumber(c):
 es=entries(c)
 for number,e in reversed(list(enumerate(es,1))):
  old=c[e['head_start']:e['head_end']];
  if not re.search(r'\bid=',old.split('>',1)[0]):old=old.replace('<h3','<h3 id="'+e['id']+'"',1)
  old=re.sub(r'(<h3\b[^>]*>)(.*?)(</h3>)',lambda m:m[1]+str(number)+'. '+re.sub(r'^\d+\.\s*','',m[2])+m[3],old,flags=re.S);c=c[:e['head_start']]+old+c[e['head_end']:]
 return c

def update_count(text,old,new):
 return re.sub(r'\b'+str(old)+r'(?=\s+(?:(?:best|free|paid|premium|WordPress|one.page|photography|charity|medical|eCommerce|personal|gallery|travel|wedding|event|fashion|bakery)\s+){0,5}(?:themes|options|picks)\b)',str(new),text,flags=re.I)

def wpbalance(c):
 stack=[]
 for m in re.finditer(r'<!--\s*(/?)wp:([^\s]+?)(?:\s+.*?)?\s*(/?)-->',c,re.S):
  close,name,selfclose=m.groups()
  if selfclose:continue
  if close:
   if not stack or stack[-1]!=name:return False
   stack.pop()
  else:stack.append(name)
 return not stack

def prepare(post_id,keys):
 post=POSTS[post_id];old=post['post_content'];c=old;
 if post_id==9352:c=c.replace('<!-- /wp:paragrap\n', '<!-- /wp:paragraph -->\n')
 old_es=entries(c);original_count=len(old_es);front_infos=[]
 for key in keys:
  d=info(key);existing=next((e for e in old_es if e['name']==d['name'] or e['name'].startswith(d['name']+' ')),None);d['anchor']=existing['id'] if existing else 'colorlib-'+d['canonical'];front_infos.append(d)
 # Move existing selected products rather than duplicating them.
 selected={d['name'] for d in front_infos}
 if post_id==9446:
  m=re.search(r'<!-- wp:heading[^>]*-->\s*<h2\b[^>]*>New from Colorlib:.*?(?=<!-- wp:heading[^>]*-->\s*<h2)',c,re.S)
  assert m,'Missing old Colorlib group';c=c[:m.start()]+c[m.end():]
 for e in reversed(entries(c)):
  if e['name'] in selected:c=c[:e['start']]+c[e['end']:]
 remaining=entries(c);assert remaining,post_id
 # Insert our free group immediately before the first full theme-list section.
 first=remaining[0]['start'];h2=list(re.finditer(r'<h2\b[^>]*>.*?</h2>',c[:first],re.S));assert h2,post_id
 insertion=hstart(c,h2[-1].start())
 new_section=render_picks(front_infos, MEDIA, c);c=c[:insertion]+new_section+c[insertion:];c=remove_embedded_components(c);c=renumber(c);all_es=entries(c);total=len(all_es)
 # All original external entries remain, in their original relative order.
 old_external=[e['name'] for e in old_es if e['name'].split(' — ')[0] not in selected];new_external=[e['name'] for e in all_es if e['name'].split(' — ')[0] not in selected];assert old_external==new_external,(post_id,'External entries changed')
 toc='<!-- wp:html -->\n<details class="cltoc"><summary>Table of contents — all '+str(total)+' themes</summary><ol>'+''.join('<li><a href="#'+e['id']+'">'+html.escape(e['name'])+'</a></li>' for e in all_es)+'</ol></details>\n<!-- /wp:html -->'
 cltoc=re.search(r'<!-- wp:html -->\s*<details class="cltoc">.*?</details>\s*<!-- /wp:html -->',c,re.S)
 if cltoc:c=c[:cltoc.start()]+toc+c[cltoc.end():]
 else:
  manual=re.search(r'<h3\b[^>]*>Table\s+Of\s+Contents:?</h3>',c,re.I)
  if manual:
   start=hstart(c,manual.start());lst=re.search(r'<(ul|ol)\b[^>]*>.*?</\1>',c[manual.end():],re.S);assert lst
   end=manual.end()+lst.end();closing=re.match(r'\s*<!-- /wp:list -->',c[end:]);end+=closing.end() if closing else 0;c=c[:start]+toc+c[end:]
  else:
   ins=c.index('<!-- wp:group {"className":"colorlib-editorial-picks"} -->');c=c[:ins]+toc+'\n\n'+c[ins:]
 # Add our leading picks to summary UI and feature comparison; benchmark rows stay untouched.
 picked=front_infos[:3];quick=re.search(r'(<ul class="quick-picks__list">)',c)
 if quick:
  bullet=''.join(f'<li class="quick-picks__item"><strong>Colorlib free pick:</strong> <a href="#{d["anchor"]}">{html.escape(d["name"])}</a> — {html.escape(d["standout"])}</li>' for d in picked);c=c[:quick.end()]+bullet+c[quick.end():]
 cards=re.search(r'(<div class="clqp__grid">)',c)
 if cards:
  card=''.join(f'<a class="clqp__card" href="#{d["anchor"]}"><img src="{MEDIA[d["key"]]["url"]}" alt="{html.escape(MEDIA[d["key"]]["alt"],quote=True)}" loading="lazy"><span class="clqp__name">{html.escape(d["name"])}</span><span class="clqp__why">Free from Colorlib · {html.escape(d["standout"])}</span></a>' for d in picked);c=c[:cards.end()]+card+c[cards.end():]
 comp=re.search(r'<h2\b[^>]*id="quick-comparison"[^>]*>.*?</h2>(.*?)(?=<!-- wp:heading|<h2)',c,re.S)
 if comp:
  region=comp[1];cols=len(re.findall('<th[ >]',region));assert cols in (4,5)
  def keep_row(m):return '' if any(re.search(r'>'+re.escape(d['name'])+r'</a>',m[0]) for d in front_infos) else m[0]
  region=re.sub(r'<tr>.*?</tr>',keep_row,region,flags=re.S)
  rows=''.join('<tr><td><strong><a href="#'+d['anchor']+'">'+html.escape(d['name'])+'</a></strong></td><td>Free</td><td>'+html.escape(d['best'])+'</td><td>'+html.escape(d['standout'])+'</td>'+('<td>—</td>' if cols==5 else '')+'</tr>' for d in picked)
  region=region.replace('<tbody>','<tbody>'+rows,1);c=c[:comp.start(1)]+region+c[comp.end(1):]
  c=c.replace('Quick comparison: the six we recommend most','Quick comparison: our current picks')
 old_title=html.unescape(post['post_title']);old_display=int(re.match(r'^(\d+)',old_title)[1]);is_free=bool(re.match(r'^\d+ Best Free\b',old_title));added=total-original_count
 # General roundup previously excluded its three unranked Colorlib entries from the free total.
 display=47 if post_id==9446 else old_display+added if is_free else total
 title=re.sub(r'^\d+',str(display),old_title)
 if post_id==9446:title=f'{display} Best Free WordPress Themes 2026 (With Live Demos)'
 c=update_count(c,old_display,display)
 c=update_count(c,original_count,total)
 topics={9446:'a starter-based website',10966:'a photography website',116484:'a charity or community organisation',229751:'a personal website or independent service business',12945:'a WooCommerce store',109209:'a clinic or small practice',116669:'a conference or meetup',116741:'an image-led gallery',13365:'a travel agency or tour operator',3639:"a couple's wedding website",116713:'a clothing store or fashion blog',230474:'a bakery or cafe'}
 if post_id in topics:
  first_pick=front_infos[0];link='<a href="#'+first_pick['anchor']+'">'+html.escape(first_pick['name'])+'</a>'
  qualifier=(' — '+html.escape(first_pick['subtitle'])) if first_pick['canonical']=='unapp' and first_pick['subtitle']!='Block edition' else ''
  paid=total-display
  lead=f'<p>For {html.escape(topics[post_id])}, our first free Colorlib pick is {link}{qualifier}: {html.escape(first_pick["standout"].lower())}. This guide covers {display} free themes'+(f' and {paid} paid alternatives' if paid else '')+', with live demos and clear notes about what each option provides.</p>'
  intro=re.search(r'(<!-- wp:paragraph(?:\s+\{[^>]*\})? -->\s*)<p(?:\s[^>]*)?>.*?</p>(\s*<!-- /wp:paragraph -->)',c,re.S)
  assert intro,(post_id,'Missing intro paragraph')
  c=c[:intro.start()]+intro[1]+lead+intro[2]+c[intro.end():]
 # Scope test-method claims to the actual, unchanged benchmark tables.
 c=c.replace('Every theme here cleared the same three checks:', 'The WordPress.org selections were checked against three criteria:')
 c=c.replace('We also installed each one on an identical test site and measured what it weighs,', 'The themes in the speed table were also installed on an identical test site to measure their weight,')
 c=c.replace('we installed every theme on the same clean WordPress site', 'we installed the themes in the speed table on the same clean WordPress site')
 c=c.replace('all measured for speed on identical content', 'with a comparison of the previously speed-tested themes on identical content')
 c=c.replace('all shown running a real', 'shown running a real')
 c=c.replace('We tested it: what each free theme actually weighs','Speed comparison: previously tested themes')
 if post_id==328812:c=c.replace('collection of&nbsp;premium WordPress themes','collection of free and premium WordPress themes').replace('collection of premium WordPress themes','collection of free and premium WordPress themes')
 # Synchronize existing ItemList schema; FAQ answers retain their substantive content.
 def schema_update(m):
  try:obj=json.loads(m[2])
  except json.JSONDecodeError:return m[0]
  if obj.get('@type')=='ItemList':obj['name']=title;obj['numberOfItems']=total;obj['itemListElement']=[{'@type':'ListItem','position':i,'name':e['name'],'url':post['url']+'#'+e['id']} for i,e in enumerate(all_es,1)]
  else:
   def walk(o):
    if isinstance(o,str):return update_count(o,old_display,display)
    if isinstance(o,list):return [walk(v) for v in o]
    if isinstance(o,dict):return {k:walk(v) for k,v in o.items()}
    return o
   obj=walk(obj)
  return m[1]+json.dumps(obj,ensure_ascii=False,separators=(',',':'))+m[3]
 c=re.sub(r'(<script[^>]*type="application/ld\+json"[^>]*>)(.*?)(</script>)',schema_update,c,flags=re.S)
 seo=dict(META[str(post_id)])
 for key,value in seo.items():
  if value:seo[key]=re.sub(r'^'+str(old_display)+r'\b',str(display),update_count(value,old_display,display))
 seo['_yoast_wpseo_title']=f'{display} Best Free WordPress Themes 2026 - Colorlib' if post_id==9446 else seo['_yoast_wpseo_title'] or title+' - Colorlib'
 topic=re.sub(r'^\d+ Best (?:Free )?','',title)
 topic=re.sub(r'\s*\([^)]*\)| 2026','',topic).strip().lower().replace('wordpress','WordPress').replace('ecommerce','eCommerce')
 if is_free:topic='free '+topic
 seo['_yoast_wpseo_metadesc']=f'Compare {display} {topic} with live demos and free Colorlib picks. See key features, requirements and the best fit for your website.'
 if len(seo['_yoast_wpseo_metadesc'])>160:seo['_yoast_wpseo_metadesc']=f'Explore {display} {topic}. Compare live demos, free Colorlib options and features for your website.'
 assert wpbalance(c),(post_id,'Unbalanced native blocks')
 assert len({e['id'] for e in all_es})==total,(post_id,'Duplicate entry anchors')
 assert all_es[0]['id']==front_infos[0]['anchor'],(post_id,[e['name'] for e in all_es[:3]])
 assert len(re.findall('class="wp-block-group colorlib-theme-entry ',c))==len(keys)
 return {'id':post_id,'url':post['url'],'old_title':post['post_title'],'post_title':title,'before_sha256':hashlib.sha256(old.encode()).hexdigest(),'post_content':c,'seo':seo,'original_entries':original_count,'total_entries':total,'display_count':display,'added_entries':added,'products':[{'key':d['key'],'canonical':d['canonical'],'anchor':d['anchor'],'demo':d['demo'],'page':d['page']} for d in front_infos],'preview_section':new_section}

if __name__=='__main__':
 patches=[prepare(i,ks) for i,ks in PLANS.items()]
 Path('/tmp/colorlib-editorial-patches.json').write_text(json.dumps(patches,ensure_ascii=False))
 summary=[{k:p[k] for k in ['id','url','old_title','post_title','original_entries','total_entries','display_count','added_entries','products']} for p in patches]
 Path('/tmp/colorlib-editorial-plan.json').write_text(json.dumps(summary,ensure_ascii=False,indent=2)+'\n')
 print('Prepared',len(patches),'articles;',sum(p['added_entries'] for p in patches),'new unique entries;',sum(len(p['products']) for p in patches),'leading Colorlib placements.')
 for p in patches:print(p['id'],str(p['original_entries'])+'->'+str(p['total_entries']),p['post_title'])
