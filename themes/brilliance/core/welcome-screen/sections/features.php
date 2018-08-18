<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Features
 */

$features = array(
	'slider-options'    => array(
		'label'   => esc_html__( 'Improved Slider Options', 'brilliance' ),
		'sub'     => esc_html__( 'Add more slides, control the appearance & position of slides.', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'woocommerce'       => array(
		'label'   => esc_html__( 'WooCommerce Support', 'brilliance' ),
		'sub'     => esc_html__( 'Create a WooCommerce powered shop. Supports WooCommerce v3.x and upwards', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'reorder-sections'  => array(
		'label'   => esc_html__( 'Reorder Homepage Sections', 'brilliance' ),
		'sub'     => esc_html__( 'Re-order your site\'s front-page sections in any way you want.', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'custom-colors'     => array(
		'label'   => esc_html__( 'Custom Color Schemes & Color Controls', 'brilliance' ),
		'sub'     => esc_html__( 'Easily change your site\'s color schemes.', 'brilliance' ),
		'cpo'     => '<span class="dashicons dashicons-no-alt"></span>',
		'cpo-pro' => '<span class="dashicons dashicons-yes"></span></i>'
	),
	'typography'        => array(
		'label'   => esc_html__( 'Custom Typography Controls', 'brilliance' ),
		'sub'     => esc_html__( 'Want a different font family? No problem. Just an upgrade away.', 'brilliance' ),
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
		            <p><?php echo $feature['sub']; ?></p>
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
            <td colspan="2" class="text-right"><a href="//www.cpothemes.com/theme/brilliance?utm_source=worg&utm_medium=theme&utm_campaign=upsell" target="_blank"
                               class="button button-primary button-hero"><span class="dashicons dashicons-cart"></span><?php _e( 'Get The Pro Version Now!', 'brilliance' ) ?></a></td>
        </tr>
        </tbody>
    </table>
</div>