var gulp = require("gulp");
var jshint = require("gulp-jshint");
var uglify = require("gulp-uglify");
var minifyCSS = require("gulp-minify-css");
var concat = require("gulp-concat");
var rename = require("gulp-rename");
var del = require("del");
var assets = require("gulp-assets");

gulp.task("css", function() {
    gulp.src("./templates/layouts/default.html")
        .pipe(assets({
            js: false,
            css: "site.css",
            cwd: "../../public/"
        }))
        .pipe(concat("site.css"))
        .pipe(minifyCSS({ comments:true, spare:true }))
        .pipe(rename({ suffix: ".min" }))
        .pipe(gulp.dest("./public/assets/dist"));
});

gulp.task("js", function() {
    gulp.src("./templates/layouts/default.html")
        .pipe(assets({
            js: "site.js",
            css: false,
            cwd: "../../public/"
        }))
        .pipe(concat("site.js"))
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(gulp.dest("./public/assets/dist"));
});

gulp.task("lint", function() {
  gulp.src([
    "./public/assets/js/**/*.js", 
  ])
    .pipe(jshint())
    .pipe(jshint.reporter("default"))
    .pipe(jshint.reporter("fail"));
});

gulp.task("clean", function(cb) {
    del([
        "public/assets/dist/*.css",
        "public/assets/dist/*.min.css",
        "public/assets/dist/*.js",
        "public/assets/dist/*.min.js"
    ], cb);
});

gulp.task("default", ["clean", "lint", "css", "js"]);
