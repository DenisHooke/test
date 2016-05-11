'use strict';

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    rigger = require('gulp-rigger'),
    cssmin = require('gulp-minify-css'),
    sourcemaps = require('gulp-sourcemaps'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant'),
    rimraf = require('rimraf'),
    browserSync = require("browser-sync"),
    reload = browserSync.reload;

var path = {
    build: {
        js: 'web/js/',
        css: 'web/css/',
        img: 'web/img/',
        fonts: 'web/fonts/'
    },
    src: {
        js: 'app/Resources/js/app.js',
        style: 'app/Resources/style/main.css',
        img: 'app/Resources/img/**/*.*',
        fonts: 'app/Resources/fonts/**/*.*'
    },
    watch: {
        js: 'app/Resources/js/**/*.js',
        style: 'app/Resources/style/**/*.css',
        img: 'app/Resources/img/**/*.*',
        fonts: 'app/Resources/fonts/**/*.*'
    },
    clean: './web/assets'
};

/*var config = {
    server: {
        baseDir: "./web/assets"
    },
    proxy: "myproject.dev",
    files: ["*.css, *.html, *.php, *.js"],
    tunnel: true,
    host: 'localhost',
    port: 9000,
};*/

gulp.task('webserver', function () {
    //browserSync(config);
    browserSync.init({
        proxy: "klika.dev/app_dev.php"
    });
});

gulp.task('clean', function (cb) {
    rimraf(path.clean, cb);
});


gulp.task('js:build', function () {
    gulp.src(path.src.js)
        .pipe(rigger())
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(path.build.js))
        .pipe(reload({stream: true}));
});

gulp.task('style:build', function () {
    gulp.src(path.src.style)
        .pipe(sourcemaps.init())
        .pipe(prefixer())
        .pipe(cssmin())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(path.build.css))
        .pipe(reload({stream: true}));
});

gulp.task('image:build', function () {
    gulp.src(path.src.img)
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.build.img))
        .pipe(reload({stream: true}));
});

gulp.task('fonts:build', function() {
    gulp.src(path.src.fonts)
        .pipe(gulp.dest(path.build.fonts))
});

gulp.task('build', [
    'js:build',
    'style:build',
    'fonts:build',
    'image:build'
]);


gulp.task('watch', function(){
    watch([path.watch.style], function(event, cb) {
        gulp.start('style:build');
    });
    watch([path.watch.js], function(event, cb) {
        gulp.start('js:build');
    });
    watch([path.watch.img], function(event, cb) {
        gulp.start('image:build');
    });
    watch([path.watch.fonts], function(event, cb) {
        gulp.start('fonts:build');
    });
});


gulp.task('default', ['build', 'webserver', 'watch']);