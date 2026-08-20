"""Shop templates' translatable text, plus the storefront sections."""
import os, sys; sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from pgen import *

S = "unapp, unapp_shop"

# --------------------------------------------------- hidden partials for templates
write_pattern("hidden-shop-empty-cart", title="Shop: empty cart", cats="unapp", inserter=False,
              keywords="cart, empty, shop",
              desc="The empty-cart message used by the Cart template.",
              body=(heading(t("Your cart is empty"), align="center") + "\n" +
                    para(t("Nothing here yet. Have a look at what is in stock."),
                         align="center", color="muted") + "\n" +
                    buttons([{"text": t("Browse the shop"), "url": "/shop/"}], justify="center")))

write_pattern("hidden-shop-related", title="Shop: related products heading", cats="unapp", inserter=False,
              keywords="shop, related, products",
              desc="The heading above related products on a single product.",
              body=heading(t("You may also like")))

write_pattern("hidden-shop-no-results", title="Shop: no products found", cats="unapp", inserter=False,
              keywords="shop, empty, no results",
              desc="Shown when a product query returns nothing.",
              body=para(t("No products match that. Try a different filter, or browse everything."),
                        align="center", color="muted"))

# --------------------------------------------------- storefront sections
PROMISES = [
    ("package", "Sent within a day", "Ordered before 2pm on a weekday, it leaves the same afternoon."),
    ("refresh", "Sixty days to change your mind", "Unworn, unwashed, and we pay the return postage."),
    ("shield", "Made to last", "Two-year guarantee on everything, repairs at cost after that."),
    ("heart", "Made in small runs", "Roughly two hundred of anything. When it is gone we make it again, or we do not."),
]
body = section_std(
    intro(eyebrow_text=t("Why buy here", "Section eyebrow label"),
          title=t("The boring promises that matter")) + "\n" +
    grid(loop("unapp_shop_promises", "unapp_shop_promise",
              icon_card("$unapp_shop_promise['icon']",
                        php("$unapp_shop_promise['title']"),
                        php("$unapp_shop_promise['text']"))), cols=4),
    variation="is-style-section-soft")
write_pattern("shop-promise", title="Shop: promises", cats=S + ", unapp_features",
              keywords="shop, shipping, returns, guarantee, promise, ecommerce",
              desc="Four reassurance cards: delivery, returns, guarantee and how the run sizes work.",
              body=body,
              php_prelude=php_rows("unapp_shop_promises", ("icon", "title", "text"), PROMISES,
                                   "Shop promise"))

# featured products, using Woo's own collection block so it works on any store
featured = ('<!-- wp:woocommerce/product-collection {"queryId":0,"query":{"perPage":4,"pages":1,"offset":0,'
            '"postType":"product","order":"desc","orderBy":"date","search":"","exclude":[],"inherit":false,'
            '"taxQuery":{},"isProductCollectionBlock":true,"woocommerceOnSale":false,'
            '"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],'
            '"woocommerceHandPickedProducts":[]},"tagName":"div","displayLayout":{"type":"flex","columns":4,'
            '"shrinkColumns":true},"queryContextIncludes":["collection"],"align":"wide","dimensions":{"widthType":"fill","fixedWidth":""},'
            '"convertedFromProducts":false} -->\n'
            '<div class="wp-block-woocommerce-product-collection alignwide">\n'
            '<!-- wp:woocommerce/product-template -->\n'
            '<!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true,'
            '"style":{"border":{"radius":"20px"}}} /-->\n'
            '<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"large",'
            '"__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->\n'
            '<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"fontSize":"small"} /-->\n'
            '<!-- /wp:woocommerce/product-template -->\n'
            '</div>\n<!-- /wp:woocommerce/product-collection -->')
body = section_std(
    intro(eyebrow_text=t("New in", "Section eyebrow label"),
          title=t("The four most recent things"),
          lead=t("Everything is made in small runs, so this changes more often than we plan for.")) + "\n" +
    featured + "\n" +
    buttons([{"text": t("See everything in stock"), "url": "/shop/"}], justify="center"))
write_pattern("shop-featured", title="Shop: featured products", cats=S + ", unapp_content, products",
              keywords="shop, products, featured, new, grid, woocommerce",
              desc="Four newest products from the store, with a link to the full shop. Needs WooCommerce.",
              body=body)

# storefront hero
body = section_std(
    split(
        eyebrow(t("Workshop and shop", "Shop hero eyebrow"), align="left") + "\n" +
        heading(t("Things we make, in numbers we can stand behind"), size="xxx-large",
                line_height="1.05") + "\n" +
        para(t("A small workshop in Leeds making bags, aprons and a few things that did not fit either category. Two hundred of anything, then we stop and think about it."),
             color="muted", size="large") + "\n" +
        buttons([{"text": t("Shop everything"), "url": "/shop/"},
                 {"text": t("How it is made"), "url": "#making", "style": "outline"}]),
        image(uri("assets/images/abstract/studio-1.svg"), tattr("The workshop"), radius=CARD_RADIUS),
        left_width="55%", right_width="45%"),
    gap="0")
