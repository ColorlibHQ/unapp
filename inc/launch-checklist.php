<?php
/**
 * Read-only starter checks and explicit owner reviews before launch.
 *
 * @package Unapp
 */
defined( 'ABSPATH' ) || exit;
const UNAPP_LAUNCH_REVIEWS = 'unapp_launch_reviews';

/** @return string Checklist admin URL. */
function unapp_launch_url() {
	return admin_url( 'themes.php?page=unapp-launch-checklist' );
}

/** @return array Latest starter, or the Home/Blog setup created on activation. */
function unapp_launch_active_starter() {
	$active = get_option( UNAPP_STARTER_OPTION, array() );
	if ( ! empty( $active['pages'] ) ) {
		return $active;
	}
	$setup = get_option( UNAPP_SETUP_OPTION, array() );
	return ! empty( $setup['home'] ) ? array( 'slug' => 'saas', 'pages' => array( 'home' => $setup['home'], 'blog' => $setup['blog'] ?? 0 ), 'time' => $setup['time'] ?? 0, 'automatic' => true ) : array();
}

/** @return array Current starter pages and shared design content, with edit links. */
function unapp_launch_sources() {
	$active = unapp_launch_active_starter();
	if ( empty( $active['pages'] ) ) {
		return array();
	}
	$sources = array();
	$pages = $active['pages'];
	if ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ) {
		$pages['current_home'] = (int) get_option( 'page_on_front' );
	}
	foreach ( $pages as $key => $id ) {
		if ( 'blog' === $key ) {
			continue;
		}
		$post = absint( $id ) ? get_post( $id ) : null;
		$sources[ 'page:' . $id ] = array(
			'title' => $post ? $post->post_title : __( 'Missing starter page', 'unapp' ),
			'content' => $post ? $post->post_content : '',
			'published' => $post && 'page' === $post->post_type && 'publish' === $post->post_status,
			'page' => true,
			'state' => $post ? unapp_starter_post_fingerprint( $id ) : '',
			'edit' => $post && current_user_can( 'edit_post', $id ) ? get_edit_post_link( $id, 'raw' ) : admin_url( 'edit.php?post_type=page' ),
		);
	}
	foreach ( array( 'header' => __( 'Header', 'unapp' ), 'footer' => __( 'Footer', 'unapp' ) ) as $name => $title ) {
		$part = unapp_get_customised_part( $name );
		$sources[ $name ] = array( 'title' => $title, 'content' => $part ? $part->post_content : unapp_part_file_content( $name ), 'page' => false, 'edit' => add_query_arg( array( 'postType' => 'wp_template_part', 'postId' => get_stylesheet() . '//' . $name ), admin_url( 'site-editor.php' ) ) );
	}
	$nav_id = (int) get_option( UNAPP_STARTER_NAV_OPTION );
	$nav = $nav_id ? get_post( $nav_id ) : null;
	if ( $nav && 'wp_navigation' === $nav->post_type && 'publish' === $nav->post_status ) {
		$sources['menu'] = array( 'title' => __( 'Starter menu', 'unapp' ), 'content' => $nav->post_content, 'page' => false, 'edit' => admin_url( 'site-editor.php' ) );
	}
	return $sources;
}

/**
 * Match the bundled demo numbers, not every real number containing 555.
 *
 * @param string $phone Displayed number or telephone destination.
 * @return bool
 */
function unapp_launch_demo_phone( $phone ) {
	$number = preg_replace( '/[^0-9]/', '', $phone );
	if ( 0 === strpos( $number, '44' ) ) {
		$number = '0' . substr( $number, 2 );
	}
	return in_array( $number, array( '1235235598', '01135550177', '01145550128', '01175550192', '012255550163', '012465550114', '01315550142', '013925550146', '01515550188', '01615550134' ), true );
}

/**
 * Scan saved blocks without rendering a plugin form or making external requests.
 * Resolve pattern/synced-block/navigation references with a cycle guard.
 *
 * @param string $markup Saved block content.
 * @param array  $path   Reference path for cycle detection.
 * @return array Findings, visible text, and a fingerprint of all scanned content.
 */
