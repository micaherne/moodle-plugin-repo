<?php

$api = "https://download.moodle.org/api/1.3/pluglist.php";
$localapi = __DIR__ . '/pluglist.json';
$packagesfile = __DIR__ . '/packages.json';

if (!file_exists($localapi)) {
	$pluginlistjson = file_get_contents($api);
	file_put_contents($localapi, $pluginlistjson);
} else {
	$pluginlistjson = file_get_contents($localapi);
}

if (!$pluginlist = json_decode($pluginlistjson)) {
	die("Unable to read plugin list");
}

$repo = new stdClass();
$repo->packages = [];

foreach ($pluginlist->plugins as $key => $plugin) {
	$package = new stdClass();
	$package->name = 'moodle-plugin-db/' . $plugin->component;
	$package->description = $plugin->name;
	$packageversions = [];
	foreach ($plugin->versions as $version) {
		$versionpackage = clone($package);
		$versionpackage->version = $version->version;
		$versionpackage->type = 'moodle-plugin';
		$dist = new stdClass();
		$dist->url = $version->downloadurl;
		$dist->type = 'zip';
		$versionpackage->dist = $dist;
		$versionpackage->require = ['moodle-plugin-db/installer' => '*'];
		$packageversions[$version->version] = $versionpackage;
	}
	$repo->packages[$package->name] = $packageversions;
}

file_put_contents($packagesfile, json_encode($repo));
