var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync');
//var useref = require('gulp-useref');
var uglify = require('gulp-uglify');
var gulpIf = require('gulp-if');
var cssnano = require('gulp-cssnano');
var sourcemaps = require('gulp-sourcemaps');
var imagemin = require('gulp-imagemin');
var cache = require('gulp-cache');
var del = require('del');
var runSequence = require('run-sequence');
var plumber = require('gulp-plumber');

// Development Tasks
// -----------------

// Start browserSync server
//gulp.task('browserSync', function() {
//    browserSync({
//        server: {
//            baseDir: 'app'
//        }
//    })
//})


gulp.task('browserSync', function() {
    //watch files
    var files = [
        './src/css/init.css'
        //'./*.php'
    ];

    //initialize browsersync
    browserSync.init(files, {
        //browsersync with a php server
        proxy   : "http://osmilab",
        notify: false
    });
});

gulp.task('sass', function() {
    return gulp.src('src/scss/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(cssnano())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('src/css'))
        .pipe(browserSync.reload({ // Reloading with Browser Sync
            stream: true
        }));
})



// Watchers
gulp.task('watch', function() {
    gulp.watch('src/scss/**/*.scss', ['sass']);
    //gulp.watch('src/css/init.css', browserSync.reload);
    gulp.watch('./**/*.php', browserSync.reload);
    gulp.watch('src/js/**/*.js', browserSync.reload);
})

// Optimization Tasks
// ------------------

// Optimizing CSS and JavaScript
//gulp.task('useref', function() {
//
//    //return gulp.src('./**/*.php')
//    return gulp.src('header.php', 'footer.php')
//        //.pipe(plumber())
//        .pipe(useref())
//        .pipe(gulpIf('*.js', uglify()))
//        .pipe(gulpIf('*.css', cssnano()))
//        .pipe(gulp.dest(''));
//});

// Optimizing Images
gulp.task('images', function() {
    return gulp.src('src/images/**/*.+(png|jpg|jpeg|gif|svg)')
        .pipe(plumber())
        // Caching images that ran through imagemin
        .pipe(cache(imagemin({
            interlaced: true
        })))
        //.pipe(gulp.dest('dist/images'))
        .pipe(gulp.dest(''))
});

// Copying fonts
//gulp.task('fonts', function() {
//    return gulp.src('src/fonts/**/*')
//        .pipe(plumber())
//        .pipe(gulp.dest('dist/fonts'))
//})

// Cleaning
//gulp.task('clean', function() {
//    return del.sync('src').then(function(cb) {
//        return cache.clearAll(cb);
//    });
//})
//
//gulp.task('clean:dist', function() {
//    return del.sync(['dist/**/*', '!dist/images', '!dist/images/**/*']);
//});

// Build Sequences
// ---------------

gulp.task('default', function(callback) {
    runSequence(['sass', 'browserSync', 'watch'],
        callback
    )
})

gulp.task('build', function(callback) {
    runSequence(
        //'clean:dist',
        //['sass', 'useref', 'images', 'fonts'],
        ['sass', 'images'],
        callback
    )
})