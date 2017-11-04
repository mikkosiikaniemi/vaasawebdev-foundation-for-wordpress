# vaasawebdev-foundation-for-wordpress
A simple WordPress theme with [Foundation for Sites](https://foundation.zurb.com/sites.html) dependencies.

This theme was built for demo purposes to present the possibilities to use Foundation as a basis for WordPress theme development. I presented the work at [VaasaWebDev meetup](https://www.meetup.com/vaasawebdev/events/243973604/) on 2<sup>nd</sup> of November 2017 ([slides here](https://docs.google.com/presentation/d/1wfIgKmFh-qP8dxd3n1cpLL8QekXifWcJYwPLLLCZymY/edit?usp=sharing)).

To make this theme work, you need to follow the instructions below.

## Prerequisites

1. A working local WordPress installation (I was using XAMPP running on Windows 10 64-bit in the demo)
2. Node.js installed
3. Git installed

## Installing Foundation for Sites

* Open command prompt.
* Create a new theme directory under WordPress installation’s `/wp-content/themes`
* Change to newly created directory
* Clone Foundation's template via command prompt using Git (this follows the guidelines for [Manually setting up ZURB Template](https://foundation.zurb.com/sites/docs/installation.html#manual-setup). The template comes with ready-made workflow tools utilizing e.g. webpack, Gulp, autoprefixer & BrowserSync):

```
git clone https://github.com/zurb/foundation-zurb-template .
```
* Install needed dependencies using Node.js:

```
npm install
```



* Note that Foundation can also be installed using Foundation’s own wizard-like CLI tool.

You can now test that everything works by starting a build process with `npm start` (which in this case is alias to `gulp`).

## Modifying Foundation files

You can now download this repository to your theme directory, overwriting Foundation's corresponding files. 

### Summary of changes to Foundation files

Here's a summary of changes to Foundation files I've made. These changes will be taken into use when you overwrite Foundation files with this repository.

`package.json`
* change project name
* add author info
* add hostname to configure BrowserSync for local site live reloading
* add devDependencies: `gulp-del`, `gulp-notify`, `gulp-rename`
* remove unnecessary dependencies

`gulpfile.babel.js`
* remove unnecessary imports
* import `gulp-del` to enable deleting compiled files before new build
* change CSS file output name and path for WordPress to `./style.css`
* prevent including jQuery in JavaScript output file to reduce the file size and prevent duplicate usage, as WordPress usually enqueues this anyway

`config.yml`
* change path for outputting built files from `/dist` to root of the theme folder

`/src/assets/js/app.js`
* disable including the whole Foundation JS (see next bullet)

`/src/assets/js/lib/foundation-explicit-pieces.js`
* selectively include necessary Foundation JS libraries for your project (default is all)

`/src/assets/scss/app.scss`
* comment out unnecessary Foundation SCSS modules (default is all)
* configure the grid templating as you see fit; floats vs. flexbox vs. “XY-grid” (not the CSS grid). I’ve mostly used flexbox-based grid for projects.
* add WordPress stylesheet headers
* start adding your own (S)CSS

You can now remove unnecessary Foundation module folders under `/src` for static pages, layouts, partials, styleguides etc.

After these changes, run `npm install` again to fetch the new dependencies. You should now be able to run `gulp` again to have the live site appearing.

This theme only contains a few basic files to make navigation menu, title, content and footer appear. Now go ahead and start the actual WordPress theme building by editing PHP files.

### WordPress theme stylesheet header

This code should be on the top of the `src/assets/scss/app.scss` file to make the theme selectable in WordPress' admin.

```css
/*! ----------------------------------------------------------------------------
Theme Name: VaasaWebDev Meetup Demo
Theme URI: https://vaasawebdev.fi
Author: Mikko Siikaniemi / Mikrogramma Design
Author URI: http://www.mikrogramma.fi
Description: Handcrafted WordPress theme for VaasaWebDev meetup demo.
Version: 1.0
Text Domain: mikrogramma
---------------------------------------------------------------------------- */
```

Note the exclamation mark after the opening `/*` → file minification will remove comments like this, unless suffixed with the exclamation mark. Removing this comment from style.css will render the theme inoperable.

### Demo CSS

These few lines of SCSS should go to `app.scss` as well to make the demo look more beautiful.

```scss
header {
	color: $white;
	padding: $global-padding * 7 0;
	background-size: cover;
	background-position: top center;
	background-repeat: no-repeat;

	h1 {
		margin-bottom: 0;
	}
}

main {
	padding-top: $global-padding * 2;
}

footer {
	padding: $global-padding;
	font-size: 70%;
	text-transform: uppercase;
	opacity: .7;
	letter-spacing: .5px;
}
```
