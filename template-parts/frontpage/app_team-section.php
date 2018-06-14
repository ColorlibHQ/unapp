<?php
$frontpage = Epsilon_Page_Generator::get_instance_page( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$grouping = array( 'values'   => $fields['team_grouping'], 'group_by' => 'team_title');
$fields['app_team'] = $frontpage->get_repeater_field( $fields['team_repeater_field'], array(), $grouping );

?>
<div class="container" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div class="row">
	    <?php echo wp_kses( unapp_Helper::generate_pencil( 'unapp_Repeatable_Sections', 'app_team' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
        <div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
            <h2><?php echo wp_kses_post( $fields[ 'team_title' ] ); ?></h2>
            <p><?php echo wp_kses_post( $fields[ 'team_subtitle' ] ); ?></p>
        </div>
    </div>
    <div class="row team_bottom">
        <?php
        foreach ( $fields['app_team'] as $key => $team ){
        ?>
        <div class="col-md-3 text-center animate-box">
            <div class="staff-entry">
                <a href="#">
                    <?php if( !empty( $team[ 'member_image' ] ) ) { ?>
                    <img src="<?php echo esc_url( $team[ 'member_image' ] ); ?>" alt="<?php echo wp_kses_post( $team[ 'member_title' ] ); ?>" class="staff-img">
                    <?php
                    }
                    else{
                        ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/woman-b5.png" alt="<?php echo wp_kses_post( $team[ 'member_title' ] ); ?> class="staff-img">
                    <?php
                    }
                    ?>

                </a>
                <div class="desc">
                    <h3><?php echo wp_kses_post( $team[ 'member_title' ] ); ?></h3>
                    <?php if( !empty( $team[ 'member_designation' ] ) ) { ?>
                    <span><?php echo wp_kses_post( $team[ 'member_designation' ] ); ?></span>
                    <?php } ?>
                    <?php if( !empty( $team[ 'member_text' ] ) ) { ?>
                    <p><?php echo wp_kses_post( $team[ 'member_text' ] ); ?></p>
                    <?php } ?>
                    <ul class="colorlib-social-icons">
		                <?php if( !empty( $team[ 'member_social_facebook' ] ) ) { ?>
                            <li><a href="<?php echo esc_url( $team[ 'member_social_facebook' ] ); ?>"><i class="icon-facebook"></i></a></li>
		                <?php } ?>
		                <?php if( !empty( $team[ 'member_social_twitter' ] ) ) { ?>
                            <li><a href="<?php echo esc_url( $team[ 'member_social_twitter' ] ); ?>"><i class="icon-twitter"></i></a></li>
		                <?php } ?>
		                <?php if( !empty( $team[ 'member_social_linkedin' ] ) ) { ?>
                            <li><a href="<?php echo esc_url( $team[ 'member_social_linkedin' ] ); ?>"><i class="icon-linkedin"></i></a></li>
		                <?php } ?>
		                <?php if( !empty( $team[ 'member_social_pinterest' ] ) ) { ?>
                            <li><a href="<?php echo esc_url( $team[ 'member_social_pinterest' ] ); ?>"><i class="icon-pinterest"></i></a></li>
		                <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>