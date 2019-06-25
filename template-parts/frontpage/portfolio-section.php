<?php
$frontpage = Epsilon_Page_Generator::get_instance( 'unapp_frontpage_sections_' . get_the_ID(), get_the_ID() );
$fields = $frontpage->sections[ $section_id ];

$grouping = array( 'values'   => $fields['grouping'], 'group_by' => 'title');
$fields['portfolios'] = $frontpage->get_repeater_field( 'unapp_portfolios', array(), $grouping );

$attr_helper = new Epsilon_Section_Attr_Helper( $fields, 'portfolio', Unapp_Repeatable_Sections::get_instance() );
$parent_attr = array(
	'class' => array( 'ewf-section', 'colorlib-work' ),
    'style' => array( 'background-image', 'background-position', 'background-size', 'background-repeat', 'background-parallax' ),
);

$span      = 12 / absint( $fields['portfolio_column_group'] );

$id = '' != $fields['section_id'] ? $fields['section_id'] : Unapp_Helper::generate_section_id( 'portfolio' );

?>

<div class="colorlib-section" data-customizer-section-id="unapp_repeatable_section" data-section="<?php echo esc_attr( $section_id ); ?>">
    <div id="<?php echo $id ?>" <?php $attr_helper->generate_attributes( $parent_attr ); ?>>

        <?php if ( '' != $fields['portfolio_background_video'] ): ?>
            <a class="player" data-property="{videoURL:'<?php echo esc_url( $fields[ 'portfolio_background_video' ] ); ?>',containment:'#<?php echo $id ?>', showControls:false, autoPlay:true, loop:true, mute:true, startAt:0, opacity:1, quality:'default'}"></a>
        <?php endif ?>

        <?php $attr_helper->generate_color_overlay(); ?>

        <div class="<?php echo esc_attr( Unapp_Helper::container_class( 'portfolio', $fields ) ); ?>">

            <div class="row">
				<?php echo wp_kses( unapp_Helper::generate_pencil( 'unapp_Repeatable_Sections', 'portfolio' ), Epsilon_Helper::allowed_kses_pencil() ); ?>
                <div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
                    <h2><?php echo wp_kses_post( $fields[ 'title' ] ); ?></h2>
                    <p><?php echo wp_kses_post( $fields[ 'subtitle' ] ); ?></p>
                </div>
            </div>

            <div class="row portfolio_bottom">
				<?php
				foreach ( $fields['portfolios'] as $key => $portfolio ){

                    switch ( $fields['layout'] ) {
                        case 'grid':
                            ?>
                            <div class="col-md-<?php echo absint( $span ); ?> animate-box">
                                <div class="work-grid">
                                    <a href="<?php echo esc_url( $portfolio['url'] ) ?>" class="work-img" style="background-image: url(<?php echo esc_url( $portfolio['featured_image'] ) ?>)">
                                        <span class="icon"><i class="icon-plus2"></i></span>
                                    </a>
                                    <div class="desc">
                                        <h2><a href="<?php echo esc_url( $portfolio['url'] ) ?>"><?php echo esc_html( $portfolio['title'] ) ?></a></h2>
                                        <?php echo wp_kses_post( wpautop( $portfolio['content'] ) ) ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                        case 'minimal-grid':
                            ?>
                            <div class="col-md-<?php echo absint( $span ); ?> animate-box">
                                <div class="work-grid">
                                    <a href="<?php echo esc_url( $portfolio['url'] ) ?>" class="work-img" style="background-image: url(<?php echo esc_url( $portfolio['featured_image'] ) ?>)">
                                        <span class="icon"><i class="icon-plus2"></i></span>
                                    </a>
                                </div>
                            </div>
                            <?php
                            break;
                        default:
                            if( $portfolio['image_align'] == 'right' ){
                                $pushClass = 'col-md-push-12';
                                $pullClass = 'col-md-pull-12';
                            }else{
                                $pushClass = '';
                                $pullClass = '';
                            }

                            ?>
                            <div class="col-md-12">
                                <div class="work-flex">
                                    <div class="half animate-box">
                                        <div class="row no-gutters">
                                            <div class="col-md-12 no-gutters <?php echo esc_attr( $pushClass ) ?>">
                                                <a href="<?php echo esc_url( $portfolio['url'] ) ?>" class="work-img" style="background-image: url(<?php echo esc_url( $portfolio['featured_image'] ) ?>)"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="half animate-box">
                                        <div class="row no-gutters">
                                            <div class="col-md-12 no-gutters <?php echo esc_attr( $pullClass ) ?>">
                                                <div class="display-t desc">
                                                    <div class="display-tc">
                                                        <h2><a href="<?php echo esc_url( $portfolio['url'] ) ?>"><?php echo esc_html( $portfolio['title'] ) ?></a></h2>
                                                        <?php echo wp_kses_post( wpautop( $portfolio['content'] ) ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                    }

				}
                ?>
            </div>
        </div>
    </div>
</div>