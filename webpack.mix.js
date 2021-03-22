const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
  // .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/admin/users/app.js', 'public/js/admin/users-vue/');
mix.js('resources/js/admin/sessions/app.js', 'public/js/admin/sessions-vue/');
mix.js('resources/js/admin/categories/app.js', 'public/js/admin/categories-vue/');
mix.js('resources/js/admin/profile/app.js', 'public/js/admin/profile-vue/');
mix.js('resources/js/admin/login/app.js', 'public/js/admin/login-vue/');

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js/'),
            '@users': path.resolve(__dirname, 'resources/js/admin/users'),
            '@sessions': path.resolve(__dirname, 'resources/js/admin/sessions'),
            '@categories': path.resolve(__dirname, 'resources/js/admin/categories'),
            '@profile': path.resolve(__dirname, 'resources/js/admin/profile'),
            '@login': path.resolve(__dirname, 'resources/js/admin/login'),
        }
    }
})
