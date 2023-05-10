1. Manual method
   1. Enable WP debugging
   2. Set up a site on a staging or local environment that's running PHP 8
   3. Test the functionality and inspect the logs for errors
   4. Fix
   5. Rinse, repeat
2. Automated method: [PHPCompatibility](https://github.com/PHPCompatibility/PHPCompatibility)
   1. Requires [Composer](https://getcomposer.org/)
   2. Requires [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
   3. Requires [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards) rules for PHPCS
      1. composer init
      2. composer require --dev dealerdirect/phpcodesniffer-composer-installer
      3. composer require --dev phpcompatibility/php-compatibility
      4. composer require -- dev wp-coding-standards/wpcs
      5. ./vendor/bin/phpcs -p . --standard=PHPCompatibility --ignore=*/vendor/* --runtime-set testVersion 8.0 --report-full=report.txt
   4. Pros
      1. Does not require a different PHP version
      2. Can scan your entire codebase
      3. Can be automated (git hooks, CI/CD, etc.)
   5. Cons
      1. Does not catch all issues (https://github.com/PHPCompatibility/PHPCompatibility/issues/808)
      2. Does require CLI familiarly