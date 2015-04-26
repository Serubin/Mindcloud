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
			grunt: {
				files: ['Gruntfile.js'],
				tasks: ['build']
			},
			sass: {
				files: '../scss/**/*.scss',
				tasks: ['build']
			},
			js: {
				files: ['../js/**/*.js'],
				tasks: ['build']
			},
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
				src: ['../js/framework/*.js'],
				dest: '../assets/js/frameworks.js'
			}
		},
		uglify: {
			options: {
				mangle: {
					except: ['jQuery']
				}
			},
			my_target: {
				files: {
					'../assets/js/includes.js': ['../assets/js/includes.js'],
					'../assets/js/apps.js': ['../assets/js/apps.js'],
					'../assets/js/frameworks.js': ['../assets/js/frameworks.js']
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('build', ['clean','sass','concat']);
	grunt.registerTask('prd', ['clean','sass','concat','uglify']);
	grunt.registerTask('default', ['build','watch']);
}
