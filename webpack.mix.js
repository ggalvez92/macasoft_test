const mix = require('laravel-mix');


mix.js('resources/assets/backend/js/app.js',
		'public/backend/js/app.js')
	.js('resources/assets/login/js/app.js',
		'public/login/js/app.js')
	.less('resources/assets/backend/less/app.less','public/backend/css/app.css')
	.less('resources/assets/login/less/app.less','public/login/css/app.css')
	.sourceMaps()
    .webpackConfig({
        devtool: 'source-map'
    })
    .options({
        processCssUrls: false
    });