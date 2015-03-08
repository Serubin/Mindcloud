module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			options: {
				includePaths: ['bower_components/foundation/scss']
			},
			dist: {
				options: {
					outputStyle: 'compressed'
				},
				files: {
					'../assets/css/master.css': '../scss/master.scss',
				}				
			}
		},
		watch: {
			grunt: { files: ['Gruntfile.js'] },

			sass: {
				files: '../scss/**/*.scss',
				tasks: ['build']
			},
			js: {
				files: ['../js/**/*.js'],
				tasks: ['build']
			},
			js_framework: {
				files: ['bower_components/**/*'],
				tasks: ['build']
			}
		},
		clean: {
			js_clean: {
				options: { force: true },
				src: [ '../assets/js/' ]
			},
			scss_clean: {
				options: { force: true },
				src: ['../assets/css/']
			}
		},
		concat: {
			js_include_concat: {
				src: ['../js/include/*.js'],
				dest: '../assets/js/includes.js'
			},
			js_app_concat: {
				src: ['../js/*.js'],
				dest: '../assets/js/apps.js'
			},
			js_framework_concat: {
				src: ['bower_components/jquery/dist/jquery.js', 
					  'bower_components/modernizr/modernizr.js', 
<<<<<<< HEAD
					  'bower_components/foundation/js/foundation.js'],
=======
					  'bower_components/foundation/js/foundation.js',
					  'bower_components/jquery-tags-input/jquery.tagsinput.js'
					  ],
>>>>>>> origin/feat_dashboard
				dest: '../assets/js/frameworks.js'
			}
		}
	});

	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('build', ['clean','sass','concat']);
	grunt.registerTask('default', ['build','watch']);
}
