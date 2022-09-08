const gulp = require("gulp");
const rtlcss = require("gulp-rtlcss");
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require("gulp-sourcemaps");
const jshint = require("gulp-jshint");
const concat = require("gulp-concat");
const uglify = require("gulp-uglify");
const sort = require("gulp-sort");
const wppot = require("gulp-wp-pot");
const gettext = require("gulp-gettext");
const plumber = require("gulp-plumber");
const autoprefixer = require("gulp-autoprefixer");
const gutil = require("gulp-util");
const rename = require("gulp-rename");
const map = require("map-stream");
const browserlist = ["last 2 version", "> 1%"];

const errorreporter = map(function(file, cb) {
	if (file.jshint.success) {
		return cb(null, file);
	}

	console.log("JSHINT fail in", file.path);

	file.jshint.results.forEach(function(result) {
		if (!result.error) {
			return;
		}

		const err = result.error;
		console.log(
			`  line ${err.line}, col ${err.character}, code ${err.code}, ${
				err.reason
			}`
		);
	});

	cb(null, file);
});

gulp.task("default", function() {
	console.log("Use the following commands");
	console.log("--------------------------");
	console.log("gulp compile-css    to compile the scss to css");
	console.log("gulp compile-js     to compile the js to min.js");
	console.log(
		"gulp watch          to continue watching the files for changes"
	);
	console.log(
		"gulp wordpress-lang to compile the lsx-sharing.pot, en_EN.po and en_EN.mo"
	);
});

gulp.task("styles", function(done) {
	return (
		gulp
			.src("assets/css/lsx-sharing.scss")
			.pipe(
				plumber({
					errorHandler: function(err) {
						console.log(err);
						this.emit("end");
					}
				})
			)
			.pipe(sourcemaps.init())
			.pipe(
				sass({
					outputStyle: "compact"
				}).on("error", gutil.log)
			)
			.pipe(
				autoprefixer({
					browsers: browserlist,
					casacade: true
				})
			)
			.pipe(sourcemaps.write("maps"))
			.pipe(gulp.dest("assets/css")),
		done()
	);
});

gulp.task("admin-style", function(done) {
	return (
		gulp
			.src("assets/css/lsx-sharing-admin.scss")
			.pipe(
				plumber({
					errorHandler: function(err) {
						console.log(err);
						this.emit("end");
					}
				})
			)
			.pipe(sourcemaps.init())
			.pipe(
				sass({
					outputStyle: "compact"
				}).on("error", gutil.log)
			)
			.pipe(
				autoprefixer({
					browsers: browserlist,
					casacade: true
				})
			)
			.pipe(sourcemaps.write("maps"))
			.pipe(gulp.dest("assets/css")),
		done()
	);
});

gulp.task("styles-rtl", function(done) {
	return (
		gulp
			.src("assets/css/lsx-sharing.scss")
			.pipe(
				plumber({
					errorHandler: function(err) {
						console.log(err);
						this.emit("end");
					}
				})
			)
			.pipe(
				sass({
					outputStyle: "compact"
				}).on("error", gutil.log)
			)
			.pipe(
				autoprefixer({
					browsers: browserlist,
					casacade: true
				})
			)
			.pipe(rtlcss())
			.pipe(
				rename({
					suffix: "-rtl"
				})
			)
			.pipe(gulp.dest("assets/css")),
		done()
	);
});

gulp.task(
	"compile-css",
	gulp.series(["styles", "styles-rtl", "admin-style"], function(done) {
		done();
	})
);

gulp.task("js", function(done) {
	gulp
		.src("assets/js/lsx-sharing.js")
		.pipe(jshint())
		.pipe(errorreporter)
		.pipe(concat("lsx-sharing.min.js"))
		.pipe(uglify())
		.pipe(gulp.dest("assets/js")),
		done();
});

gulp.task(
	"compile-js",
	gulp.series(["js"], function(done) {
		done();
	})
);

gulp.task("watch-css", function(done) {
	done();
	return gulp.watch("assets/css/**/*.scss", gulp.series("compile-css"));
});

gulp.task("watch-js", function(done) {
	done();
	return gulp.watch("assets/js/src/**/*.js", gulp.series("compile-js"));
});

gulp.task(
	"watch",
	gulp.series(["watch-css", "watch-js"], function(done) {
		done();
	})
);

gulp.task("wordpress-pot", function(done) {
	return (
		gulp
			.src("**/*.php")
			.pipe(sort())
			.pipe(
				wppot({
					domain: "lsx-sharing",
					package: "lsx-sharing",
					team: "LightSpeed <webmaster@lsdev.biz>"
				})
			)
			.pipe(gulp.dest("languages/lsx-sharing.pot")),
		done()
	);
});

gulp.task("wordpress-po", function(done) {
	return (
		gulp
			.src("**/*.php")
			.pipe(sort())
			.pipe(
				wppot({
					domain: "lsx-sharing",
					package: "lsx-sharing",
					team: "LightSpeed <webmaster@lsdev.biz>"
				})
			)
			.pipe(gulp.dest("languages/en_EN.po")),
		done()
	);
});

gulp.task(
	"wordpress-po-mo",
	gulp.series(["wordpress-po"], function(done) {
		return (
			gulp
				.src("languages/en_EN.po")
				.pipe(gettext())
				.pipe(gulp.dest("languages")),
			done()
		);
	})
);

gulp.task(
	"wordpress-lang",
	gulp.series(["wordpress-pot", "wordpress-po-mo"], function(done) {
		done();
	})
);
