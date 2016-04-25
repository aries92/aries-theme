var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var babel = require('gulp-babel');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin'),
    cache = require('gulp-cache');
var cssnano = require('gulp-cssnano')
var sass = require('gulp-sass');
	sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync');

gulp.task('browser-sync', function() {
  browserSync({
    server: {
       baseDir: "./"
    },
    notify: false
  });
});

gulp.task('bs-reload', function () {
  browserSync.reload();
});

gulp.task('images', function(){
  gulp.src('src/img/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('dist/img/'));
});

gulp.task('styles', function(){
  gulp.src(['src/scss/init.scss'])
    .pipe(plumber({
      errorHandler: function (error) {
        console.log(error.message);
        this.emit('end');
    }}))
    .pipe(sourcemaps.init())
    .pipe(sass())
	.pipe(autoprefixer({
	    browsers: ['last 5 versions'],
	    cascade: false
	}))
    //.pipe(gulp.dest('dist/css/'))
    .pipe(rename({suffix: '.min'}))
    .pipe(cssnano())
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('dist/css/'))
    .pipe(browserSync.reload({stream:true}))
});

gulp.task('scripts', function(){
  return gulp.src('src/js/**/*.js')
    .pipe(plumber({
      errorHandler: function (error) {
        console.log(error.message);
        this.emit('end');
    }}))
    //.pipe(concat('init.js'))
    //.pipe(babel())
    //.pipe(gulp.dest('dist/js/'))
    //.pipe(rename({suffix: '.min'}))
    //.pipe(uglify())
    //.pipe(gulp.dest('dist/js/'))
    .pipe(browserSync.reload({stream:true}))
});

gulp.task('default', ['browser-sync'], function(){
  gulp.watch("src/scss/**/*.scss", ['styles']);
  gulp.watch("src/js/**/*.js", ['scripts']);
  gulp.watch("*.html", ['bs-reload']);
});