write_pattern("shop-hero", title="Shop: hero", cats=S + ", banner, featured",
              keywords="shop, hero, store, ecommerce, workshop",
              desc="A storefront introduction with two calls to action and an image.",
              body=body)

# the workshop
body = section_std(
    split(
        image(uri("assets/images/abstract/desk.svg"), tattr("The bench"), radius=CARD_RADIUS),
        eyebrow(t("How it is made", "Section eyebrow label"), align="left") + "\n" +
        heading(t("Four people, two benches, no factory")) + "\n" +
        para(t("Leather from a tannery in Devon that has been at it since 1863, canvas from Dundee, and thread we buy in quantities that embarrass our accountant."),
             color="muted", size="large") + "\n" +
        lst([t("Cut, stitched and finished in Leeds"),
             t("Every piece signed by whoever made it"),
             t("Repairs at cost, for as long as we exist"),
             t("Offcuts go to a bookbinder down the road")]),
        left_width="45%", right_width="55%"),
    gap="0")
write_pattern("shop-workshop", title="Shop: the workshop", cats=S + ", unapp_company, about",
              keywords="shop, about, workshop, making, materials, craft",
              desc="Where the goods are made and what from, beside a photograph.",
              body=body)

# shop FAQ
FAQ = [
    ("When will it arrive?",
     "Ordered before 2pm on a weekday, it leaves the same afternoon and usually lands the next day in the UK. Europe is three to five days, the rest of the world a week or so. Tracking goes out with the dispatch email."),
    ("What if it is not right?",
     "Send it back within sixty days, unworn and unwashed, and we refund the lot including what you paid to have it delivered. The return postage is on us."),
    ("Do you repair things?",
     "Yes, at cost, for as long as we are still here. Post it to the workshop with a note about what happened and we will quote before doing anything."),
    ("Is it really made where you say?",
     "Cut, stitched and finished in Leeds by four people whose names are on the About page. Materials come from Devon, Dundee and one German thread mill."),
]
body = section_std(
    intro(eyebrow_text=t("Before you order", "Section eyebrow label"),
          title=t("Delivery, returns and repairs")) + "\n" +
    faq_list([(t(q, "FAQ question"), t(a, "FAQ answer")) for q, a in FAQ]))
write_pattern("shop-faq", title="Shop: delivery and returns", cats=S + ", unapp_utility, faq",
              keywords="shop, faq, delivery, returns, repairs, shipping",
              desc="The four questions asked before every online order.",
              body=body)

body = band(t("Everything is made in runs of about two hundred"),
            t("When something sells out we decide whether to make it again. The newsletter is the only warning you get."),
            [{"text": t("Shop everything"), "url": "/shop/", "bg": "base", "color": "contrast"},
             {"text": t("Join the list"), "url": "#subscribe", "style": "outline", "color": "base"}])
write_pattern("shop-cta", title="Shop: closing band", cats=S + ", unapp_cta, call to action",
              keywords="shop, cta, newsletter, stock, ecommerce",
              desc="A closing band about small production runs, on the palette gradient.",
              body=body)

# the product search form, so its placeholder and button are translatable
write_pattern("hidden-shop-search", title="Shop: product search", cats="unapp", inserter=False,
              keywords="shop, search, products",
              desc="The product search form used by the shop search template.",
              body=('<!-- wp:search {"label":"","showLabel":false,"placeholder":"' + tattr("Search products…") +
                    '","buttonText":"' + tattr("Search") + '","align":"center","buttonPosition":"button-inside",'
                    '"style":{"border":{"radius":"999px"}}} /-->'))

print("batch 19 written: 4 hidden partials + 6 shop sections")

# --------------------------------------------------- visitor scheme toggle
toggle = ('<!-- wp:buttons -->\n<div class="wp-block-buttons">\n'
          '<!-- wp:button {"className":"is-style-outline unapp-scheme-toggle","fontSize":"small"} -->\n'
          '<div class="wp-block-button has-custom-font-size is-style-outline unapp-scheme-toggle has-small-font-size">'
          '<a class="wp-block-button__link has-small-font-size has-custom-font-size wp-element-button" href="#">'
          '<span class="unapp-scheme-toggle__light">' + t("Dark") + '</span>'
          '<span class="unapp-scheme-toggle__dark">' + t("Light") + '</span>'
          '</a></div>\n'
          '<!-- /wp:button -->\n</div>\n<!-- /wp:buttons -->')
write_pattern("theme-toggle", title="Dark mode toggle", cats="unapp, unapp_utility, header",
              keywords="dark mode, light, scheme, toggle, accessibility",
              desc="A button that lets a visitor read the site light or dark and remembers the choice. Add it to the header; the dark tokens and script load only where it appears.",
              body=toggle, viewport=600)

print("scheme toggle pattern written")
