---
layout: page
title: Issues
permalink: /issues/
---

There are a number of problems with the current repository:

1. The version numbers in the Moodle plugin database aren't in the  semantic versioning format usually used by composer, so you can install specific versions (also "*" for the latest works as expected) but the version ranges syntax doesn't really work. As far as I can see - although I haven't confirmed this so it's more of a guess than anything - Composer interprets each build number as a major version (for example 2015083001 is treated as 2015083001.0.0). The only other version data held by the plugin database is "release", but this seems to be free text, so can't really be used.

2. Author information isn't exposed by the plugin database API, so we can't attribute the plugins properly in the generated metadata.

3. Where a plugin has a Github repository which could be used by Composer, we're not using it. Worse, when a plugin has a composer.json file we're ignoring it. This could conceivably be fixed by checking the source control URL for each plugin for a composer.json file in the correct format (including the data for composer/installers) but this may not be easy or fast. You could argue that if the plugin does have a good composer.json it should just be added to packagist and installed from there, and we don't need to do anything more.

4. To install into an existing Moodle download (which is kind of the idea), you need to edit the composer.json file, which is a core Moodle file. Alternatively, you need to mess around with the COMPOSER environment variable to use a different filename.

5. There's no actual dependency management, so you have to work out the relevant versions yourself. In the extra-experimental repo we add requires for Moodle versions but, because the Moodle composer.json doesn't have a name or version, this has to be added manually before running composer install otherwise you'd end up with a whole copy of Moodle in your vendor directory.

6. We can't check the MD5 of the download to ensure its integrity.

7. The plugin database doesn't appear to have explicit license information, so we can't expose it in the repo.

Also, it's important to note that this repository was generated from the plugin database at a specific point in time, and won't necessarily (ever) be updated. If this proof-of-concept turns out to be a useful and/or popular idea, I'd hope HQ could be convinced to build the repository into the Moodle plugins database itself.
