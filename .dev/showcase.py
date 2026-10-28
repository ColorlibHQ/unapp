"""Original, self-contained starter artwork. Run before regenerating patterns."""
from pathlib import Path
from html import escape

OUT = Path(__file__).resolve().parent.parent / 'assets/images/showcase'
OUT.mkdir(parents=True, exist_ok=True)

def text(x, y, value, size=24, color='#173e38', weight=400, family='Arial, sans-serif', **attrs):
    extra = ' '.join(f'{k.replace("_", "-")}="{v}"' for k, v in attrs.items())
    return f'<text x="{x}" y="{y}" font-family="{family}" font-size="{size}" font-weight="{weight}" fill="{color}" {extra}>{escape(value)}</text>'

def rect(x, y, w, h, color, r=0, extra=''):
    return f'<rect x="{x}" y="{y}" width="{w}" height="{h}" rx="{r}" fill="{color}" {extra}/>'

def save(name, title, body, bg='#edece6'):
    defs = '<defs><filter id="shadow" x="-30%" y="-30%" width="160%" height="180%"><feDropShadow dx="0" dy="18" stdDeviation="20" flood-color="#122f2c" flood-opacity=".13"/></filter></defs>'
    (OUT / f'{name}.svg').write_text(f'<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="900" viewBox="0 0 1200 900"><title>{escape(title)}</title>{defs}{rect(0,0,1200,900,bg)}{body}</svg>\n')

# A consistent fictional product, rather than unrelated analytics screenshots.
for mode in ('board', 'report', 'timeline'):
    body = rect(40,55,1120,790,'#fff',20,'filter="url(#shadow)"')
    body += rect(40,55,220,790,'#f5f6fb',20) + rect(240,55,20,790,'#f5f6fb')
    body += text(72,112,'◈ unapp',32,'#36449b',700)
    for i, label in enumerate(('Workspace', 'Overview', 'Projects', 'Reports', 'Team')):
        y = 195+i*64
        if label == ('Reports' if mode=='report' else 'Projects'):
            body += rect(60,y-32,178,48,'#e4e8ff',8)
        body += text(79,y,label,21,'#45506b',600 if i==2 else 400)
    body += text(76,762,'PRODUCT TEAM',14,'#66718a',700,letter_spacing='2')
    for i, color in enumerate(('#dce6d9','#ffdcc8','#d9dcf8')):
        body += f'<circle cx="{88+i*40}" cy="801" r="18" fill="{color}"/>'
        body += text(80+i*40,807,('AL','MK','JT')[i],12,'#344253',700)
    body += text(298,112,'Workspace / Projects',17,'#66718a')
    body += rect(978,82,145,43,'#e9f4ec',22) + text(1000,110,'On track',17,'#286643',600)
    body += text(298,191,'Website launch',40,'#182641',700)
    body += text(300,229,'One team. A clear path to launch.',20,'#66718a')
    for i, tab in enumerate(('Board','Timeline','Report')):
        x=300+i*140
        body += text(x,290,tab,21,'#4156bf' if tab.lower()==mode else '#66718a',600)
        if tab.lower()==mode: body += rect(x,307,90,4,'#5468d8',2)
    if mode == 'board':
        cards = [('To do', '#e8eaf1', [('Write the launch story','Content','#f9e7d9'),('Review mobile layouts','Design','#e5e1fa')]),
                 ('In progress','#dfe5ff',[('Build the new homepage','Website','#dfeee5'),('Connect the contact form','Website','#dfeee5')]),
                 ('Ready','#dfeee5',[('Agree the project scope','Planning','#e9e8f4'),('Create the brand toolkit','Design','#e5e1fa')])]
        for i,(title,color,items) in enumerate(cards):
            x=293+i*278
            body += rect(x,346,258,434,'#f6f7fa',12)+text(x+18,383,title,20,'#36415a',600)
            body += f'<circle cx="{x+222}" cy="376" r="14" fill="{color}"/>'+text(x+217,382,'2',14,'#45506b',700)
            for j,(label,tag,tagcolor) in enumerate(items):
                y=408+j*172
                body += rect(x+12,y,234,151,'#fff',10)+rect(x+28,y+20,91,27,tagcolor,6)
                body += text(x+38,y+39,tag,13,'#45506b',600)
                words=label.split(); line=''; lines=[]
                for word in words:
                    if len(line+' '+word)>21: lines.append(line);line=word
                    else: line=(line+' '+word).strip()
                lines.append(line)
                for k,line in enumerate(lines): body += text(x+28,y+77+k*23,line,18,'#24314c',600)
                body += text(x+28,y+132,'✓ 3 / 5',14,'#66718a')+f'<circle cx="{x+211}" cy="{y+125}" r="12" fill="{color}"/>'
    elif mode == 'report':
        for i,(value,label) in enumerate((('24 / 32','Tasks complete'),('8 days','Until launch'),('92%','Team capacity'))):
            x=300+i*278
            body += rect(x,348,252,130,'#f5f6fb',12)+text(x+22,401,value,35,'#24314c',700)+text(x+22,447,label,18,'#66718a')
        body += text(300,533,'Progress this month',24,'#24314c',600)
        for i,h in enumerate((70,100,86,140,174,202,220)):
            x=334+i*108
            body += rect(x,786-h,55,h,'#cbd2f8' if i<6 else '#5468d8',7)+text(x+10,814,str(5+i*3),14,'#66718a')
        body += '<path d="M330 680 L438 647 L546 655 L654 599 L762 569 L870 543 L978 520" fill="none" stroke="#2f7754" stroke-width="5" stroke-linecap="round"/>'
    else:
        body += text(304,361,'JUNE',16,'#66718a',600)
        for i in range(5):
            x=480+i*134
            body += text(x,361,f'{9+i*3} Jun',17,'#66718a')+rect(x,390,1,390,'#e6e9f1')
        for i,(label,x,w,col) in enumerate((('Discovery',482,210,'#dce6d9'),('Brand toolkit',554,280,'#dddff8'),('Website build',650,340,'#dce6d9'),('Content review',842,220,'#f4dfcf'),('Launch',1030,70,'#5468d8'))):
            y=420+i*70
            body += text(300,y+27,label,18,'#45506b')+rect(x,y,w,40,col,8)
    save(f'workspace-{mode}',f'Unapp website launch: {mode} view',body,'#edf0fa')

