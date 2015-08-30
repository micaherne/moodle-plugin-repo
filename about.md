---
layout: page
title:
permalink: /
---

This is an experimental [Composer](https://getcomposer.org) repository for [Moodle](https://moodle.org) and
its [plugin database](https://moodle.org/plugins/).

## Usage
_The following assumes fairly good knowledge of Composer: what it's for; how to create a composer.json file; how to install and update your dependencies._

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

The vendor is always "moodle-plugin-db", the package name is the component name from the Moodle plugin database, and the version is the 10-digit version build number from the plugin database.

### Core Moodle installation
The repository can also be used to get an existing Moodle release as the basis for an installation. Use the composer [create-project](https://getcomposer.org/doc/03-cli.md#create-project) command, for example:

    composer create-project --repository-url=http://micaherne.github.io/moodle-plugin-repo moodle/moodle outputdirname 2.3.4

## Core dependency management
*(Be warned - this is even more experimental than the previous repo!)*

There is a second repository at http://micaherne.github.io/moodle-plugin-repo/with-core-deps which declares dependencies on core Moodle versions for each plugin, based on the supported Moodle versions listed in the Moodle plugin database. Where a version is not specified in the require statement (as in 4 of the 5 in the example above), Composer will attempt to resolve the latest version of the plugin that supports your Moodle version.

Unfortunately, because Moodle's composer.json contains no name or version information by default (see [MDL-48114](https://tracker.moodle.org/browse/MDL-48114)), this must be manually entered into your composer.json:

    {
      "name": "moodle/moodle",
      "description": "Moodle",
      "version": "2.5.6"
    }
