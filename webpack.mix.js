const mix = require('laravel-mix');
const path = require('path')

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
  .vue({ version: 2})
  .sass('resources/scss/app.scss', 'public/css');

mix.webpackConfig({
    resolve: {
        alias: {
            // vue: '@vue/compat',
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
    // module: {
    //     rules: [
    //         {
    //             test: /\.mjs$/i, // test: /\.vue$/,
    //             resolve: { byDependency: { esm: { fullySpecified: false } } },
    //         },
    //         {
    //             test: /\.vue$/,
    //             loader: 'vue-loader',
    //             options: {
    //                 compilerOptions: {
    //                     compatConfig: {
    //                         MODE: 3,
    //                         WATCH_ARRAY: true,
    //                         COMPILER_FILTER: 'deep',
    //                         whitespace: 'preserve'
    //                     },
    //                     whitespace: 'preserve'
    //                 },
    //             }
    //         }
    //     ],
    // }
});
