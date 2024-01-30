'use strict';
module.exports = function(grunt) {
    var pkg = grunt.file.readJSON('package.json');

    grunt.initConfig({

        addtextdomain: {
            options: {
                textdomain: 'product-addon-custom-field',
            },
            update_all_domains: {
                options: {
                    updateDomains: true
                },
                src: [ '*.php', '**/*.php', '!node_modules/**', '!php-tests/**', '!bin/**', '!build/**', '!assets/**' ]
            }
        },

        // Generate POT files.
        makepot: {
            target: {
                options: {
                    exclude: ['build/.*', 'node_modules/*', 'assets/*'],
                    mainFile: 'main.php',
                    domainPath: '/languages/',
                    potFilename: 'product-addon-custom-field.pot',
                    type: 'wp-plugin',
                    updateTimestamp: true,
                    potHeaders: {
                        'language-team': 'LANGUAGE <EMAIL@ADDRESS>',
                        poedit: true,
                        'x-poedit-keywordslist': true
                    }
                }
            }
        },

        // Clean up build directory
        clean: {
            main: ['build/']
        },

        // Copy the plugin into the build directory
        copy: {
            main: {
                src: [
                    '**',
                    '!vendor/**',
                    '!node_modules/**',
                    '!build/**',
                    '!src/**',
                    '!.git/**',
                    '!Gruntfile.js',
                    '!package.json',
                    '!phpunit.xml',
                    '!.gitignore',
                    '!.gitmodules',
                    '!npm-debug.log',
                    '!**/Gruntfile.js',
                    '!**/package.json',
                    '!**/package-lock.json',
                    '!**/composer.json',
                    '!**/composer.lock',
                    '!**/phpcs-report.txt',
                    '!**/phpcs.xml.dist',
                    '!**/webpack.config.js'
                ],
                dest: 'build/'
            }
        },

        //Compress build directory into <name>.zip and <name>-<version>.zip
        compress: {
            main: {
                options: {
                    mode: 'zip',
                    archive: './build/product-addon-custom-field-v'+pkg.version+'.zip'
                },
                expand: true,
                cwd: 'build/',
                src: ['**/*'],
                dest: 'product-addon-custom-field'
            }
        },
    });

    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-contrib-clean' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-compress' );
    grunt.loadNpmTasks( 'grunt-wp-i18n' );
        // file auto generation
    grunt.registerTask( 'i18n', [ 'makepot' ] );
    grunt.registerTask( 'readme', [ 'wp_readme_to_markdown' ] );

    grunt.registerTask( 'release', [ 'i18n' ] );
    grunt.registerTask( 'zip', [ 'clean', 'copy', 'compress' ] );
};