function unapp_launch_inspect( $markup, $path = array() ) {
	$found = array( 'contacts' => false, 'placeholders' => false, 'texts' => array(), 'slots' => false, 'fingerprint' => hash( 'sha256', $markup ) );
	if ( count( $path ) > 8 ) {
		$found['placeholders'] = true;
		return $found;
	}
	$scan_url = static function ( $url ) use ( &$found ) {
		if ( ! is_string( $url ) ) {
			$found['placeholders'] = true;
			return;
		}
		if ( preg_match( '~[a-z0-9._%+-]+@(?:[a-z0-9-]+\.)*(?:example\.(?:com|org|net)|[a-z0-9-]+\.example)(?![a-z0-9.-])~i', $url ) ) {
			$found['contacts'] = true;
		}
		if ( 0 === stripos( $url, 'tel:' ) && unapp_launch_demo_phone( substr( $url, 4 ) ) ) {
			$found['contacts'] = true;
		}
		if ( ( 0 === stripos( $url, 'mailto:' ) && '' === trim( substr( $url, 7 ) ) )
			|| ( 0 === stripos( $url, 'tel:' ) && ! preg_match( '/[0-9]/', substr( $url, 4 ) ) ) ) {
			$found['placeholders'] = true;
		}
		$host = wp_parse_url( $url, PHP_URL_HOST );
		if ( '' === trim( $url ) || '#' === trim( $url ) || ( $host && preg_match( '/(?:^|\.)(?:example\.(?:com|org|net)|example|invalid)$/i', $host ) ) ) {
			$found['placeholders'] = true;
		}
	};
	$scan_text = static function ( $text ) use ( &$found ) {
		$normalized = trim( preg_replace( '/\s+/u', ' ', $text ) );
		if ( preg_match( '~[a-z0-9._%+-]+@(?:[a-z0-9-]+\.)*(?:example\.(?:com|org|net)|[a-z0-9-]+\.example)(?![a-z0-9.-])~i', $normalized ) ) {
			$found['contacts'] = true;
		}
		preg_match_all( '/\b0\d{3,4}[ -]\d{3}[ -]\d{4}\b|\+1 235 2355 98|(?<![0-9])\+?[0-9]{10,13}(?![0-9])/', $normalized, $phones );
		foreach ( $phones[0] as $phone ) {
			$found['contacts'] = $found['contacts'] || unapp_launch_demo_phone( $phone );
		}
		if ( preg_match( '/lorem ipsum|your (?:business name|address here)|\[insert .+?\]/i', $normalized ) ) {
			$found['placeholders'] = true;
		}
		if ( mb_strlen( $normalized ) >= 40 ) {
			$found['texts'][ $normalized ] = true;
		}
		return $text;
	};
	foreach ( parse_blocks( $markup ) as $block ) {
		$name  = $block['blockName'];
		$attrs = $block['attrs'];
		$found['slots'] = $found['slots'] || (bool) preg_match( '/(?:^|\s)unapp-form-slot(?:\s|$)/', $attrs['className'] ?? '' );
		$toggle = 'core/button' === $name && preg_match( '/(?:^|\s)unapp-(?:period|scheme-toggle)(?:\s|$)/', $attrs['className'] ?? '' );
		if ( ! $toggle && in_array( $name, array( 'core/navigation-link', 'core/social-link', 'core/button' ), true ) && isset( $attrs['url'] ) ) {
			$scan_url( $attrs['url'] );
		}
		$html = $block['innerHTML'];
		if ( ! $toggle ) {
			$tags = new WP_HTML_Tag_Processor( $html );
			while ( $tags->next_tag( 'a' ) ) {
				$href = $tags->get_attribute( 'href' );
				if ( null !== $href ) {
					$scan_url( (string) $href );
				}
			}
		}
		unapp_map_html_text( $html, $scan_text );
		if ( in_array( $name, array( 'core/navigation-link', 'core/social-link' ), true ) && is_string( $attrs['label'] ?? null ) ) {
			$scan_text( $attrs['label'] );
		}
		$nested = '';
		$reference = '';
		if ( 'core/pattern' === $name ) {
			$reference = 'pattern:' . ( $attrs['slug'] ?? '' );
			$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $attrs['slug'] ?? '' );
			$nested = $pattern['content'] ?? '';
		} elseif ( in_array( $name, array( 'core/block', 'core/navigation' ), true ) && ! empty( $attrs['ref'] ) ) {
			$reference = 'post:' . absint( $attrs['ref'] );
			$post = get_post( $attrs['ref'] );
			$expected = 'core/block' === $name ? 'wp_block' : 'wp_navigation';
			$nested = $post && $expected === $post->post_type && 'publish' === $post->post_status ? $post->post_content : '';
		}
		if ( $reference ) {
			if ( '' === $nested || in_array( $reference, $path, true ) ) {
				$found['placeholders'] = true;
			} else {
				$nested_path = array_merge( $path, array( $reference ) );
				$child = unapp_launch_inspect( $nested, $nested_path );
				$found = unapp_launch_merge_findings( $found, $child );
			}
		}
		foreach ( $block['innerBlocks'] as $child_block ) {
			$child = unapp_launch_inspect( serialize_block( $child_block ), $path );
			$found = unapp_launch_merge_findings( $found, $child );
		}
	}
	return $found;
}

