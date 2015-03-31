var gulp = require('gulp'),
    gulpLoadPlugins = require('gulp-load-plugins'),
    plugins = gulpLoadPlugins({
        rename: {
            'gulp-strip-css-comments': 'stripComments'
        }
    }),
    files = {
        js: {
            core: [
                './Resources/public/js/core.js',
                './Resources/public/js/vendor/mustache.js',
                './Resources/public/js/plugins.js',
                './Resources/public/js/default.js',
                './Resources/public/js/list.js'
            ],
            destination: './Resources/public/js/min/'
        },
        css: {
            core: [
                './Resources/stylus/core.styl'
            ],
            destination: './Resources/public/css/min/'
        }
    };

gulp.task('build:assets', ['core-js', 'core-css']);
gulp.task('default', ['build:assets']);

gulp.task('watch', ['build:assets'], function(){
    gulp.watch(files.css.core, ['build:css-core']);
    gulp.watch(files.js.core,['build:js-core']);
});

gulp.task('core-css', ['build:css-core']);
gulp.task('core-js', ['build:js-core']);

gulp.task('build:css-core', function () {
    return gulp.src(files.css.core)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
            .pipe(plugins.stylus({sourceComments: 'map', compress: true}))
            .pipe(plugins.concat('core.css'))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest(files.css.destination));
});

gulp.task('build:js-core', function() {
    return gulp.src(files.js.core)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
            .pipe(plugins.uglify())
            .pipe(plugins.concat('core.js'))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest(files.js.destination));
});
