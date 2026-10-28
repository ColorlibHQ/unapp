<?php
/** Optional service copy in ordinary Heading and Paragraph blocks. @package Unapp */
defined( 'ABSPATH' ) || exit;

/** Bounded scalar form fields, or the equivalent internal profile array. */
function unapp_service_cards_sanitize( $source ) {
	$cards = array();
	$input = is_array( $source['service_cards'] ?? null ) ? $source['service_cards'] : array();
	for ( $i = 0; $i < 6; ++$i ) {
		$title = isset( $source['service_cards_present'] ) ? ( $source[ 'service_title_' . $i ] ?? '' ) : ( $input[ $i ]['title'] ?? '' );
		$description = isset( $source['service_cards_present'] ) ? ( $source[ 'service_description_' . $i ] ?? '' ) : ( $input[ $i ]['description'] ?? '' );
		$title = is_string( $title ) ? sanitize_text_field( $title ) : '';
		$description = is_string( $description ) ? sanitize_textarea_field( $description ) : '';
		if ( $title || $description ) { $cards[ $i ] = array( 'title' => $title, 'description' => $description ); }
	}
	return $cards;
}

function unapp_service_cards_validate( $source ) {
	if ( isset( $source['service_cards'] ) && ! is_array( $source['service_cards'] ) ) { return new WP_Error( 'unapp_services_invalid', __( 'Enter service cards as names and descriptions.', 'unapp' ) ); }
	foreach ( $source['service_cards'] ?? array() as $index => $card ) {
		if ( ! is_int( $index ) || $index < 0 || $index >= 6 || ! is_array( $card ) || ! is_string( $card['title'] ?? '' ) || ! is_string( $card['description'] ?? '' ) ) { return new WP_Error( 'unapp_services_invalid', __( 'Use the available service name and description fields.', 'unapp' ) ); }
	}
	foreach ( unapp_service_cards_sanitize( $source ) as $card ) {
		if ( ! $card['title'] || ! $card['description'] || mb_strlen( $card['title'] ) > 120 || mb_strlen( $card['description'] ) > 600 ) {
			return new WP_Error( 'unapp_services_invalid', __( 'Give each service a name (up to 120 characters) and a short description (up to 600 characters), or leave both fields empty to keep that starter card.', 'unapp' ) );
		}
	}
	return true;
}

/** Find the starter's card row, excluding section introductions and price labels. */
function unapp_service_card_fields( $blocks, $path = array() ) {
	foreach ( $blocks as $index => $block ) {
		$here = array_merge( $path, array( $index ) );
		if ( ( 'core/group' === $block['blockName'] && 'grid' === ( $block['attrs']['layout']['type'] ?? '' ) ) || 'core/columns' === $block['blockName'] ) {
			$cards = array();
			foreach ( $block['innerBlocks'] as $card_index => $card ) {
				$fields = array();
				foreach ( $card['innerBlocks'] as $field_index => $field ) {
					if ( 'core/heading' === $field['blockName'] && 3 === ( $field['attrs']['level'] ?? 2 ) ) { $fields['title'] = array( 'path' => array_merge( $here, array( $card_index, $field_index ) ), 'block' => $field ); }
					if ( 'core/paragraph' === $field['blockName'] && 'muted' === ( $field['attrs']['textColor'] ?? '' ) ) { $fields['description'] = array( 'path' => array_merge( $here, array( $card_index, $field_index ) ), 'block' => $field ); }
				}
				if ( count( $fields ) !== 2 ) { $cards = array(); break; }
				$cards[] = $fields;
			}
			if ( count( $cards ) >= 2 && count( $cards ) <= 6 ) { return $cards; }
		}
		$found = unapp_service_card_fields( $block['innerBlocks'], $here );
		if ( $found ) { return $found; }
	}
	return array();
}

/** Explicit starter sections, never testimonials, prices, people or arbitrary grids. */
function unapp_service_card_spec( $site ) {
	$slug = str_replace( 'unapp/demo-', '', $site['home'] ?? '' );
	if ( ! in_array( $slug, array( 'portfolio', 'agency', 'medical', 'finance', 'coach', 'photographer', 'home-services', 'cleaning', 'salon' ), true ) ) { return array(); }
	$blocks = parse_blocks( unapp_get_pattern_markup( 'unapp/' . $slug . '-services' ) );
	$roots = array_values( array_filter( $blocks, static function ( $block ) { return (bool) $block['blockName']; } ) );
	if ( count( $roots ) !== 1 || empty( $roots[0]['attrs']['anchor'] ) ) { return array(); }
	$cards = unapp_service_card_fields( array( $roots[0] ) );
	return $cards ? array( 'anchor' => $roots[0]['attrs']['anchor'], 'cards' => $cards ) : array();
}

