<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Features
 */

$features = array(
	'slider-options' => array(
		'label'   	=> __( 'Slider options', 'brilliance' ),
		'cpo'     	=> '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro'	=> '<span class="dashicons dashicons-yes"></span></i>'
	),
	'woocommerce' => array(
		'label'  	=> __( 'WooCommerce Integration', 'brilliance' ),
		'cpo'     	=> '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' 	=> '<span class="dashicons dashicons-yes"></span></i>'
	),
	'reorder-sections' => array(
		'label'       => __( 'Reorder Homepage Sections', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'custom-colors'    => array(
		'label'       => __( 'Custom Colors', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'typography'       => array(
		'label'       => __( 'Custom Typography', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'dedicated-support' => array(
		'label'       => __( 'Dedicated Support Team', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'security-updates' => array(
		'label'       => __( 'Security updates & feature releases', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
);
?>
<div class="featured-section features">
    <table class="free-pro-table">
        <thead>
        <tr>
            <th></th>
            <th><?php _e( 'Lite', 'brilliance' ) ?></th>
            <th><?php _e( 'PRO', 'brilliance' ) ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $features as $feature ): ?>
            <tr>
	            <td class="feature">
		            <h3><?php echo $feature['label']; ?></h3>
		            <?php if ( isset( $feature['sub'] ) ): ?>
		            	<p><?php echo $feature['sub']; ?></p>
		            <?php endif ?>
	            </td>
                <td class="cpo-feature">
					<?php echo $feature['cpo']; ?>
                </td>
                <td class="cpo-pro-feature">
					<?php echo $feature['cpo-pro']; ?>
                </td>
            </tr>
		<?php endforeach; ?>
        <tr>
            <td></td>
            <td colspan="2" class="text-right"><a href="//www.cpothemes.com/theme/brilliance?utm_source=brilliance&utm_medium=about-page&utm_campaign=upsell" target="_blank"
                               class="button button-primary button-hero"><span class="dashicons dashicons-cart"></span><?php _e( 'Get The Pro Version Now!', 'brilliance' ) ?></a></td>
        </tr>
        </tbody>
    </table>
</div>