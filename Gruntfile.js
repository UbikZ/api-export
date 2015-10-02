"use strict";

var originCSS = 'web/static/css/*.css',
  originJS = 'web/static/js/**/*.js';

module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    bower_concat: {
      all: {
        dest: 'web/static/dist/js/vendor.js',
        cssDest: 'web/static/dist/css/vendor.css'
      }
    },
    uglify: {
      dist: {
        files: {
          'web/static/dist/js/application.min.js': ['web/static/dist/js/application.js'],
          'web/static/dist/js/vendor.min.js': ['web/static/dist/js/vendor.js']
        }
      }
    },
    cssmin: {
      options: {
        shorthandCompacting: false,
        roundingPrecision: -1
      },
      target: {
        files: {
          'web/static/dist/css/application.min.css': ['web/static/dist/css/application.css'],
          'web/static/dist/css/vendor.min.css': ['web/static/dist/css/vendor.css']
        }
      }
    },
    copy: {
      dist: {
        files: [{
          expand: true,
          dot: true,
          cwd: 'web/static/vendor/components-font-awesome/fonts',
          src: ['*.*'],
          dest: 'web/static/dist/fonts'
        }]
      }
    }
  });

  // Module static configuration (before concat)
  grunt.registerTask("prepareModules", "Finds and prepares modules for concatenation.", function() {
    var concat = grunt.config.get('concat') || {};

    // other stuffs

    concat['js'] = {
      src: [originJS],
      dest: 'web/static/dist/js/application.js'
    };

    concat['css'] = {
      src: [originCSS],
      dest: 'web/static/dist/css/application.css'
    };

    grunt.config.set('concat', concat);
  });

  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-bower-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-copy');

  // Default task(s).
  grunt.registerTask('default', ['copy', 'bower_concat', 'prepareModules', 'concat', 'uglify', 'cssmin']);
};