# Brand and packaging studies: deliberately distinct colour, type and materials.
body = '<g transform="rotate(-9 400 440)">'+rect(185,153,390,590,'#164c3d',10,'filter="url(#shadow)"')
body += rect(185,153,390,38,'#113e31')+text(220,269,'NORDWELL',42,'#f4eacb',700,letter_spacing='2')
body += text(220,312,'COFFEE ROASTERS',18,'#f4eacb',400,letter_spacing='3')
body += '<path d="M225 545 Q300 400 370 545 T535 545 M225 580 Q300 435 370 580 T535 580" fill="none" stroke="#d9bd72" stroke-width="8"/>'
body += text(220,665,'EVERYDAY / 01',24,'#f4eacb',600)+text(220,704,'Whole bean · 250g',18,'#f4eacb')+'</g>'
body += '<g transform="rotate(12 830 490)">'+rect(625,235,350,530,'#e8ad85',10,'filter="url(#shadow)"')+rect(625,235,350,32,'#d59c78')
body += text(655,343,'NORDWELL',35,'#263d31',700)+text(655,378,'COFFEE ROASTERS',16,'#263d31',400,letter_spacing='2')
body += '<circle cx="800" cy="527" r="90" fill="#f3e5c5"/><path d="M710 527H890M800 437V617" stroke="#263d31" stroke-width="3"/>'
body += text(655,689,'SINGLE ORIGIN / 02',20,'#263d31',600)+text(655,724,'Roasted with purpose.',18,'#263d31')+'</g>'
save('nordwell-coffee','Nordwell Coffee: forest-green and apricot coffee packaging',body,'#e8e1d4')

body=rect(90,80,1020,740,'#f8f6ef',10,'filter="url(#shadow)"')+text(130,155,'FOLD',57,'#313c2c',700)+text(795,146,'Spaces   Objects   Studio',18,'#313c2c')
body+=rect(130,204,940,460,'#d6d9c4')+rect(735,204,335,460,'#a5b6a0')
body+='<path d="M360 617V392Q360 310 430 310H620V617Z" fill="#50644a"/><path d="M405 617V401Q405 363 448 363H574V617Z" fill="#e6dcc4"/>'
body+=rect(565,496,310,30,'#8b5139',5)+rect(595,526,22,138,'#8b5139')+rect(823,526,22,138,'#8b5139')
body+='<ellipse cx="740" cy="493" rx="30" ry="10" fill="#e4be8f"/><path d="M720 490L710 435H770L760 490Z" fill="#e4be8f"/>'
body+=text(130,733,'Less, but more considered.',40,'#313c2c',400,'Georgia, serif')+text(132,777,'Furniture and spaces for everyday living.',20,'#56614c')
save('fold-studio','Fold Studio: an editorial furniture website with a sculptural green chair',body,'#bdc9b5')

