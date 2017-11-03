'use strict';

import plugins       from 'gulp-load-plugins';
import browser       from 'browser-sync';
import gulp          from 'gulp';
import yaml          from 'js-yaml';
import fs            from 'fs';
import webpackStream from 'webpack-stream';
import webpack2      from 'webpack';
import del           from 'del';

// ----------------------------------------------------------------------------
// Load all Gulp plugins into one variable
// ----------------------------------------------------------------------------

const $ = plugins();

// ----------------------------------------------------------------------------
// Read info from package.json to one variable
// ----------------------------------------------------------------------------

const pkg = require('./package.json');

// ----------------------------------------------------------------------------
// Load settings from config.yml
// ----------------------------------------------------------------------------

const { COMPATIBILITY, PORT, UNCSS_OPTIONS, PATHS } = loadConfig();

function loadConfig() {
  let ymlFile = fs.readFileSync('config.yml', 'utf8');
  return yaml.load(ymlFile);
}

// ----------------------------------------------------------------------------
// Build the site, run the server, and watch for file changes
// ----------------------------------------------------------------------------

gulp.task( 'default', gulp.series( clean, gulp.parallel( sass, javascript ), serve, watch ) );

// ----------------------------------------------------------------------------
// Delete the style files every time a build starts
// ----------------------------------------------------------------------------

function clean(done) {
	return del([
		'./style.css',
		'./style.css.map',
		'./js/theme.min.js',
	]);
	done();
}

// ----------------------------------------------------------------------------
// Compile Sass into CSS
// ----------------------------------------------------------------------------

function sass() {
  return gulp.src('src/assets/scss/app.scss')
    .pipe($.sourcemaps.init())
    .pipe($.sass({
      includePaths: PATHS.sass
    })
      .on("error", $.notify.onError("Error: <%= error.message %>")))
    .pipe($.autoprefixer({
      browsers: COMPATIBILITY
    }))
    .pipe($.cleanCss({ compatibility: 'ie9' }))
		.pipe($.rename('style.css')) // rename stylesheet for WordPress
    .pipe($.sourcemaps.write(PATHS.dist))
    .pipe(gulp.dest(PATHS.dist))
    .pipe(browser.reload({ stream: true }));
}

let webpackConfig = {
	externals: {
		jquery: 'jQuery' // disable including jQuery library in minified JavaScript file
	},
  module: {
    rules: [
      {
        test: /.js$/,
        use: [
          {
            loader: 'babel-loader'
          }
        ]
      }
    ]
  }
}
// ----------------------------------------------------------------------------
// Combine JavaScript into one minified file
// ----------------------------------------------------------------------------

function javascript() {
  return gulp.src(PATHS.entries)
    .pipe(webpackStream(webpackConfig, webpack2))
		.pipe($.uglify()
      .on('error', e => { console.log(e); })
    )
		.pipe($.rename( 'theme.min.js' ))
    .pipe(gulp.dest(PATHS.dist + '/js'))
		.pipe(browser.reload({ stream: true }));
}
// ----------------------------------------------------------------------------
// Start a server with BrowserSync to preview the site
// ----------------------------------------------------------------------------

function serve( done ) {
  browser.init({
		host: pkg.vars.hostname,
    port: 8000,
    proxy: pkg.vars.hostname + '/' + pkg.name,
    files: "*.php", // watch for PHP file modifications
		ui: false,
		notify: true,
  });
  done();
}

// ----------------------------------------------------------------------------
// Watch for changes to Sass and JavaScript
// ----------------------------------------------------------------------------

function watch() {
  gulp.watch('src/assets/scss/**/*.scss').on( 'all', sass );
  gulp.watch('src/assets/js/**/*.js').on( 'all', javascript );
}
