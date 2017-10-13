# Disclaimer

First off I'd like to say this not an ideal solution and should never be considered as an alternative to using composer via CLI.

We're in an awkward situtation where composer is becoming a hard requirement for Drupal. If all this project achieves is more awareness of the proplem and convinces composer naysayers that it's actually better to use composer CLI, I'd be happy.

However, I understand that there are some cases where composer via CLI might be technically impossible leaving some users behind and the only option would be to use another CMS. 

To be clear, it would still be better to address the educational / technical limitations.

Currently, this is just a proof of concept and is very insecure and should not be used on any externally accessible webserver.

It would be nice if this could be integrated into Drupal's existing authentication and only allow access to user 1 similar to `update.php`.

# What?

This project provides a way to run common composer commands via a browser.

# How?

It creates a simple endpoint that will run a command on the project codebase.

eg. `/composer/update/drupal/core/--/with-dependencies` would run `composer update drupal/core --with-dependencies`.

# limitations.

Due to memory limitations it is unlikely you'd be able to run `install` to install all dependencies, also an unfiltered `update` would be impossible.
However, if you include most packages in the tarball you don't have to install all packages and `require` or `update` on single packages are possible.

# Request structure.

/composer/[command]/[argument]/--/[options]

# Examples.

`/composer/validate` (default)

`/composer/show`

`/composer/require/drupal/devel/--/prefer-dist`

`/composer/install`

`/composer/install/--/no-dev`

`/composer/update/drupal/core/--/with-dependencies`
 