/** @return array Combined findings from a referenced or nested block. */
function unapp_launch_merge_findings( $found, $child ) {
	foreach ( array( 'contacts', 'placeholders', 'slots' ) as $flag ) {
		$found[ $flag ] = $found[ $flag ] || $child[ $flag ];
	}
	$found['texts'] += $child['texts'];
	$found['fingerprint'] = hash( 'sha256', $found['fingerprint'] . $child['fingerprint'] );
	return $found;
}

/** @return array Long passages from the current starter, used as review hints. */
function unapp_launch_starter_texts() {
	$active = unapp_launch_active_starter();
	$sites = unapp_get_starter_sites();
	if ( ! isset( $sites[ $active['slug'] ?? '' ] ) ) {
		return array();
	}
	$site = $sites[ $active['slug'] ];
	$slugs = array( $site['home'], $site['footer'] ?? 'unapp/footer' );
	foreach ( $site['pages'] as $page ) {
		$slugs = array_merge( $slugs, $page['patterns'] ?? array( $page['pattern'] ?? '' ) );
	}
	$texts = array();
	foreach ( array_unique( $slugs ) as $slug ) {
		$scan = unapp_launch_inspect( unapp_get_pattern_markup( $slug ) );
		$texts += $scan['texts'];
	}
	return $texts;
}

/** @return string Fingerprint of the selected contact method and saved form data. */
function unapp_launch_contact_signature() {
	$form = unapp_detect_form();
	$parts = array( get_option( UNAPP_CONTACT_FORM_OPTION, 'auto' ), $form );
	$providers = unapp_form_providers();
	$provider = $providers[ $form['key'] ?? '' ] ?? array();
	if ( ! empty( $form['id'] ) && ! empty( $provider['post_type'] ) && get_post_type( $form['id'] ) === $provider['post_type'] ) {
		$parts[] = unapp_starter_post_fingerprint( $form['id'] );
	}
	if ( 'gravity' === ( $form['key'] ?? '' ) && ! empty( $form['id'] ) && class_exists( 'GFAPI' ) ) {
		$parts[] = GFAPI::get_form( $form['id'] );
	}
	/** Custom providers can include their own form-settings version in this hash. */
	return hash( 'sha256', wp_json_encode( apply_filters( 'unapp_launch_contact_signature', $parts, $form ) ) );
}

