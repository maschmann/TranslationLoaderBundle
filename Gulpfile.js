var gulp = require('gulp'),
    //_ = require('lodash'),
    gulpLoadPlugins = require('gulp-load-plugins'),
    plugins = gulpLoadPlugins({
        rename: {
            'gulp-strip-css-comments': 'stripComments'
        }
    }),
    files = {
        js: {
            core: [
                './vendor/asm/translation-loader-bundle/Resources/public/js/main.js',
                './vendor/asm/translation-loader-bundle/Resources/public/js/vendor/mustache.js',
                './vendor/asm/translation-loader-bundle/Resources/public/js/plugins.js'
            ],
            destination: './web/bundles/asmtranslationloader/js/min/'
        },
        css: {
            core: [
                './vendor/asm/translation-loader-bundle/Resources/stylus/core.styl'
            ],
            destination: './web/bundles/asmtranslationloader/css/min/'
        }
    };

console.log(plugins);
gulp.task('handle-assets', ['core-js', 'core-css']);

gulp.task('default', ['core-js', 'core-css'], function(){
    gulp.watch(files.css.core, ['build-css-core']);
    gulp.watch(files.js.core,['build-js-core']);
});

gulp.task('core-css', ['build-css-core']);
gulp.task('core-js', ['build-js-core']);

gulp.task('build-css-core', function () {
    return gulp.src(files.css.core)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
            .pipe(plugins.stylus({sourceComments: 'map', compress: true}))
            .pipe(plugins.concat('core.css'))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest(files.css.destination));
});

gulp.task('build-js-core', function() {
    return gulp.src(files.js.core)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
            .pipe(plugins.uglify())
            .pipe(plugins.concat('core.js'))
            .pipe(plugins.jshint())
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest(files.js.destination));
});