/** Mark only known, unchanged starter fields; older direct edits are never adopted. */
function unapp_service_cards_prepare( $markup, $site, $profile ) {
	$spec = unapp_service_card_spec( $site );
	if ( ! $spec ) { return $markup; }
	$blocks = parse_blocks( $markup );
	foreach ( $blocks as &$root ) {
		if ( ( $root['attrs']['anchor'] ?? '' ) !== $spec['anchor'] ) { continue; }
		foreach ( $spec['cards'] as $index => $fields ) {
			foreach ( $fields as $field => $item ) {
				$path = $item['path']; array_shift( $path );
				$target =& $root;
				foreach ( $path as $position ) {
					if ( ! isset( $target['innerBlocks'][ $position ] ) ) { unset( $target ); continue 2; }
					$target =& $target['innerBlocks'][ $position ];
				}
				if ( empty( $target['attrs']['metadata']['unappService'] ) && $target['blockName'] === $item['block']['blockName'] && $target['innerHTML'] === $item['block']['innerHTML'] ) {
					$target['attrs']['metadata']['unappService'] = array( 'index' => $index, 'field' => $field, 'baseline' => hash( 'sha256', $target['innerHTML'] ) );
				}
				unset( $target );
			}
		}
	}
	unset( $root );
	$changed = 0; $kept = 0;
	return unapp_service_cards_update( serialize_blocks( $blocks ), $profile, $changed, $kept );
}

/** Update linked fields independently, preserving card layout, icons, prices and links. */
function unapp_service_cards_update( $markup, $profile, &$changed, &$kept ) {
	$walk = static function ( $blocks ) use ( &$walk, $profile, &$changed, &$kept ) {
		foreach ( $blocks as &$block ) {
			$marker = $block['attrs']['metadata']['unappService'] ?? null;
			if ( is_array( $marker ) && in_array( $marker['field'] ?? '', array( 'title', 'description' ), true ) ) {
				$value = $profile['service_cards'][ $marker['index'] ?? -1 ][ $marker['field'] ] ?? '';
				if ( $value ) {
					$candidate = preg_replace_callback( '~^(\s*<(h[1-6]|p)\b[^>]*>).*?(</\2>\s*)$~s', static function ( $match ) use ( $value ) { return $match[1] . nl2br( esc_html( $value ), false ) . $match[3]; }, $block['innerHTML'], 1 );
					if ( $candidate !== $block['innerHTML'] ) {
						if ( ! empty( $block['attrs']['metadata']['bindings'] ) || ! hash_equals( (string) ( $marker['baseline'] ?? '' ), hash( 'sha256', $block['innerHTML'] ) ) ) { ++$kept; }
						elseif ( in_array( $block['blockName'], array( 'core/heading', 'core/paragraph' ), true ) && ! $block['innerBlocks'] ) {
							$block['innerHTML'] = $candidate;
							$block['innerContent'] = array( $block['innerHTML'] );
							$block['attrs']['metadata']['unappService']['baseline'] = hash( 'sha256', $block['innerHTML'] );
							++$changed;
						}
					}
				}
			}
			$block['innerBlocks'] = $walk( $block['innerBlocks'] );
		}
		return $blocks;
	};
	return serialize_blocks( $walk( parse_blocks( $markup ) ) );
}

function unapp_render_service_card_fields( $site, $profile ) {
	$spec = unapp_service_card_spec( $site );
	if ( ! $spec ) { return; }
	$cards = unapp_service_cards_sanitize( $profile );
	?>
	<details class="unapp-service-fields"><summary><?php esc_html_e( 'Personalize service cards (optional)', 'unapp' ); ?></summary><p><?php esc_html_e( 'Enter a name and description for each card you want to replace. Blank rows keep the starter content; review any remaining examples. Icons, photos, prices and buttons stay as they are. Later profile updates preserve direct edits in WordPress. Clear a row to stop updating it, then remove unwanted cards in the native editor.', 'unapp' ); ?></p><input type="hidden" name="service_cards_present" value="1">
	<?php foreach ( $spec['cards'] as $index => $fields ) : ?><fieldset style="border:1px solid #c3c4c7;padding:16px;margin:16px 0;min-width:0"><legend><?php /* translators: %d: card position. */ printf( esc_html__( 'Service card %d', 'unapp' ), $index + 1 ); ?></legend>
	<p><label for="unapp-service-title-<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Service name', 'unapp' ); ?></label><input class="large-text" style="width:100%;box-sizing:border-box" id="unapp-service-title-<?php echo esc_attr( $index ); ?>" name="service_title_<?php echo esc_attr( $index ); ?>" maxlength="120" type="text" value="<?php echo esc_attr( $cards[ $index ]['title'] ?? '' ); ?>"></p>
	<p><label for="unapp-service-description-<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Short service description', 'unapp' ); ?></label><textarea class="large-text" style="width:100%;box-sizing:border-box" id="unapp-service-description-<?php echo esc_attr( $index ); ?>" name="service_description_<?php echo esc_attr( $index ); ?>" maxlength="600" rows="3"><?php echo esc_textarea( $cards[ $index ]['description'] ?? '' ); ?></textarea></p></fieldset><?php endforeach; ?></details>
	<?php
}

/** Share the same scalar payload between Back, preview and import forms. */
function unapp_service_card_payload( $profile ) {
	$fields = array( 'service_cards_present' => '1' );
	foreach ( unapp_service_cards_sanitize( $profile ) as $index => $card ) {
		$fields[ 'service_title_' . $index ] = $card['title'];
		$fields[ 'service_description_' . $index ] = $card['description'];
	}
	return $fields;
}
