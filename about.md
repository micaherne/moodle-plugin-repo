---
layout: page
title:
permalink: /
---

This is an experimental [Composer](https://getcomposer.org) repository for [Moodle](https://moodle.org) and
its [plugin database](https://moodle.org/plugins/).

## Usage

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
