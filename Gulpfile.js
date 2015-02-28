var gulp = require('gulp'),
    //_ = require('lodash'),
    gulpLoadPlugins = require('gulp-load-plugins'),
    plugins = gulpLoadPlugins({
        rename: {
            'gulp-strip-css-comments': 'stripComments'
        }
    }),
    theme = 'default',
    env = 'dev',
    minify = false,
    files = {
        js: {
            bootstrap: [
                './vendor/bower-asset/jquery/dist/jquery.js',
                './vendor/bower-asset/jquery-ui/jquery-ui.js',
                './vendor/npm-asset/bootstrap/dist/js/bootstrap.js'
            ],
            core: [
                './src/MxBundle/Resources/public/js/main.js',
                './src/MxBundle/Resources/public/js/mustache.js',
                './src/MxBundle/Resources/public/js/plugins.js'
            ],
            user: [
                './src/MxBundle/Resources/public/js/user.*.js'
            ]
        },
        css: {
            bootstrap: [
                './vendor/npm-asset/bootstrap/dist/css/bootstrap.css',
                './vendor/npm-asset/bootstrap/dist/css/bootstrap-theme.css',
                './vendor/bower-asset/jquery-ui/themes/ui-darkness/jquery-ui.css',
                './vendor/bower-asset/jquery-ui/themes/ui-darkness/theme.css'
            ],
            core: [
                './src/MxBundle/Resources/stylus/main.styl'
            ],
            user: [
                './src/MxBundle/Resources/stylus/user.*.styl'
            ]
        }
    };

/**
 * check for commandline params and define defaults
 */
if (plugins.util.env.env && plugins.util.env.env == 'prod') {
    minify = true;
    errorlog = false;
    env = 'prod';
}

console.log(plugins);
gulp.task('handle-assets', ['core-js', 'core-css']);

gulp.task('default', ['core-js', 'core-css'], function(){
    gulp.watch(files.css.bootstrap, ['build-css-bootstrap']);
    gulp.watch(files.css.core, ['build-css-core']);
    gulp.watch(files.css.user, ['build-css-user']);
    gulp.watch(files.js.bootstrap,['build-js-bootstrap']);
    gulp.watch(files.js.core,['build-js-core']);
    gulp.watch(files.js.user,['build-js-user']);
});

gulp.task('core-css', ['build-css-bootstrap', 'build-css-core', 'build-css-user']);
gulp.task('core-js', ['build-js-bootstrap', 'build-js-core', 'build-js-user']);

gulp.task('build-css-bootstrap', function () {
    return gulp.src(files.css.bootstrap)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
            .pipe(plugins.if(minify, plugins.uglifycss()))
            .pipe(plugins.concat('bootstrap.css'))
            .pipe(plugins.stripComments({all: true}))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('./web/css/' + env));
});

gulp.task('build-css-core', function () {
    return gulp.src(files.css.core)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
            .pipe(plugins.stylus({sourceComments: 'map', compress: minify}))
            .pipe(plugins.concat('core.css'))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('./web/css/' + env));
});

gulp.task('build-css-user', function () {
    return gulp.src(files.css.user)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
            .pipe(plugins.stylus({sourceComments: 'map', compress: minify}))
            .pipe(plugins.concat('user.css'))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('./web/css/' + env));
});

gulp.task('build-js-bootstrap', function() {
    return gulp.src(files.js.bootstrap)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.if(minify, plugins.uglify()))
        .pipe(plugins.concat('bootstrap.js'))
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('./web/js/' + env));
});

gulp.task('build-js-core', function() {
    return gulp.src(files.js.core)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.if(minify, plugins.uglify()))
        .pipe(plugins.concat('core.js'))
        .pipe(plugins.jshint())
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('./web/js/' + env));
});

gulp.task('build-js-user', function() {
    return gulp.src(files.js.user)
        .pipe(plugins.plumber())
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.if(minify, plugins.uglify()))
        .pipe(plugins.concat('user.js'))
        .pipe(plugins.jshint())
        .pipe(plugins.sourcemaps.write('./'))
        .pipe(gulp.dest('./web/js/' + env));
});

// Without this function exec() will not show any output
var logStdOutAndErr = function (err, stdout, stderr) {
    console.log(stdout + stderr);
};
