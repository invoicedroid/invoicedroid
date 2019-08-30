const mix = require('laravel-mix')
const path = require('path')
const fs = require('fs-extra')
require('laravel-mix-versionhash')


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

mix.js('resources/js/app.js', 'public/dist/js')
    .sass('resources/sass/app.scss', 'public/dist/css')
    .disableNotifications()


if (mix.inProduction()) {
    mix
    // .extract() // Disabled until resolved: https://github.com/JeffreyWay/laravel-mix/issues/1889
    // .version() // Use `laravel-mix-versionhash` for the generating correct Laravel Mix manifest file.
        .versionHash()
} else {
    mix.sourceMaps()
}

//
mix.webpackConfig({
    plugins: [
        // new BundleAnalyzerPlugin()
    ],
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            '~': path.join(__dirname, './resources/js')
        }
    },
    output: {
        chunkFilename: 'dist/js/[chunkhash].js',
        path: mix.config.hmr ? '/' : path.resolve(__dirname, './public/build')
    }
})

mix.then(() => {
    if (!mix.config.hmr) {
        process.nextTick(() => publishAseets())
    }
})

function publishAseets () {
    const publicDir = path.resolve(__dirname, './public')

    if (mix.inProduction()) {
        fs.removeSync(path.join(publicDir, 'dist'))
    }

    fs.copySync(path.join(publicDir, 'build', 'dist'), path.join(publicDir, 'dist'))
    fs.removeSync(path.join(publicDir, 'build'))
}
