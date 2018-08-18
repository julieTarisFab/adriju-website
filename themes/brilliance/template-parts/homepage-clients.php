<?php $query = new WP_Query('post_type=cpo_client&posts_per_page=-1&order=ASC&orderby=menu_order'); ?>
<?php if($query->posts): $feature_count = 0; ?>
<div id="clients" class="clients secondary-color-bg dark">
	<div class="container">
		<?php if($query->post_count < 6) $columns = $query->post_count; else $columns = 6; ?>
		<?php cpotheme_grid($query->posts, 'element', 'client', $columns, array('class' => 'column-narrow')); ?>
	</div>
</div>
<?php endif; ?>