/** @return array Fresh automatic findings and review states; nothing is written. */
function unapp_launch_checklist() {
	$active = unapp_launch_active_starter();
	$sources = unapp_launch_sources();
	if ( ! $sources ) {
		return array();
	}
	$issues = array( 'pages' => array(), 'contacts' => array(), 'links' => array(), 'content' => array() );
	$hashes = array();
	$slots = false;
	$baseline = unapp_launch_starter_texts();
	foreach ( $sources as $key => $source ) {
		$scan = unapp_launch_inspect( $source['content'] );
		$hashes[ $key ] = array( $scan['fingerprint'], $source['state'] ?? '', $source['title'] );
		$slots = $slots || $scan['slots'];
		foreach ( array( 'contacts' => 'contacts', 'placeholders' => 'links' ) as $flag => $task ) {
			if ( $scan[ $flag ] ) {
				$issues[ $task ][ $key ] = $source;
			}
		}
		if ( array_intersect_key( $scan['texts'], $baseline ) ) {
			$issues['content'][ $key ] = $source;
		}
		if ( $source['page'] && ! $source['published'] ) {
			$issues['pages'][ $key ] = $source;
		}
	}
	$style = WP_Theme_JSON_Resolver::get_user_data_from_wp_global_styles( wp_get_theme() );
	$contact_signature = unapp_launch_contact_signature();
	$run = hash( 'sha256', wp_json_encode( array( get_stylesheet(), $active ) ) );
	$snapshot = hash( 'sha256', wp_json_encode( array( $run, UNAPP_VERSION, $hashes, $style['post_content'] ?? '', get_option( 'blogname' ), get_option( 'blogdescription' ), get_theme_mods(), get_option( 'show_on_front' ), (int) get_option( 'page_on_front' ), (int) get_option( 'page_for_posts' ), $contact_signature ) ) );
	$saved = get_option( UNAPP_LAUNCH_REVIEWS, array() );
	$reviews = ( $saved['run'] ?? '' ) === $run ? ( $saved['checks'] ?? array() ) : array();
	$choice = get_option( UNAPP_CONTACT_FORM_OPTION, 'auto' );
	$effective = unapp_detect_form();
	$method_available = ! $slots || in_array( $choice, array( 'auto', 'email' ), true ) || unapp_resolve_contact_form( $choice ) || ( $effective && ! empty( $effective['markup'] ) );
	$checks = array(
		'pages' => array( 'label' => __( 'Check that starter pages are published', 'unapp' ), 'description' => __( 'Checks whether the pages created by this starter still exist and are published. Review the listed pages before sharing their links.', 'unapp' ), 'complete' => ! $issues['pages'], 'manual' => false ),
		'contacts' => array( 'label' => __( 'Replace sample contact details', 'unapp' ), 'description' => __( 'Checks for example email addresses and the demo phone numbers, including saved email fallbacks. Edit the listed pages or template parts to replace them.', 'unapp' ), 'complete' => ! $issues['contacts'], 'manual' => false ),
		'links' => array( 'label' => __( 'Finish links and placeholder text', 'unapp' ), 'description' => __( 'Checks for empty links, example destinations, obvious placeholder text and unavailable shared blocks. Pricing and dark-mode controls are excluded.', 'unapp' ), 'complete' => ! $issues['links'], 'manual' => false ),
		'method' => array( 'label' => __( 'Choose an available contact method', 'unapp' ), 'description' => $method_available ? __( 'A form or the saved email fallback is available. Availability does not confirm that messages are delivered.', 'unapp' ) : __( 'The selected form is unavailable. Choose another form or email-only contact below.', 'unapp' ), 'complete' => (bool) $method_available, 'manual' => false ),
		'content' => array( 'label' => __( 'Review business details, prices and testimonials', 'unapp' ), 'description' => __( 'Confirm that names, addresses, opening hours, prices, dates, images and testimonials describe your business. Listed pages still contain passages matching the starter; keeping suitable wording is fine after review.', 'unapp' ), 'complete' => ( $reviews['content'] ?? '' ) === $snapshot, 'manual' => true ),
		'delivery' => array( 'label' => __( 'Test how visitors contact you', 'unapp' ), 'description' => __( 'Open the contact page and check its email and phone links. If you use a form, send a test message and confirm that it arrives in the intended inbox. The theme does not send a test for you.', 'unapp' ), 'complete' => $method_available && ( $reviews['delivery'] ?? '' ) === $snapshot, 'manual' => true, 'blocked' => ! $method_available ),
		'mobile' => array( 'label' => __( 'Review mobile navigation and buttons', 'unapp' ), 'description' => __( 'Visit the site on a narrow screen. Open and close the menu, follow the main buttons, and check that text and images fit. Also try the menu and controls with a keyboard.', 'unapp' ), 'complete' => ( $reviews['mobile'] ?? '' ) === $snapshot, 'manual' => true ),
	);
	$complete = count( array_filter( $checks, static function ( $check ) { return $check['complete']; } ) );
	return array( 'run' => $run, 'snapshot' => $snapshot, 'checks' => $checks, 'issues' => $issues, 'complete' => $complete, 'total' => count( $checks ) );
}

/**
 * Save explicit reviews only for the current snapshot; never mark automated checks.
 *
 * @param string $snapshot Snapshot shown when the form was opened.
 * @param array  $reviewed Manual task keys checked by the owner.
 * @return true|WP_Error
 */
function unapp_save_launch_reviews( $snapshot, $reviewed ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return new WP_Error( 'unapp_launch_forbidden', __( 'You are not allowed to review the site design.', 'unapp' ) );
	}
	$data = unapp_launch_checklist();
	if ( ! $data || ! is_string( $snapshot ) || ! hash_equals( $data['snapshot'], $snapshot ) ) {
		return new WP_Error( 'unapp_launch_changed', __( 'The site changed after this checklist was opened. Review the refreshed checklist before saving.', 'unapp' ) );
	}
	$checks = array();
	foreach ( $data['checks'] as $key => $check ) {
		if ( $check['manual'] && empty( $check['blocked'] ) && in_array( $key, $reviewed, true ) ) {
			$checks[ $key ] = $data['snapshot'];
		}
	}
	$record = array( 'run' => $data['run'], 'checks' => $checks );
	update_option( UNAPP_LAUNCH_REVIEWS, $record, false );
	return get_option( UNAPP_LAUNCH_REVIEWS ) === $record ? true : new WP_Error( 'unapp_launch_save', __( 'The reviewed items could not be saved.', 'unapp' ) );
}

