<?php
/**
 * Rewrite a starter's words for the site's own business.
 *
 * Most AI features in themes generate layouts, and generated layouts are bad.
 * Unapp already has layouts that are measured and verified, so the model is
 * pointed at the only part it is actually good at: the words. It receives the
 * text already on the page and a description of the business, and returns
 * replacements one for one. Nothing about the structure is sent, and nothing
 * about the structure comes back.
 *
 * Three providers are supported because people already have an account with
 * one of them. Keys are the site's own, stored in the options table, and are
 * never sent anywhere except that provider.
 *
 * @package Unapp_Library
 */

defined( 'ABSPATH' ) || exit;

const UNAPP_AI_SETTINGS = 'unapp_library_ai';

/**
 * The providers, their endpoints and how each wants a request shaped.
 *
 * @return array[]
 */
function unapp_ai_providers() {
	return array(
		'anthropic' => array(
			'label'    => __( 'Claude', 'unapp-library' ),
			'endpoint' => 'https://api.anthropic.com/v1/messages',
			'model'    => 'claude-sonnet-5',
			'keys_url' => 'https://console.anthropic.com/settings/keys',
		),
		'openai'    => array(
			'label'    => __( 'ChatGPT', 'unapp-library' ),
			'endpoint' => 'https://api.openai.com/v1/chat/completions',
			'model'    => 'gpt-5',
			'keys_url' => 'https://platform.openai.com/api-keys',
		),
		'google'    => array(
			'label'    => __( 'Gemini', 'unapp-library' ),
			'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent',
			'model'    => 'gemini-2.5-pro',
			'keys_url' => 'https://aistudio.google.com/app/apikey',
		),
	);
}

/**
 * Stored AI settings.
 *
 * @return array
 */
function unapp_ai_settings() {
	$defaults = array( 'provider' => 'anthropic', 'key' => '', 'model' => '' );
	$saved    = get_option( UNAPP_AI_SETTINGS, array() );
	$settings = wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );

	// A key defined in wp-config.php never touches the database.
	if ( defined( 'UNAPP_AI_KEY' ) && '' !== (string) UNAPP_AI_KEY ) {
		$settings['key']      = (string) UNAPP_AI_KEY;
		$settings['constant'] = true;
	}
	if ( defined( 'UNAPP_AI_PROVIDER' ) && isset( unapp_ai_providers()[ UNAPP_AI_PROVIDER ] ) ) {
		$settings['provider'] = UNAPP_AI_PROVIDER;
	}

	return $settings;
}

/**
 * Whether a provider and key are configured.
 *
 * @return bool
 */
function unapp_ai_ready() {
	$settings = unapp_ai_settings();

	return '' !== $settings['key'] && isset( unapp_ai_providers()[ $settings['provider'] ] );
}

/**
 * Pull the editable text out of block markup.
 *
 * Only the text nodes of headings, paragraphs, list items and buttons are
 * collected — never the markup around them — so the model cannot damage a
 * layout it never sees.
 *
 * @param string $markup Block markup.
 * @return array List of strings in document order.
 */
function unapp_ai_extract_text( $markup ) {
	$strings = array();
	// Older Unapp versions do not have the shared text walker yet.
	if ( ! function_exists( 'unapp_map_html_text' ) ) {
		preg_match_all( '#<(h[1-6]|p|li|a)(\s[^>]*)?>(.*?)</\\1>#s', $markup, $matches, PREG_SET_ORDER );
		foreach ( $matches as $match ) {
			$text = trim( wp_strip_all_tags( $match[3] ) );
			if ( mb_strlen( $text ) >= 3 && ! is_numeric( $text ) ) {
				$strings[] = $text;
			}
		}
		return array_values( array_unique( $strings ) );
	}
	unapp_map_html_text(
		$markup,
		static function ( $text ) use ( &$strings ) {
			$trimmed = trim( $text );
			if ( mb_strlen( $trimmed ) >= 3 && ! is_numeric( $trimmed ) ) {
				$strings[] = $trimmed;
			}
			return $text;
		}
	);

	return array_values( array_unique( $strings ) );
}

/**
 * Ask the configured provider to rewrite a list of strings.
 *
 * @param string[] $strings     The text currently on the page.
 * @param string   $description What the site's business actually is.
 * @return array|WP_Error Map of original => replacement.
 */
