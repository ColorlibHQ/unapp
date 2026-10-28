<?php
/**
 * Edit visible text without reserializing block HTML or changing attributes.
 *
 * @package Unapp
 */

defined( 'ABSPATH' ) || exit;

/**
 * Visit text in headings, paragraphs, list items and links, including inline tags.
 *
 * Keep tags and block comments byte for byte. Quoted attributes and non-copy
 * contexts are left alone. Supports WordPress 6.6, before the HTML API setter.
 *
 * @param string   $markup   Block HTML.
 * @param callable $callback Receives decoded text; returns decoded replacement.
 * @return string
 */
function unapp_map_html_text( $markup, $callback ) {
	$tokens = preg_split( '~(<!--[\s\S]*?-->|<!\[CDATA\[[\s\S]*?\]\]>|<(?:"[^"]*"|\'[^\']*\'|[^\'">])*>)~', $markup, -1, PREG_SPLIT_DELIM_CAPTURE );
	$stack  = array();
	$raw    = '';
	$copy   = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'li', 'a' );
	$skip   = array( 'script', 'style', 'code', 'pre', 'textarea', 'svg', 'math', 'noscript', 'template' );
	$void   = array( 'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr' );

	foreach ( $tokens as $i => $token ) {
		if ( 1 === $i % 2 ) {
			if ( ! preg_match( '~^<\s*(/?)\s*([a-z][a-z0-9:-]*)\b~i', $token, $match ) ) {
				continue;
			}
			$tag = strtolower( $match[2] );
			if ( $raw && ( $tag !== $raw || ! $match[1] ) ) {
				continue;
			}
			if ( $match[1] ) {
				$position = array_search( $tag, array_reverse( $stack, true ), true );
				if ( false !== $position ) {
					$stack = array_slice( $stack, 0, $position );
				}
				$raw = '';
			} elseif ( ! in_array( $tag, $void, true ) && ! preg_match( '~/\s*>$~', $token ) ) {
				$stack[] = $tag;
				if ( in_array( $tag, array( 'script', 'style', 'textarea' ), true ) ) {
					$raw = $tag;
				}
			}
			continue;
		}
		if ( ! array_intersect( $copy, $stack ) || array_intersect( $skip, $stack ) ) {
			continue;
		}
		$decoded = html_entity_decode( $token, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$updated = call_user_func( $callback, $decoded );
		if ( $updated !== $decoded ) {
			$tokens[ $i ] = esc_html( $updated );
		}
	}

	return implode( '', $tokens );
}
