const gulp = require('gulp');
const gulpLoadPlugins = require('gulp-load-plugins');
const browserSync = require('browser-sync').create();
const del = require('del');
const wiredep = require('wiredep').stream;
const runSequence = require('run-sequence');

const $ = gulpLoadPlugins();
const reload = browserSync.reload;

let dev = true;

gulp.task('styles', () => {
  return gulp.src('app/css/*.scss')
    .pipe($.plumber())
    .pipe($.if(dev, $.sourcemaps.init()))
    .pipe($.sass.sync({
      outputStyle: 'expanded',
      precision: 10,
      includePaths: [
        "node_modules",
        "bower_components"
      ],
    }).on('error', $.sass.logError))
    .pipe($.cssimport({
      matchPattern: "*.css",
      includePaths: [
        "node_modules",
        "bower_components"
      ]
    }))
    .pipe($.autoprefixer({browsers: ['> 1%', 'last 2 versions', 'Firefox ESR']}))
    .pipe($.if(dev, $.sourcemaps.write()))
    .pipe(gulp.dest('dist/css'))
    .pipe(reload({stream: true}));
});

gulp.task('scripts', () => {
  return gulp.src('app/js/**/*.js')
    .pipe($.plumber())
    .pipe($.if(dev, $.sourcemaps.init()))
    .pipe($.babel())
    .pipe($.if(dev, $.sourcemaps.write('.')))
    .pipe(gulp.dest('dist/js'))
    .pipe(reload({stream: true}));
});

function lint(files) {
  return gulp.src(files)
    .pipe($.eslint({ fix: true }))
    .pipe(reload({stream: true, once: true}))
    .pipe($.eslint.format())
    .pipe($.if(!browserSync.active, $.eslint.failAfterError()));
}

gulp.task('lint', () => {
  return lint('app/js/**/*.js')
    .pipe(gulp.dest('dist/js'));
});

gulp.task('php', () => {
  return gulp.src('app/**/*.php')
    .pipe($.useref({searchPath: ['app', '.', '..']}))
    .pipe(gulp.dest('dist'))
    .pipe(reload({stream: true}));
})

gulp.task('html', ['styles', 'scripts'], () => {
  return gulp.src('app/*.html')
    .pipe($.useref({searchPath: ['.tmp', 'app', '.']}))
    .pipe($.if(/\.js$/, $.uglify({compress: {drop_console: true}})))
    .pipe($.if(/\.css$/, $.cssnano({safe: true, autoprefixer: false})))
    .pipe($.if(/\.html$/, $.htmlmin({
      collapseWhitespace: true,
      minifyCSS: true,
      minifyJS: {compress: {drop_console: true}},
      processConditionalComments: true,
      removeComments: true,
      removeEmptyAttributes: true,
      removeScriptTypeAttributes: true,
      removeStyleLinkTypeAttributes: true
    })))
    .pipe(gulp.dest('dist'))
    .pipe(reload({stream: true}));
});

gulp.task('images', () => {
  return gulp.src([
      'app/images/**/*',
      'node_modules/lightbox2/dist/images/*'
    ])
    .pipe($.cache($.imagemin()))
    .pipe(gulp.dest('dist/images'));
});

gulp.task('fonts', () => {
  return gulp.src([
    'app/fonts/**/*',
    'bower_components/font-awesome/fonts/*.{eot,svg,ttf,woff,woff2}'
  ])
    .pipe(gulp.dest('dist/fonts'));
});

gulp.task('extras', () => {
  return gulp.src([
    'app/*',
    '!app/*.html'
  ], {
    dot: true
  }).pipe(gulp.dest('dist'));
});

gulp.task('clean', del.bind(null, ['.tmp', 'dist']));

gulp.task('serve', () => {
  runSequence(['wiredep'], ['build'], () => {
    browserSync.init({
      notify: false,
      port: 9000,
      proxy: "http://localhost/~syphez/plugg/dt148g/moment4/dist",
    });

    gulp.watch([
      'app/*.html',
      'app/images/**/*',
      '.tmp/fonts/**/*'
    ]).on('change', reload);

    gulp.watch('app/css/**/*.scss', ['styles']);
    gulp.watch('app/js/**/*.js', ['scripts']);
    gulp.watch('app/fonts/**/*', ['fonts']);
    gulp.watch('bower.json', ['wiredep', 'fonts']);
    gulp.watch('app/*.html', ['html']);

    gulp.watch([
      "app/**/*.php"
    ], function (obj) {
      return gulp.src(obj.path, {"base": "app/"})
      .pipe(gulp.dest("dist"))
      .pipe($.if(/^(?!.*api\.php$).*$/, reload({stream: true})));
    });
  });
});


// inject bower components
gulp.task('wiredep', () => {
  gulp.src('app/css/*.scss')
    .pipe($.filter(file => file.stat && file.stat.size))
    .pipe(wiredep({
      ignorePath: /^(\.\.\/)+/
    }))
    .pipe(gulp.dest('app/css'));

  gulp.src([
      'app/*.html',
      'app/*.php',
    ])
    .pipe(wiredep())
    .pipe(gulp.dest('app'));
});

gulp.task('build', ['lint', 'html', 'php', 'images', 'fonts', 'extras', 'scripts', 'styles'], () => {
  return gulp.src('dist/**/*').pipe($.size({title: 'build', gzip: true}));
});

gulp.task('default', () => {
  return new Promise(resolve => {
    dev = false;
    runSequence(['clean', 'wiredep'], 'build', resolve);
  });
});
