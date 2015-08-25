# moodle-plugin-repo
Experimental script to generate composer repository for Moodle plugin directory.

The idea of this is to generate a packages.json file in the composer repository format (https://getcomposer.org/doc/05-repositories.md#composer) which can be used to install Moodle plugins using Composer (see also https://moodle.org/mod/forum/discuss.php?d=275466).

The plugins are exposed as moodle-plugin-db/plugin_name, and the versions are the full 10-digit version number.

## Example composer.json

    {
        "name": "michael/mdb-test",
        "repositories": [
          {
            "type": "git",
            "url": "https://github.com/micaherne/moodle-plugin-repo-installer.git"
          },
          {
            "type": "composer",
            "url": "http://micaherne.github.io/moodle-plugin-repo/"
          },
          {
            "packagist": false
          }
        ],
        "minimum-stability": "dev",
        "require": {
            "moodle-plugin-db/theme_aardvark": "2013080900",
            "moodle-plugin-db/atto_morebackcolors": "*",
            "moodle-plugin-db/mod_attendance": "*"
        }
    }

## Issues

1. The version numbers aren't in the usual semantic versioning format usually used by composer, so you can install specific versions (also "*" for the latest works as expected) but the version ranges syntax doesn't really work.
2. Author information isn't exposed by the plugin database API, so we can't attribute the plugins properly in the generated metadata
3. Where a plugin has a Github repository which could be used by Composer, we're not using it
4. We have to use a custom installer to get the plugin into the right directory (which is one of the reasons #3 doesn't work)
5. To install into an existing Moodle download (which is kind of the idea), you need to edit the composer.json file, which is a core Moodle file. Alternatively, you need to mess around with the COMPOSER environment variable to use a different filename
6. There's no actual dependency management, so you have to work out the relevant versions yourself
