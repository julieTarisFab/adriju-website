<?php
/**
 * Actions required
 */
wp_enqueue_style( 'plugin-install' );
wp_enqueue_script( 'plugin-install' );
wp_enqueue_script( 'updates' );
?>

<div class="feature-section action-required demo-import-boxed" id="plugin-filter">

	<?php
	global $brilliance_required_actions, $brilliance_recommended_plugins;
	if ( ! empty( $brilliance_required_actions ) ):
		/* brilliance_show_required_actions is an array of true/false for each required action that was dismissed */
		$nr_actions_required = 0;
		$nr_action_dismissed = 0;
		$brilliance_show_required_actions = get_option( "brilliance_show_required_actions" );
		foreach ( $brilliance_required_actions as $brilliance_required_action_key => $brilliance_required_action_value ):
			$hidden = false;
			if ( @$brilliance_show_required_actions[ $brilliance_required_action_value['id'] ] === false ) {
				$hidden = true;
			}
			if ( @$brilliance_required_action_value['check'] ) {
				continue;
			}
			$nr_actions_required ++;
			if ( $hidden ) {
				$nr_action_dismissed ++;
			}
			
			?>
			<div class="brilliance-action-required-box">
				<?php if ( ! $hidden ): ?>
					<span data-action="dismiss" class="dashicons dashicons-visibility brilliance-required-action-button"
					      id="<?php echo esc_attr( $brilliance_required_action_value['id'] ); ?>"></span>
				<?php else: ?>
					<span data-action="add" class="dashicons dashicons-hidden brilliance-required-action-button"
					      id="<?php echo esc_attr( $brilliance_required_action_value['id'] ); ?>"></span>
				<?php endif; ?>
				<h3><?php if ( ! empty( $brilliance_required_action_value['title'] ) ): echo $brilliance_required_action_value['title']; endif; ?></h3>
				<p>
					<?php if ( ! empty( $brilliance_required_action_value['description'] ) ): echo $brilliance_required_action_value['description']; endif; ?>
					<?php if ( ! empty( $brilliance_required_action_value['help'] ) ): echo '<br/>' . $brilliance_required_action_value['help']; endif; ?>
				</p>
				<?php
				if ( ! empty( $brilliance_required_action_value['plugin_slug'] ) ) {
					$active = $this->check_active( $brilliance_required_action_value['plugin_slug'] );
					$url    = $this->create_action_link( $active['needs'], $brilliance_required_action_value['plugin_slug'] );
					$label  = '';
					switch ( $active['needs'] ) {
						case 'install':
							$class = 'install-now button';
							$label = __( 'Install', 'brilliance' );
							break;
						case 'activate':
							$class = 'activate-now button button-primary';
							$label = __( 'Activate', 'brilliance' );
							break;
						case 'deactivate':
							$class = 'deactivate-now button';
							$label = __( 'Deactivate', 'brilliance' );
							break;
					}
					?>
					<p class="plugin-card-<?php echo esc_attr( $brilliance_required_action_value['plugin_slug'] ) ?> action_button <?php echo ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ?>">
						<a data-slug="<?php echo esc_attr( $brilliance_required_action_value['plugin_slug'] ) ?>"
						   class="<?php echo $class; ?>"
						   href="<?php echo esc_url( $url ) ?>"> <?php echo $label ?> </a>
					</p>
					<?php
				};
				?>
			</div>
			<?php
		endforeach;
	endif;
	$nr_recommended_plugins = 0;
	if ( $nr_actions_required == 0 || $nr_actions_required == $nr_action_dismissed ):

		$brilliance_show_recommended_plugins = get_option( "brilliance_show_recommended_plugins" );
		foreach ( $brilliance_recommended_plugins as $slug => $plugin_opt ) {
			
			if ( !$plugin_opt['recommended'] ) {
				continue;
			}

			if ( Brilliance_Notify_System::has_plugin( $slug ) ) {
				continue;
			}
			if ( $nr_recommended_plugins == 0 ) {
				echo '<h3 class="hooray">' . __( 'Hooray! There are no required actions for you right now. But you can make your theme more powerful with next actions: ', 'brilliance' ) . '</h3>';
			}

			$nr_recommended_plugins ++;
			echo '<div class="brilliance-action-required-box">';

			if ( isset($brilliance_show_recommended_plugins[$slug]) && $brilliance_show_recommended_plugins[$slug] ): ?>
				<span data-action="add" class="dashicons dashicons-hidden brilliance-recommended-plugin-button"
				      id="<?php echo esc_attr( $slug ); ?>"></span>
			<?php else: ?>
				<span data-action="dismiss" class="dashicons dashicons-visibility brilliance-recommended-plugin-button"
				      id="<?php echo esc_attr( $slug ); ?>"></span>
			<?php endif;

			$active = $this->check_active( $slug );
			$url    = $this->create_action_link( $active['needs'], $slug );
			$info   = $this->call_plugin_api( $slug );
			$label  = '';
			$class = '';
			switch ( $active['needs'] ) {
				case 'install':
					$class = 'install-now button';
					$label = __( 'Install', 'brilliance' );
					break;
				case 'activate':
					$class = 'activate-now button button-primary';
					$label = __( 'Activate', 'brilliance' );
					break;
				case 'deactivate':
					$class = 'deactivate-now button';
					$label = __( 'Deactivate', 'brilliance' );
					break;
			}
			?>
			<h3><?php echo $label .': '.$info->name ?></h3>
			<p>
				<?php echo $info->short_description ?>
			</p>
			<p class="plugin-card-<?php echo esc_attr( $slug ) ?> action_button <?php echo ( $active['needs'] !== 'install' && $active['status'] ) ? 'active' : '' ?>">
				<a data-slug="<?php echo esc_attr( $slug ) ?>"
				   class="<?php echo $class; ?>"
				   href="<?php echo esc_url( $url ) ?>"> <?php echo $label ?> </a>
			</p>
			<?php

			echo '</div>';

		}

	endif;

	if ( $nr_recommended_plugins == 0 && $nr_actions_required == 0 ) {
		echo '<span class="hooray">' . __( 'Hooray! There are no required actions for you right now.', 'brilliance' ) . '</span>';
	}

	?>

</div>