/** Handle the review form. */
function unapp_handle_launch_reviews() {
	check_admin_referer( 'unapp_launch_reviews' );
	$snapshot = isset( $_POST['snapshot'] ) && is_string( $_POST['snapshot'] ) ? wp_unslash( $_POST['snapshot'] ) : '';
	$reviewed = isset( $_POST['reviewed'] ) && is_array( $_POST['reviewed'] ) ? array_filter( wp_unslash( $_POST['reviewed'] ), 'is_string' ) : array();
	$result = unapp_save_launch_reviews( $snapshot, $reviewed );
	set_transient( 'unapp_launch_notice_' . get_current_user_id(), is_wp_error( $result ) ? array( 'error' => $result->get_error_message() ) : array( 'saved' => true ), 5 * MINUTE_IN_SECONDS );
	wp_safe_redirect( unapp_launch_url() );
	exit;
}
add_action( 'admin_post_unapp_launch_reviews', 'unapp_handle_launch_reviews' );

/** Register the launch workspace under Appearance. */
function unapp_launch_menu() {
	add_theme_page( __( 'Launch checklist', 'unapp' ), __( 'Launch checklist', 'unapp' ), 'edit_theme_options', 'unapp-launch-checklist', 'unapp_render_launch_screen' );
}
add_action( 'admin_menu', 'unapp_launch_menu' );

/** Render progress on the starter screen and the setup completion screen. */
function unapp_render_launch_summary() {
	$data = unapp_launch_checklist();
	if ( ! $data ) {
		return;
	}
	?>
	<div class="unapp-wizard__panel">
		<h3><?php esc_html_e( 'Before you launch', 'unapp' ); ?></h3>
		<p><?php
		/* translators: 1: completed checks, 2: total checks. */
		printf( esc_html__( '%1$d of %2$d launch checks complete. Replace sample content, choose a contact form, and review the site before sharing it.', 'unapp' ), $data['complete'], $data['total'] );
		?></p>
		<a class="button button-primary" href="<?php echo esc_url( unapp_launch_url() ); ?>"><?php esc_html_e( 'Open launch checklist', 'unapp' ); ?></a>
	</div>
	<?php
}

