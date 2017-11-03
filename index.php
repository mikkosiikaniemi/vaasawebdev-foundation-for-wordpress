<?php get_header(); ?>

<main class="row">

<?php if ( have_posts() ) : ?>

	<div class="small-12 medium-8 large-9 columns">

	<?php while ( have_posts() ) : the_post(); ?>

	<?php the_title( '<h1>', '</h1>' ); ?>
	<?php the_content(); ?>

	<?php endwhile; ?>

	</div>

<?php endif; ?>

	<div class="small-12 medium-4 large-3 columns">

	<?php dynamic_sidebar( 'widgets' ); ?>

	</div>
</main>

<?php get_footer(); ?>
