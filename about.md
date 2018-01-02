---
layout: page
title:
permalink: /
---

This is an experimental [Composer](https://getcomposer.org) repository for [Moodle](https://moodle.org) and
its [plugin database](https://moodle.org/plugins/).

## Usage
_The following assumes fairly good knowledge of Composer: what it's for; how to create and manage a composer.json file; how to install and update your dependencies._

### Plugin installation
To manage Moodle plugins within a Moodle installation, add the repository to the composer.json file, in the "[repositories](https://getcomposer.org/doc/04-schema.md#repositories)" section.

    "repositories": [
        {
            "type": "composer",
            "url":  "http://micaherne.github.io/moodle-plugin-repo"
        }
    ]

You can now install plugins from the Moodle plugin database by adding them to the [require](https://getcomposer.org/doc/04-schema.md#require) section, for example:

    "require": {
        "moodle-plugin-db/mod_advmindmap": "*",
        "moodle-plugin-db/theme_aardvark": "*",
        "moodle-plugin-db/block_configurable_reports": "*",
        "moodle-plugin-db/block_faces": "2014052914",
        "moodle-plugin-db/mod_attendance": "*"
    }

The vendor is always "moodle-plugin-db", the package name is the component name from the Moodle plugin database, and the version is the 10-digit version build number from the plugin database. Note that this repository contains no dependency declarations for the Moodle plugins, so "*" will always install the latest version of the plugin, regardless of whether or not it is compatible with your base Moodle version.

### Core Moodle installation
The repository can also be used to get an existing Moodle release as the basis for an installation. Use the composer [create-project](https://getcomposer.org/doc/03-cli.md#create-project) command, for example:

    composer create-project --repository-url=http://micaherne.github.io/moodle-plugin-repo moodle/moodle outputdirname 2.3.4

## Core dependency management
*(Be warned - this is even more experimental than the previous repo!)*

There is a second repository at [http://micaherne.github.io/moodle-plugin-repo/with-core-deps](http://micaherne.github.io/moodle-plugin-repo/with-core-deps) which declares dependencies on core Moodle versions for each plugin, based on the supported Moodle versions listed in the Moodle plugin database. Where a version is not specified in the require statement (as in 4 of the 5 in the example above), Composer will attempt to resolve the latest version of the plugin that supports your Moodle version.

Unfortunately, because Moodle's composer.json contains no name or version information by default (see [MDL-48114](https://tracker.moodle.org/browse/MDL-48114)), this must be manually entered into your composer.json:

    {
      "name": "moodle/moodle",
      "description": "Moodle",
      "version": "2.5.6",
      "repositories":[
      {
        "type": "composer",
        "url":  "http://micaherne.github.io/moodle-plugin-repo/with-core-deps/"
      }
      ],
      "require":{
        "moodle-plugin-db/mod_certificate":"*"
      }
    }

Ensure the name is completely correct, otherwise you will end up with a second copy of Moodle in your vendor directory!

## Troubleshooting

### My plugin installs under the vendor directory instead of in the correct plugin location
This repository depends on the [composer/installers](https://github.com/composer/installers) Moodle helper to put the code in the correct place. Unfortunately, this does not currently support all the Moodle core plugin types, in particular atto plugins, which feature quite prominently in the plugins database. I have submitted a [pull request](https://github.com/composer/installers/pull/260) to add these but until this ends up in a released version it may be necessary to use the patched version.

Add the forked repository to your composer.json:

    "repositories": [
        {
          "type": "vcs",
          "url":  "https://github.com/micaherne/installers.git"
        }
    ]

and require the branch with all the plugin types:

    "require": {
        "composer/installers": "dev-add-moodle-types"
    }

This also may need "minimum-stability" to be set to "dev".

### My plugin installs in the correct place but contains a nested subdirectory of the same name
This seems to happen when the download zip file from the Moodle plugin repository contains multiple root folders. I have only ever seen this with some Mac-specific \_\_MACOSX folders, and am not aware of any workaround.

## Issues

There are a number of problems with the current repository:

1. The version numbers in the Moodle plugin database aren't in the  semantic versioning format usually used by composer, so you can install specific versions (also "*" for the latest works as expected) but the version ranges syntax doesn't really work. As far as I can see - although I haven't confirmed this so it's more of a guess than anything - Composer interprets each build number as a major version (for example 2015083001 is treated as 2015083001.0.0). The only other version data held by the plugin database is "release", but this seems to be free text, so can't really be used.

2. Author information isn't exposed by the plugin database API, so we can't attribute the plugins properly in the generated metadata.

3. Where a plugin has a Github repository which could be used by Composer, we're not using it. Worse, when a plugin has a composer.json file we're ignoring it. This could conceivably be fixed by checking the source control URL for each plugin for a composer.json file in the correct format (including the data for composer/installers) but this may not be easy or fast. You could argue that if the plugin does have a good composer.json it should just be added to packagist and installed from there, and we don't need to do anything more.

4. To install into an existing Moodle download (which is kind of the idea), you need to edit the composer.json file, which is a core Moodle file. Alternatively, you need to mess around with the COMPOSER environment variable to use a different filename.

5. There's no actual dependency management, so you have to work out the relevant versions yourself. In the extra-experimental repo we add requires for Moodle versions but, because the Moodle composer.json doesn't have a name or version, this has to be added manually before running composer install otherwise you'd end up with a whole copy of Moodle in your vendor directory.

6. We can't check the MD5 of the download to ensure its integrity.

7. The plugin database doesn't appear to have explicit license information, so we can't expose it in the repo.

Also, it's important to note that this repository was generated from the plugin database at a specific point in time, and won't necessarily (ever) be updated. If this proof-of-concept turns out to be a useful and/or popular idea, I'd hope HQ could be convinced to build the repository into the Moodle plugins database itself.