/** Render live findings, manual reviews and the separate contact form selector. */
function unapp_render_launch_screen() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to review the site design.', 'unapp' ), 403 );
	}
	$data = unapp_launch_checklist();
	$notice_key = 'unapp_launch_notice_' . get_current_user_id();
	$notice = get_transient( $notice_key );
	delete_transient( $notice_key );
	?>
	<div class="wrap unapp-launch">
		<h1><?php esc_html_e( 'Launch checklist', 'unapp' ); ?></h1>
		<p><a href="<?php echo esc_url( unapp_finish_url() ); ?>"><?php esc_html_e( 'Back to Finish your website', 'unapp' ); ?></a></p>
		<p><?php esc_html_e( 'Automatic checks find common unfinished starter content. Owner reviews cover things the theme cannot verify. Reviews reset when the site content, design or contact choice changes. This checklist never publishes pages or sends messages.', 'unapp' ); ?></p>
		<p class="description"><?php esc_html_e( 'Scans starter pages, the current static home page, header, footer and starter menu. Blog posts and unrelated pages need your own review.', 'unapp' ); ?></p>
		<?php if ( ! empty( $notice['error'] ) ) : ?><div class="notice notice-error"><p><?php echo esc_html( $notice['error'] ); ?></p></div><?php elseif ( ! empty( $notice['saved'] ) ) : ?><div class="notice notice-success"><p><?php esc_html_e( 'Saved. The checklist reflects the current site.', 'unapp' ); ?></p></div><?php endif; ?>
		<?php if ( ! $data ) : ?>
			<p><?php esc_html_e( 'Apply a starter to get checks for its pages and shared design. You can choose a contact form below even without a starter.', 'unapp' ); ?> <a href="<?php echo esc_url( admin_url( 'themes.php?page=unapp-starter-sites' ) ); ?>"><?php esc_html_e( 'Choose a starter', 'unapp' ); ?></a></p>
		<?php else : ?>
			<section class="unapp-launch__panel" aria-labelledby="unapp-launch-heading">
				<h2 id="unapp-launch-heading"><?php
				/* translators: 1: completed checks, 2: total checks. */
				printf( esc_html__( '%1$d of %2$d checks complete', 'unapp' ), $data['complete'], $data['total'] );
				?></h2>
				<?php if ( $data['complete'] === $data['total'] ) : ?><p><?php esc_html_e( 'All checklist items are complete for this version of your site. Keep reviewing the site as you update it.', 'unapp' ); ?></p><?php endif; ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'unapp_launch_reviews' ); ?>
					<input type="hidden" name="action" value="unapp_launch_reviews">
					<input type="hidden" name="snapshot" value="<?php echo esc_attr( $data['snapshot'] ); ?>">
					<ol class="unapp-launch__checks">
					<?php foreach ( $data['checks'] as $key => $check ) : ?>
						<li>
							<?php if ( $check['manual'] ) : ?>
								<label class="unapp-launch__task"><input type="checkbox" name="reviewed[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $check['complete'] ); ?> <?php disabled( ! empty( $check['blocked'] ) ); ?>> <?php echo esc_html( $check['label'] ); ?></label>
							<?php else : ?><h3 class="unapp-launch__task"><?php echo esc_html( $check['label'] ); ?></h3><?php endif; ?>
							<p class="unapp-launch__status"><?php echo esc_html( $check['complete'] ? ( $check['manual'] ? __( 'Reviewed', 'unapp' ) : __( 'Checked automatically', 'unapp' ) ) : ( $check['manual'] ? __( 'Owner review needed', 'unapp' ) : __( 'Needs attention', 'unapp' ) ) ); ?></p>
							<p><?php echo esc_html( $check['description'] ); ?></p>
							<?php if ( ! empty( $data['issues'][ $key ] ) ) : ?><ul class="unapp-launch__links"><?php foreach ( $data['issues'][ $key ] as $source ) : ?><li><a href="<?php echo esc_url( $source['edit'] ); ?>"><?php echo esc_html( $source['title'] ); ?></a></li><?php endforeach; ?></ul><?php endif; ?>
							<?php if ( 'method' === $key ) : ?><p><a href="#unapp-contact-form"><?php esc_html_e( 'Choose a contact form', 'unapp' ); ?></a></p><?php endif; ?>
							<?php if ( in_array( $key, array( 'delivery', 'mobile' ), true ) ) : ?><p><a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View site in a new tab', 'unapp' ); ?></a></p><?php endif; ?>
						</li>
					<?php endforeach; ?>
					</ol>
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save reviewed items', 'unapp' ); ?></button>
				</form>
			</section>
		<?php endif; ?>
		<?php unapp_render_launch_recommendations(); ?>
		<?php unapp_render_contact_form_selection(); ?>
	</div>
	<style>
		.unapp-launch { max-width: 960px; }
		.unapp-launch__panel { box-sizing: border-box; background: #fff; border: 1px solid #c3c4c7; border-radius: 8px; padding: 20px; margin: 20px 0; scroll-margin-top: 48px; }
		.unapp-launch__panel h2 { margin-top: 0; }
		.unapp-launch__checks { margin: 0 0 24px 24px; }
		.unapp-launch__checks > li { padding: 16px 0; border-bottom: 1px solid #dcdcde; }
		.unapp-launch__recommendation { border-top: 1px solid #dcdcde; padding: 12px 0; }
		.unapp-launch__recommendation li { margin-bottom: 12px; }
		.unapp-launch__task { display: block; margin: 0; font-size: 14px; font-weight: 600; }
		.unapp-launch__status { font-weight: 600; color: #50575e; }
		.unapp-launch__links { display: flex; flex-wrap: wrap; gap: 8px 20px; }
		.unapp-launch__warning { border-inline-start: 4px solid #d63638; padding-inline-start: 12px; }
		.unapp-launch label[for="unapp-contact-form-choice"] { display: block; margin: 16px 0 6px; font-weight: 600; }
		.unapp-launch select { width: 100%; max-width: 640px; }
		.unapp-launch p, .unapp-launch a, .unapp-launch select { overflow-wrap: anywhere; }
	</style>
	<?php
}
