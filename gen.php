<?php

$api = "https://download.moodle.org/api/1.3/pluglist.php";
$corebase = "https://download.moodle.org/download.php/direct";
$localapi = __DIR__ . '/pluglist.json';
$packagesfile = __DIR__ . '/packages.json';

$addcorerequires = in_array('--add-core-requires', $argv);

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
		if ($addcorerequires) {
    		$supportedmoodles = [];
    		foreach ($version->supportedmoodles as $supportedmoodle) {
    		    $supportedmoodles[] = $supportedmoodle->release . '.*';
    		}
    		$versionpackage->require['moodle-plugin-db/moodle'] = implode('||', $supportedmoodles);
		}
    	$packageversions[$version->version] = $versionpackage;
	}
	$repo->packages[$package->name] = $packageversions;
}

$coremaxversions = [
    '2.9' => 1,
    '2.8' => 7,
    '2.7' => 9,
    '2.6' => 11,
    '2.5' => 9,
    '2.4' => 11,
    '2.3' => 11,
    '2.2' => 11,
    '2.1' => 10,
    '2.0' => 10,
    '1.9' => 19,
    '1.8' => 14,
    '1.7' => 7,
    '1.6' => 9
];

$package = new stdClass();
$package->name = 'moodle-plugin-db/moodle';
$packageversions = [];
foreach ($coremaxversions as $major => $max) {
    for ($i = $max; $i >= 0; $i--) {
        $versionno = $major . '.' . $i;
        $directory = 'stable'. str_replace('.', '', $major);
        if ($i == '0') {
            // the 0 is not part of the filename
            $filename = "moodle-$major.zip";
        } else {
            $filename = "moodle-$versionno.zip";
        }
        $versionpackage = clone($package);
        $versionpackage->version = $versionno;
        $versionpackage->type = 'project';
        $dist = new stdClass();
        $dist->url = $corebase . "/$directory/$filename";
        $dist->type = 'zip';
        $versionpackage->dist = $dist;
        $versionpackage->require = ['moodle-plugin-db/installer' => '*'];
        $packageversions[$versionno] = $versionpackage;
    }
}
$repo->packages[$package->name] = $packageversions;

file_put_contents($packagesfile, json_encode($repo));
