<?php
/** A local, accessible finder for the free native starter catalogue. @package Unapp */
defined( 'ABSPATH' ) || exit;

function unapp_starter_categories() {
	return apply_filters( 'unapp_starter_categories', array( 'business' => __( 'Business', 'unapp' ), 'local' => __( 'Local services', 'unapp' ), 'creative' => __( 'Creative', 'unapp' ), 'wellness' => __( 'Health and wellbeing', 'unapp' ), 'food' => __( 'Food and hospitality', 'unapp' ), 'community' => __( 'Community', 'unapp' ), 'education' => __( 'Education and events', 'unapp' ), 'shop' => __( 'Shop', 'unapp' ), 'publishing' => __( 'Publishing', 'unapp' ), 'technology' => __( 'Technology', 'unapp' ) ) );
}

/** Synonyms help visitors find a suitable foundation, not just an exact title. */
function unapp_starter_metadata( $sites ) {
	$defaults = array(
		'saas' => array( array( 'technology', 'business' ), 'software app startup product technology' ),
		'portfolio' => array( array( 'creative' ), 'designer artist freelancer photographer photography work gallery' ),
		'agency' => array( array( 'business', 'creative' ), 'marketing design studio creative agency consultant consulting' ),
		'finance' => array( array( 'business' ), 'financial adviser advisor accountant accounting investment' ),
		'church' => array( array( 'community' ), 'church faith ministry parish community nonprofit charity' ),
		'blog' => array( array( 'publishing' ), 'blog magazine writer author editorial news' ),
		'fitness' => array( array( 'wellness', 'local' ), 'gym yoga fitness pilates trainer exercise studio' ),
		'restaurant' => array( array( 'food', 'local' ), 'restaurant cafe coffee dining food bistro takeaway' ),
		'shop' => array( array( 'shop' ), 'store ecommerce commerce products retail woocommerce' ),
		'realestate' => array( array( 'business', 'local' ), 'estate agent property real estate homes realtor' ),
		'medical' => array( array( 'wellness', 'local' ), 'medical dentist dental clinic practice therapist healthcare' ),
		'education' => array( array( 'education' ), 'school courses tutor teacher classes learning workshop' ),
		'events' => array( array( 'education', 'community' ), 'conference event festival speakers tickets venue' ),
		'home-services' => array( array( 'local', 'business' ), 'home services handyman repairs maintenance decorating painter carpenter plumbing plumber electrical electrician trades' ),
		'cleaning' => array( array( 'local', 'business' ), 'cleaning cleaner domestic residential commercial housekeeping house home office deep clean' ),
		'salon' => array( array( 'local', 'wellness' ), 'salon barber barbershop hair hairdresser hairdressing hairstylist beauty colour color styling' ),
		'coach' => array( array( 'business', 'education' ), 'coach coaching consultant consulting leadership executive career mentoring strategy team workshop' ),
		'photographer' => array( array( 'creative', 'business' ), 'photographer photography photo editorial commercial landscape still life architecture commissions portfolio' ),
		'nonprofit' => array( array( 'community' ), 'nonprofit non profit not for profit charity charitable community volunteer volunteering fundraising donations giving programmes support' ),
	);
	foreach ( $sites as $slug => &$site ) {
		$meta = $defaults[ $slug ] ?? array( array( 'business' ), '' );
		$site = wp_parse_args( $site, array( 'categories' => $meta[0], 'keywords' => $meta[1] ) );
	}
	return $sites;
}

function unapp_starter_search_normalize( $text ) {
	$text = remove_accents( wp_strip_all_tags( $text ) );
	$text = function_exists( 'mb_strtolower' ) ? mb_strtolower( $text, 'UTF-8' ) : strtolower( $text );
	return trim( preg_replace( '/[^\p{L}\p{N}]+/u', ' ', $text ) );
}

