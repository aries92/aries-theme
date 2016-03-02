var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync');
var cssnano = require('gulp-cssnano');
var sourcemaps = require('gulp-sourcemaps');
var imagemin = require('gulp-imagemin');
var cache = require('gulp-cache');
var runSequence = require('run-sequence');
var plumber = require('gulp-plumber');
//var concat = require('gulp-concat-css');

function handleError(err) {
    console.log(err.toString());
    this.emit('end');
}

// Development Tasks
// -----------------

gulp.task('browserSync', function() {
    // //watch files
    // var files = [
    //     './src/css/init.css'
    //     //'./*.php'
    // ];

    // //initialize browsersync
    // browserSync.init(files, {
    //     server: {
    //         baseDir: "./"
    //     },
    //     notify: false
    // });

    //initialize browsersync
    browserSync.init({
        server: {
            baseDir: "./"
        },
        notify: false
    });
});

gulp.task('sass', function() {
    return gulp.src('src/scss/init.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .on('error', handleError)
        .pipe(autoprefixer({
            browsers: ['last 5 versions'],
            cascade: false
        }))
        .pipe(cssnano())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('src/css'))
        .on('error', handleError)
        .pipe(browserSync.reload({
            stream: true
        }));
})

// Watchers
gulp.task('watch', function() {
    gulp.watch('src/scss/**/*.scss', ['sass']);
    //gulp.watch('src/css/init.css', ['sass']);
    gulp.watch('./**/*.html', browserSync.reload);
    gulp.watch('src/js/**/*.js', browserSync.reload);
})

// Optimization Tasks
// ------------------

// Optimizing Images
gulp.task('images', function() {
    return gulp.src('src/images/**/*.+(png|jpg|jpeg|gif|svg)')
        .pipe(plumber())
        // Caching images that ran through imagemin
        .pipe(cache(imagemin({
            interlaced: true
        })))
        .pipe(gulp.dest(''))
});

// Build Sequences
// ---------------

gulp.task('default', function(callback) {
    runSequence(['sass', 'browserSync', 'watch'],
        callback
    )
})

gulp.task('build', function(callback) {
    runSequence(
        ['sass', 'images'],
        callback
    )
})
