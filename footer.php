<?php if( is_active_sidebar( 'footer-1' ) ) {?>
<footer id="colorlib-footer">
	<div class="container">
		<div class="row row-pb-md">
			<?php dynamic_sidebar( 'footer-1' ); ?>
		</div>
	</div>
</footer>
<?php } ?>

<?php if( get_theme_mod( 'unapp_enable_footer_copyright', 'true' ) ) { ?>
    <div class="copy">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>
						<?php
					echo wp_kses_post( get_theme_mod( 'unapp_copyright_contents', sprintf( esc_html__( 'Unapp Themes &copy; %s. All rights reserved.', 'unapp' ), date( 'Y' ) ) ) );
					?>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

</div><!-- end #page -->

<?php if( get_theme_mod( 'unapp_enable_go_top', true ) ) { ?>
<div class="gototop js-top">
	<a href="#" class="js-gotop"><i class="icon-arrow-up2"></i></a>
</div>
<?php } ?>

<?php wp_footer(); ?>
</body>
</html>
