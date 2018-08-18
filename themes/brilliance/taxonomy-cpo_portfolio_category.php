<?php get_header(); ?>

<?php get_template_part('template-parts/element', 'page-header'); ?>
	
<div id="main" class="main">
	<div class="container">
		<section id="content" class="content">
			<?php do_action('cpotheme_before_content'); ?>
			
			<?php $description = term_description(); ?>
			<?php if($description != ''): ?>
			<div class="page-content">
				<?php echo $description; ?>
			</div>
			<?php endif; ?>

			<?php $columns = 4; ?>
			<?php if(have_posts()): $feature_count = 0; ?>
			<div id="portfolio" class="portfolio">
				<?php while(have_posts()): the_post(); ?>
				<?php if($feature_count % $columns == 0 && $feature_count != 0) echo '<div class="col-divide"></div>'; ?>
				<?php $feature_count++; ?>
				<div class="column column-fit col<?php echo $columns; if($feature_count % $columns == 0 && $feature_count != 0) echo ' col-last'; ?>">
					<?php get_template_part('template-parts/element', 'portfolio'); ?>
				</div>
				<?php endwhile; ?>
				<div class='clear'></div>
			</div>
			<?php endif; ?>
			
			<?php do_action('cpotheme_after_content'); ?>
		</section>
		<?php get_sidebar(); ?>
		<div class='clear'></div>
	</div>
	
	<div class="container">
		<?php cpotheme_secondary_menu('cpo_portfolio_category', 'menu-portfolio'); ?>
	</div>
	
	
	<?php cpotheme_numbered_pagination(); ?>
	
</div>

<?php get_footer(); ?>