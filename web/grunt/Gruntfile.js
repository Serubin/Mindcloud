module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			options: {
				includePaths: ['../bower_components/foundation/scss']
			},
			dist: {
				options: {
					outputStyle: 'compressed'
				},
				files: {
					'../assets/css/app.css': '../scss/app.scss',
					'../assets/css/login.css': '../scss/login.scss',
				}				
			}
		},
		watch: {
			grunt: { files: ['Gruntfile.js'] },

			sass: {
				files: '../scss/**/*.scss',
				tasks: ['sass']
			},
			js: {
				files: ['../assets/js/**/*.js', '!../assets/js/build/*.js'],
				tasks: ['clean', 'concat']
			}
		},
		clean: {
			js_clean: {
				options: { force: true },
				src: [ '../assets/js/build' ]
			}
		},
		concat: {
			js_include_concat: {
				src: ['../assets/js/include/*.js'],
				dest: '../assets/js/build/include.js'
			},
			js_app_concat: {
				src: ['../assets/js/*.js'],
				dest: '../assets/js/build/apps.js'
			}
		}
	});

	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('build', ['sass','clean','concat']);
	grunt.registerTask('default', ['build','watch']);
}
