module.exports = function(grunt) {
	grunt.initConfig({
		concat: {
			options: {
				separator: ';'
			},
			dist: {
				src: [
					// preserve the correct order
					'src/js/src/jquery/*.js',
					'src/js/src/yii/*.js',
					'src/js/src/bootstrap/*.js',
					'src/js/src/twitter-typeahead/*.js',
					'src/js/src/*.js'
				],
				dest: 'src/js/xbmc-video-server.js'
			}
		},
		less: {
			development: {
				options: {
					compress: true,
					yuicompress: true,
					optimization: 2
				},
				files: {
					"src/css/styles-min.css": "src/css/less/styles.less",
					"src/css/login-min.css": "src/css/less/login.less"
				}
			}
		},
		uglify: {
			scripts: {
				files: {
					'src/js/xbmc-video-server.min.js': ['src/js/xbmc-video-server.js']
				}
			}
		},
		watch: {
			styles: {
				files: ['src/css/less/**/*.less'], // which files to watch
				tasks: ['less'],
				options: {
					nospawn: true
				}
			},
			scripts: {
				files: ['src/js/src/**/*.js'],
				tasks: ['concat', 'uglify'],
				options: {
					nospwan: true
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('default', ['watch']);
	grunt.registerTask('dist', ['less', 'concat', 'uglify'])
};