body='<g transform="rotate(-6 600 450)">'+rect(130,135,600,635,'#fff9e9',4,'filter="url(#shadow)"')
body+=text(178,230,'meridian',66,'#194b46',700)+text(180,271,'A PLACE TO BELONG.',18,'#194b46',400,letter_spacing='3')
body+='<path d="M180 660V395L390 320L600 395V660H490V455L390 420L290 455V660Z" fill="#d5a34d"/>'+text(180,721,'Homes. Neighbours. Possibility.',21,'#194b46')+'</g>'
body+='<g transform="rotate(10 870 500)">'+rect(675,360,380,390,'#194b46',3,'filter="url(#shadow)"')+text(710,439,'meridian',47,'#fff9e9',700)
body+='<path d="M725 635V533L865 482L1005 533V635" fill="none" stroke="#d5a34d" stroke-width="20"/>'+text(710,703,'Welcome home.',25,'#fff9e9')+'</g>'
save('meridian-housing','Meridian Housing: cream stationery and deep-green welcome signage',body,'#e8bf6b')

body='<g transform="rotate(-7 600 450)">'+rect(202,75,800,750,'#ff704f',2,'filter="url(#shadow)"')
body+=text(244,148,'A FESTIVAL FOR EVERYONE',22,'#282b44',600,letter_spacing='3')
body+=text(238,310,'COMMON',120,'#282b44',700)+text(238,440,'GROUND',120,'#282b44',700)
body+='<circle cx="420" cy="581" r="95" fill="#f7efbd"/><path d="M420 486V676M325 581H515M353 514L487 648M353 648L487 514" stroke="#282b44" stroke-width="5"/>'
body+=text(565,557,'Music. Food.',35,'#282b44',600)+text(565,604,'Good company.',35,'#282b44',600)
body+=text(244,760,'18—20 JULY / RIVERSIDE PARK',23,'#282b44',700)+'</g>'
save('common-ground','Common Ground: coral festival poster with bold navy lettering',body,'#f4c5b1')

# A portfolio hero pairs identity work with the finished application.
body=rect(70,70,670,610,'#174b3d',12,'filter="url(#shadow)"')+text(110,174,'NORDWELL',66,'#f4eacb',700)
body+=text(113,215,'COFFEE, WITH CHARACTER.',18,'#f4eacb',400,letter_spacing='3')
body+='<path d="M110 480Q240 285 370 480T700 480M110 540Q240 345 370 540T700 540" fill="none" stroke="#d9bd72" stroke-width="16"/>'
body+=text(112,623,'IDENTITY / PACKAGING / DIGITAL',19,'#f4eacb',600)
body+='<g transform="rotate(9 890 495)">'+rect(704,286,366,515,'#edb18e',10,'filter="url(#shadow)"')+text(739,381,'Nordwell',43,'#263d31',700)
body+='<circle cx="885" cy="540" r="94" fill="#f4eacb"/><path d="M795 540H975M885 450V630" stroke="#263d31" stroke-width="4"/>'+text(740,737,'Good mornings, made.',24,'#263d31')+'</g>'
save('portfolio-showcase','Nordwell brand study: forest-green identity and apricot packaging',body,'#efe9dd')

body=rect(80,80,1040,740,'#fffaf0',16,'filter="url(#shadow)"')+text(123,151,'NORTHBANK',32,'#234940',700,letter_spacing='2')+text(764,144,'Projects   Studio   Contact',18,'#234940')
body+=text(125,275,'Spaces for',65,'#234940',400,'Georgia, serif')+text(125,348,'better living.',65,'#234940',400,'Georgia, serif')
body+=text(128,405,'Thoughtful interiors. Made around you.',19,'#526358')+rect(128,449,225,54,'#234940',27)+text(156,483,'Explore our projects',17,'#fffaf0',600)
body+=rect(620,210,452,485,'#d2c4a4',120)+rect(650,490,390,205,'#b58b67')
body+='<path d="M765 500V325Q765 265 825 265H943V500Z" fill="#e7e1c9"/>'+rect(680,546,320,70,'#f3e8d5',20)+rect(705,605,30,89,'#6f4a35')+rect(948,605,30,89,'#6f4a35')
body+='<ellipse cx="685" cy="486" rx="25" ry="9" fill="#3e6050"/><path d="M685 480Q620 390 685 366Q740 414 685 480Z" fill="#3e6050"/>'
body+=text(126,756,'A considered identity. A clearer path to an enquiry.',23,'#234940')
save('agency-launch','Northbank interiors: responsive website and brand design by Northgate Studio',body,'#d9e6de')