function unapp_ai_rewrite( $strings, $description ) {
	if ( ! unapp_ai_ready() ) {
		return new WP_Error( 'unapp_ai_not_configured', __( 'Add a provider and an API key first.', 'unapp-library' ) );
	}

	$settings = unapp_ai_settings();
	$provider = unapp_ai_providers()[ $settings['provider'] ];
	$model    = $settings['model'] ? $settings['model'] : $provider['model'];

	$prompt = sprintf(
		"You are rewriting the copy on a website template so it describes a real business.\n\n" .
		"The business: %s\n\n" .
		"Below is a JSON array of the strings currently on the page, in order. Return a JSON " .
		"array of exactly the same length, where each item is a replacement for the string at " .
		"the same index.\n\nRules:\n" .
		"- Keep each replacement close to the original length. These sit in a fixed layout.\n" .
		"- Inline formatting splits sentences into adjacent fragments. Keep fragments short and preserve their meaning; do not expand each fragment into a full sentence.\n" .
		"- Keep the register: a heading stays a heading, a button label stays two or three words.\n" .
		"- Keep any number that is structural (prices, times, counts) unless the business " .
		"description gives you a real one to use.\n" .
		"- Write plainly and specifically. No marketing filler, no exclamation marks.\n" .
		"- If a string should not change, repeat it unchanged.\n" .
		"- Return the JSON array and nothing else.\n\nStrings:\n%s",
		$description,
		wp_json_encode( $strings )
	);

	$body     = array();
	$headers  = array( 'Content-Type' => 'application/json' );
	$endpoint = $provider['endpoint'];

	switch ( $settings['provider'] ) {
		case 'anthropic':
			$headers['x-api-key']         = $settings['key'];
			$headers['anthropic-version'] = '2023-06-01';
			$body                         = array(
				'model'      => $model,
				'max_tokens' => 4096,
				'messages'   => array( array( 'role' => 'user', 'content' => $prompt ) ),
			);
			break;

		case 'openai':
			$headers['Authorization'] = 'Bearer ' . $settings['key'];
			$body                     = array(
				'model'    => $model,
				'messages' => array( array( 'role' => 'user', 'content' => $prompt ) ),
			);
			break;

		case 'google':
			$endpoint                  = str_replace( '{model}', rawurlencode( $model ), $endpoint );
			// In a header, not the query string, so the key stays out of server
			// and proxy logs that record URLs.
			$headers['x-goog-api-key'] = $settings['key'];
			$body                      = array(
				'contents' => array( array( 'parts' => array( array( 'text' => $prompt ) ) ) ),
			);
			break;
	}

	$response = wp_remote_post(
		$endpoint,
		array(
			'headers' => $headers,
			'body'    => wp_json_encode( $body ),
			'timeout' => 90,
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( 200 !== $code ) {
		$message = '';
		foreach ( array( array( 'error', 'message' ), array( 'error', 0, 'message' ) ) as $path ) {
			$found = $data;
			foreach ( $path as $key ) {
				$found = is_array( $found ) && isset( $found[ $key ] ) ? $found[ $key ] : null;
			}
			if ( is_string( $found ) ) {
				$message = $found;
				break;
			}
		}

		return new WP_Error(
			'unapp_ai_http',
			sprintf(
				/* translators: 1: provider name, 2: HTTP status, 3: error message. */
				__( '%1$s returned %2$d: %3$s', 'unapp-library' ),
				$provider['label'],
				$code,
				$message ? $message : __( 'no message', 'unapp-library' )
			)
		);
	}

	$text = unapp_ai_response_text( $settings['provider'], $data );

	if ( '' === $text ) {
		return new WP_Error( 'unapp_ai_empty', __( 'The provider returned nothing usable.', 'unapp-library' ) );
	}

	// Models like to wrap JSON in a code fence however firmly they are asked not to.
	$text = trim( preg_replace( '/^```(?:json)?|```$/m', '', $text ) );
	$out  = json_decode( $text, true );

	if ( ! is_array( $out ) || count( $out ) !== count( $strings )
		|| array_keys( $out ) !== range( 0, count( $strings ) - 1 )
		|| count( array_filter( $out, static function ( $item ) { return is_string( $item ) && '' !== trim( $item ); } ) ) !== count( $strings ) ) {
		return new WP_Error(
			'unapp_ai_shape',
			__( 'The reply did not line up with the page. Nothing was changed — try again.', 'unapp-library' )
		);
	}

	$map = array();

	foreach ( $strings as $i => $original ) {
		if ( is_string( $out[ $i ] ) && '' !== trim( $out[ $i ] ) ) {
			$map[ $original ] = trim( $out[ $i ] );
		}
	}

	return $map;
}

/**
 * Dig the assistant's text out of whichever shape the provider returned.
 *
 * @param string $provider Provider key.
 * @param array  $data     Decoded response.
 * @return string
 */
function unapp_ai_response_text( $provider, $data ) {
	switch ( $provider ) {
		case 'anthropic':
			return isset( $data['content'][0]['text'] ) ? $data['content'][0]['text'] : '';
		case 'openai':
			return isset( $data['choices'][0]['message']['content'] ) ? $data['choices'][0]['message']['content'] : '';
		case 'google':
			return isset( $data['candidates'][0]['content']['parts'][0]['text'] )
				? $data['candidates'][0]['content']['parts'][0]['text']
				: '';
	}

	return '';
}

/**
 * Swap the rewritten strings into a page's markup.
 *
 * Replacement is done on the text nodes the extractor found, longest first, so
 * a short string that is a substring of a longer one cannot corrupt it.
 *
 * @param string $markup Block markup.
 * @param array  $map    Original => replacement.
 * @return string
 */
function unapp_ai_apply( $markup, $map, &$changed = 0, &$used = array() ) {
	$changed = 0;
	$used = array();
	if ( ! function_exists( 'unapp_map_html_text' ) ) {
		return preg_replace_callback( '/>([^<]+)</', static function ( $match ) use ( $map, &$changed, &$used ) {
			$text = $match[1];
			if ( isset( $map[ $text ] ) && $map[ $text ] !== $text ) {
				++$changed;
				$used[ $text ] = $map[ $text ];
				return '>' . esc_html( $map[ $text ] ) . '<';
			}
			return $match[0];
		}, $markup );
	}
	return unapp_map_html_text(
		$markup,
		static function ( $text ) use ( $map, &$changed, &$used ) {
			$key = trim( $text );
			if ( ! isset( $map[ $key ] ) || $map[ $key ] === $key ) {
				return $text;
			}
			++$changed;
			$used[ $key ] = $map[ $key ];
			$start = strlen( $text ) - strlen( ltrim( $text ) );
			return substr( $text, 0, $start ) . $map[ $key ] . substr( $text, $start + strlen( $key ) );
		}
	);
}

/**
 * Prepare a reviewable rewrite without modifying any page.
 *
 * @param int[]  $page_ids    Page IDs.
 * @param string $description The business.
 * @return array|WP_Error Proposal with source hashes, or a provider error.
 */
function unapp_ai_prepare_rewrite( $page_ids, $description ) {
	$strings = array();
	$pages   = array();

	foreach ( $page_ids as $id ) {
		$post = get_post( $id );
		if ( ! $post || ! current_user_can( 'edit_post', $id ) ) {
			continue;
		}
		$pages[ $id ] = $post->post_content;
		$strings      = array_merge( $strings, unapp_ai_extract_text( $post->post_content ) );
	}

	$strings = array_values( array_unique( $strings ) );

	if ( ! $strings ) {
		return new WP_Error( 'unapp_ai_nothing', __( 'There was no text to rewrite.', 'unapp-library' ) );
	}

	$map = unapp_ai_rewrite( $strings, $description );

	if ( is_wp_error( $map ) ) {
		return $map;
	}

	$proposal = array( 'id' => wp_generate_uuid4(), 'user' => get_current_user_id(), 'pages' => array(), 'map' => array() );
	foreach ( $pages as $id => $markup ) {
		$updated = unapp_ai_apply( $markup, $map, $replacements, $used );
		if ( $updated !== $markup ) {
			$proposal['map'] += $used;
			$proposal['pages'][ $id ] = array( 'source' => hash( 'sha256', $markup ), 'updated' => $updated, 'changes' => $replacements );
		}
	}
	return $proposal;
}

/**
 * Save a reviewed proposal only while every source page is still unchanged.
 *
 * @param array $proposal Locally stored proposal belonging to the current user.
 * @return array|WP_Error Number of saved pages and text replacements.
 */
function unapp_ai_apply_proposal( $proposal ) {
	if ( (int) ( $proposal['user'] ?? 0 ) !== get_current_user_id() ) {
		return new WP_Error( 'unapp_ai_owner', __( 'Generate a rewrite preview with your own account first.', 'unapp-library' ) );
	}
	foreach ( $proposal['pages'] as $id => $change ) {
		$post = get_post( $id );
		if ( ! $post || ! current_user_can( 'edit_post', $id ) || hash( 'sha256', $post->post_content ) !== $change['source'] ) {
			return new WP_Error( 'unapp_ai_stale', __( 'A page changed after this preview was generated. Nothing was applied. Generate a new preview to keep those edits.', 'unapp-library' ) );
		}
	}
	$changed = 0;
	$strings_changed = 0;
	foreach ( $proposal['pages'] as $id => $change ) {
		if ( function_exists( 'unapp_keep_revision' ) ) {
			unapp_keep_revision( $id );
		} else {
			wp_save_post_revision( $id );
		}
		$saved = wp_update_post( array( 'ID' => $id, 'post_content' => wp_slash( $change['updated'] ) ), true );
		if ( is_wp_error( $saved ) || ! $saved ) {
			return new WP_Error( 'unapp_ai_save', __( 'A rewritten page could not be saved. Check your Pages list before trying again; earlier pages may have been updated.', 'unapp-library' ) );
		}
		++$changed;
		$strings_changed += $change['changes'];
	}
	return array( 'pages' => $changed, 'strings' => $strings_changed );
}

/**
 * Compatibility helper for integrations intentionally applying in one step.
 *
 * @param int[]  $page_ids Pages to rewrite.
 * @param string $description Business description.
 * @return array|WP_Error Saved counts or provider/save error.
 */
function unapp_ai_rewrite_pages( $page_ids, $description ) {
	$proposal = unapp_ai_prepare_rewrite( $page_ids, $description );
	return is_wp_error( $proposal ) ? $proposal : unapp_ai_apply_proposal( $proposal );
}