function unapp_starter_search_text( $slug, $site ) {
	$categories = unapp_starter_categories();
	$labels = array_intersect_key( $categories, array_flip( $site['categories'] ?? array() ) );
	return unapp_starter_search_normalize( $slug . ' ' . $site['title'] . ' ' . $site['summary'] . ' ' . ( $site['keywords'] ?? '' ) . ' ' . implode( ' ', $labels ) . ' ' . ( empty( $site['requires'] ) ? __( 'No required plugins', 'unapp' ) : unapp_starter_requirement_label( $site['requires'] ) ) );
}

function unapp_starter_matches( $slug, $site, $query, $category ) {
	if ( $category && ! in_array( $category, $site['categories'] ?? array(), true ) ) { return false; }
	$text = unapp_starter_search_text( $slug, $site );
	foreach ( preg_split( '/\s+/u', unapp_starter_search_normalize( $query ), -1, PREG_SPLIT_NO_EMPTY ) as $word ) {
		if ( false === strpos( $text, $word ) ) { return false; }
	}
	return true;
}

/** GET works without JavaScript; enhancement filters the same bundled cards. */
function unapp_render_starter_finder( $sites, $query, $category ) {
	$count = count( array_filter( $sites, static function ( $site, $slug ) use ( $query, $category ) { return unapp_starter_matches( $slug, $site, $query, $category ); }, ARRAY_FILTER_USE_BOTH ) );
	wp_enqueue_script( 'unapp-starter-finder', get_theme_file_uri( 'assets/js/starter-finder.js' ), array(), UNAPP_VERSION, true );
	?>
	<form method="get" action="<?php echo esc_url( admin_url( 'themes.php' ) ); ?>" class="unapp-finder" role="search" aria-label="<?php esc_attr_e( 'Find a starter', 'unapp' ); ?>">
		<input type="hidden" name="page" value="unapp-starter-sites">
		<p><label for="unapp-starter-search"><?php esc_html_e( 'Find your starter', 'unapp' ); ?></label><input id="unapp-starter-search" type="search" name="starter_search" value="<?php echo esc_attr( $query ); ?>" placeholder="<?php esc_attr_e( 'Try coaching, photography or charity', 'unapp' ); ?>" aria-controls="unapp-starter-results"></p>
		<p><label for="unapp-starter-category"><?php esc_html_e( 'Category', 'unapp' ); ?></label><select id="unapp-starter-category" name="starter_category" aria-controls="unapp-starter-results"><option value=""><?php esc_html_e( 'All categories', 'unapp' ); ?></option><?php foreach ( unapp_starter_categories() as $key => $label ) : ?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $category ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></p>
		<p><button class="button" type="submit"><?php esc_html_e( 'Find starters', 'unapp' ); ?></button> <a href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>" class="unapp-finder__clear"><?php esc_html_e( 'Clear filters', 'unapp' ); ?></a></p>
	</form>
	<p class="unapp-finder__count" role="status" aria-live="polite" aria-atomic="true"><span><?php echo (int) $count; ?></span> <?php /* translators: %d: all available starters. */ printf( esc_html__( 'of %d starters shown', 'unapp' ), count( $sites ) ); ?></p>
	<p class="unapp-finder__empty" <?php if ( $count ) : ?>hidden<?php endif; ?>><?php esc_html_e( 'No starters match. Try a broader search or clear the category.', 'unapp' ); ?></p>
	<style>.unapp-finder{display:flex;align-items:end;gap:16px;flex-wrap:wrap;margin:24px 0}.unapp-finder label{display:block;font-weight:600;margin-bottom:6px}.unapp-finder input,.unapp-finder select{width:100%;max-width:100%;box-sizing:border-box}.unapp-finder p{margin:0}.unapp-finder p:first-of-type{flex:1 1 280px}.unapp-finder .button{margin-right:8px}.unapp-starters [hidden]{display:none!important}.unapp-finder__empty{padding:16px;background:#fff;border-inline-start:4px solid #2271b1}.unapp-starter__requirements{font-weight:600}@media(max-width:600px){.unapp-finder p{width:100%}}</style>
	<?php
}