body=rect(90,100,1020,300,'#163e58',12,'filter="url(#shadow)"')+text(140,220,'H / HALDEN RAIL',65,'#f1f6ed',700)
body+=text(145,312,'One network. Every direction.',32,'#f1f6ed')
for i,(label,color) in enumerate((('NORTH','#edbc59'),('COAST','#75c6bd'),('VALLEY','#dca28c'))):
    x=90+i*348
    body+=rect(x,455,324,315,color,10,'filter="url(#shadow)"')+text(x+24,511,'H /',40,'#163e58',700)+text(x+24,648,label,31,'#163e58',700)
    body+=text(x+24,716,'→ Platform '+str(i+1),22,'#163e58',600)
save('agency-rail','Halden Rail: one railway identity across a station sign and three route cards',body,'#e4e9ec')

# Editorial covers stay vector-native so typography remains crisp at any size.
body = '<g transform="rotate(-7 580 455)">'+rect(165,95,700,710,'#fbf7e9',8,'filter="url(#shadow)"')
body += text(215,172,'THE SLOW BUILD',21,'#585c43',700,letter_spacing='4')
body += text(210,290,'Good things',79,'#273c35',400,'Georgia, serif')+text(210,380,'take care.',79,'#273c35',400,'Georgia, serif')
body += rect(215,422,590,2,'#a5ac8f')
for i,(n,label) in enumerate((('01','Craft'),('02','People'),('03','Systems'))):
 y=480+i*83
 body += text(215,y,n,18,'#8d6849',600)+text(278,y,label,31,'#273c35',400,'Georgia, serif')+rect(215,y+25,590,1,'#d4d4bd')
body += text(215,742,'INDEPENDENT ESSAYS / A QUIETER KIND OF PROGRESS',13,'#585c43',600)+'</g>'
body += '<g transform="rotate(12 930 530)">'+rect(848,240,32,510,'#997253',8)+rect(856,240,8,430,'#c49f7c')+'<path d="M848 750L864 795L880 750Z" fill="#d6bd8d"/></g>'
save('editorial-notes','The Slow Build: original editorial cover and writing desk',body,'#d6decb')

body = '<g transform="rotate(-5 600 450)">'+rect(135,95,930,710,'#fcf9ed',10,'filter="url(#shadow)"')
body += text(183,170,'YOUR NEXT CHAPTER',19,'#204568',700,letter_spacing='3')
body += text(183,259,'A plan you can follow.',50,'#204568',400,'Georgia, serif')
for i,(number,label,note) in enumerate((('01','What matters','Start with your goals.'),('02','Where you stand','Bring the whole picture together.'),('03','What comes next','Agree clear, practical steps.'))):
 y=353+i*123
 body += '<circle cx="220" cy="'+str(y)+'" r="30" fill="#e3ebee"/>'+text(206,y+7,number,20,'#204568',700)
 body += text(280,y-4,label,28,'#204568',600)+text(280,y+33,note,21,'#647783')
body += rect(184,722,830,2,'#c8d4d8')+text(184,766,'GOALS / OPTIONS / NEXT STEPS',18,'#647783',600)+'</g>'
save('financial-plan','An original planning notebook with goals, options and next steps',body,'#dce6e9')

body=rect(85,70,1030,760,'#222b47',12,'filter="url(#shadow)"')
body+=text(135,143,'LONGFORM / BRISTOL',24,'#b9c6ff',700,letter_spacing='3')
body+=text(128,296,'BUILD FOR',103,'#f7f5ea',700)+text(128,408,'THE LONG',103,'#f7f5ea',700)+text(128,520,'RUN.',103,'#f7f5ea',700)
body+='<path d="M650 690L865 475L1030 640M650 770L865 555L1030 720M650 850L865 635L1030 800" fill="none" stroke="#92c9ad" stroke-width="24"/>'
body+=text(135,690,'14—15 MAY',35,'#b9c6ff',600)+text(135,746,'TALKS / PEOPLE / PERSPECTIVE',18,'#f7f5ea',600)
save('conference-poster','Longform conference: bold original type and intersecting pathways',body,'#e4e8f4')
print(f'Wrote {len(list(OUT.glob("*.svg")))} showcase illustrations')
