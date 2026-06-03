const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .sass('resources/scss/erpnext/erpnext.scss', 'public/css/erpnext.css')
    .sass('resources/scss/erpnext/point-of-sale.scss', 'public/css/pos.css')
    .sass('resources/scss/erpnext/website.scss', 'public/css/erpnext-website.css')
    .js('resources/js/erpnext/utils.js', 'public/js/erpnext-utils.js')
    .js('resources/js/erpnext/conf.js', 'public/js/erpnext-conf.js')
    .copyDirectory('public/icons', 'public/build/icons')
    .copyDirectory('public/images', 'public/build/images');

// Disable webpackbar progress plugin to avoid webpack 5 schema validation error
mix.override(webpackConfig => {
    webpackConfig.plugins = webpackConfig.plugins.filter(
        plugin => plugin.constructor.name !== 'WebpackBarPlugin'
    );
});
