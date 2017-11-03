<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Nunito:400,700" rel="stylesheet">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


	<nav role="navigation" class="top-bar">
		<div class="top-bar-left">
			<ul class="menu">
				<li>
					<strong><a class="site-title" title="<?php bloginfo( 'name' ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></strong>
				</li>
			</ul>
		</div>
		<div class="top-bar-right">
			<?php wp_nav_menu( array (
				'container'      => false,
				'menu_class'     => 'dropdown menu',
				'items_wrap'     => '<ul id="%1$s" class="%2$s" data-dropdown-menu data-close-on-click-inside="false">%3$s</ul>',
				'theme_location' => 'primary',
				'depth'          => 3,
				'fallback_cb'    => false,
				'walker'         => new Mikrogramma_Top_Bar_Walker(),
			) ); ?>
		</div>
	</nav>

	<header class="row expanded align-middle" style="background-image: url('https://picsum.photos/1200/500?image=1063');">
		<div class="column text-center">
			<h1><?php bloginfo( 'name' ); ?></h1>
		</div>
	</header>
