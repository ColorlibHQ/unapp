<?php
/** Standalone regression tests: php .dev/tests/content.php */
define( 'ABSPATH', __DIR__ );
function esc_html( $text ) { return htmlspecialchars( $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false ); }
function wp_strip_all_tags( $text ) { return strip_tags( $text ); }
require dirname( __DIR__, 2 ) . '/inc/content.php';
require dirname( __DIR__, 2 ) . '/plugin/unapp-library/ai.php';

$checks = 0;
$expect = static function ( $actual, $expected, $label ) use ( &$checks ) {
	++$checks;
	if ( $actual !== $expected ) {
		throw new RuntimeException( $label . ': ' . var_export( $actual, true ) );
	}
};
$markup = '<!-- wp:paragraph {"className":"Grow"} --><p data-label="Grow > business"> Grow <strong>your</strong> business &amp; team. </p><!-- /wp:paragraph -->';
$expect( unapp_ai_extract_text( $markup ), array( 'Grow', 'your', 'business & team.' ), 'formatted copy is extracted as editable fragments' );
$updated = unapp_ai_apply( $markup, array( 'Grow' => 'Build', 'your' => 'our', 'business & team.' => 'studio & community.' ), $count );
$expect( $updated, '<!-- wp:paragraph {"className":"Grow"} --><p data-label="Grow > business"> Build <strong>our</strong> studio &amp; community. </p><!-- /wp:paragraph -->', 'formatting, comments, attributes and surrounding spaces survive' );
$expect( $count, 3, 'actual replacements are counted' );
$expect( unapp_ai_apply( '<p>One</p><p>Two</p>', array( 'One' => 'Two', 'Two' => 'Three' ) ), '<p>Two</p><p>Three</p>', 'replacements do not cascade' );
$expect( unapp_ai_apply( '<p><a href="https://example.com/One" title="One">One</a> <code>One</code></p>', array( 'One' => '<script>alert(1)</script>' ) ), '<p><a href="https://example.com/One" title="One">&lt;script&gt;alert(1)&lt;/script&gt;</a> <code>One</code></p>', 'replacement is escaped and does not modify code or URLs' );
$protected = '<script>"<p>One</p>"</script><style>p:after{content:"One"}</style><pre><p>One</p></pre><textarea><p>One</p></textarea><svg><a>One</a></svg>';
$expect( unapp_ai_apply( $protected, array( 'One' => 'Changed' ) ), $protected, 'non-copy contexts are protected' );
$expect( unapp_ai_extract_text( '<p>99</p><p>Hi</p><p>Useful text</p>' ), array( 'Useful text' ), 'numbers and tiny fragments stay out of AI requests' );
$expect( unapp_ai_apply( $markup, array() ), $markup, 'no-op is byte-identical' );
$expect( unapp_ai_apply( '<p>One<br>Two <em>Three</em></p><p>Four</p>', array( 'One' => 'First', 'Two' => 'Second', 'Three' => 'Third', 'Four' => 'Fourth' ) ), '<p>First<br>Second <em>Third</em></p><p>Fourth</p>', 'nested and void elements preserve text boundaries' );
echo "Content regression checks passed: $checks\n";
