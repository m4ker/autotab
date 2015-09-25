var gulp = require('gulp');

gulp.task('task1', function() {
	// place code for your default task here
	return gulp.src('src/bootstrap/dist/**')
		.pipe(gulp.dest('css/bootstrap'));
});

gulp.task('task2', function() {
	// place code for your default task here
	return gulp.src('src/jquery/dist/**')
		.pipe(gulp.dest('js'));
});

gulp.task('default', ['task1', 'task2']);
