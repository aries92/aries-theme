<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package starter
 */

get_header(); ?>
<div class="container-fluid blog-archive">
	<div class="row">
		<div class="col-md-9 col-lg-7 col-lg-offset-1 blog-append">
		<?php if ( have_posts() ) : ?>
			<div class="infblock">
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
			<?php endwhile; ?>
				<span class="inflink" style="display: none;"><?php echo get_next_posts_link( ); ?></span>
			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>
		</div>
		<div class="col-md-3 col-lg-2 col-lg-offset-1">
			<div class="recent-posts">
				<h3>Recent Posts</h3>
				<?php $args = array(
					'numberposts' => 3,
					'offset' => 0,
					'category' => 0,
					'orderby' => 'post_date',
					'order' => 'DESC',
					'post_type' => 'post',
					'post_status' => 'publish',
					'suppress_filters' => true );

				$recent_posts = wp_get_recent_posts( $args, OBJECT );
				?>
				<?php foreach($recent_posts as $post): setup_postdata($post); ?>
					<div class="recent-post-item">
						<span class="latest-news-date"><?php echo get_the_date('jS F Y', $post->ID); ?></span>
						<h4><a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID);?></a></h4>
					</div>
				<?php endforeach; wp_reset_postdata();?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
