const gulp = require('gulp')
//　エラー処理
const notify = require('gulp-notify');  // エラー通知
const plumber = require('gulp-plumber'); // エラー時のタスク停止防止
const debug = require('gulp-debug'); // ログ表示

// scss
const gulpDartSass = require('gulp-dart-sass')
const sassGlob = require('gulp-sass-glob-use-forward')
// autoprefixer
const autoprefixer = require('gulp-autoprefixer')

// hot reload
const browserSync = require('browser-sync').create()

// webp
const webp = require('gulp-webp');
const rename = require('gulp-rename');

// webpack
const webpack = require('webpack');
const webpackStream = require('webpack-stream');
const webpackConfig = require('./webpack.config.js');
const path = require('path');
const named = require('vinyl-named');
const filter = require('gulp-filter');

gulp.task('default', function () {
  return gulp.src('assets/scss/style.scss').
    pipe(plumber()).
    pipe(sassGlob()).
    pipe(gulpDartSass(
      { outputStyle: 'compressed', includePaths: ['assets/scss'] })).
    pipe(autoprefixer()).
    pipe(gulp.dest('public/css'))
})

gulp.task('webp', function() {
  return gulp.src('./images/**/*.{jpg,jpeg,png}')
  .pipe(rename(function(path) {
    path.basename += path.extname;
  }))
  .pipe(webp({
    // オプションを追加
    quality: 50,
    method: 6,
  }))
  .pipe(gulp.dest('./images'))
})


/**
 * webpack
 */
const paths = {
  js: {
    src: 'assets/js/**/*.js', // コンパイル対象
    dest: 'public/js' // 出力先
  }
}

gulp.task('js', function () {
  return gulp.src(paths.js.src)
  .pipe(plumber({
    errorHandler: notify.onError('Error: <%= error.message %>')
  }))
  .pipe(filter(function (file) { // _から始まるファイルを除外
    return !/\/_/.test(file.path) && !/^_/.test(file.relative);
  }))
  .pipe(named((file) => {
      const p = path.parse(file.relative);
      return ((p.dir) ? p.dir + path.sep : '') + p.name;
    })
  )
  .pipe(webpackStream(webpackConfig, webpack))
  .pipe(gulp.dest(paths.js.dest))
  .pipe(debug({title: 'js dest:'}));
})

//ブラウザの設定

function sync(done) {
  browserSync.init({
    proxy: 'http://localhost:8888/twitter/',  // Local by Flywheelのドメイン
    open: true,
    watchOptions: {
      debounceDelay: 1000,  //1秒間、タスクの再実行を抑制
    },
  })
  done()
}

function watch (done) {
  const reload = () => {
    browserSync.reload()
    done()
  }
  gulp.watch('assets/**/*.scss').on('change', gulp.series(gulp.task('default'), reload))
  gulp.watch('./**/*.php').on('change', gulp.series(reload))
  gulp.watch('assets/**/*.js').on('change', gulp.series(gulp.task('js'), reload))
}

gulp.task('dev', gulp.series(gulp.series(sync, watch)))
