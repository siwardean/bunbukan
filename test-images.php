<?php
/**
 * Image Diagnostic Test
 * Visit: http://bunbukan.local/wp-content/themes/bunbukan/test-images.php
 */

// Load WordPress
require_once('../../../../../wp-load.php');

echo '<h1>Bunbukan Theme - Image Diagnostic</h1>';
echo '<style>body{font-family:monospace;padding:20px;} .exists{color:green;} .missing{color:red;}</style>';

$theme_dir = get_template_directory();
$theme_uri = get_template_directory_uri();

echo "<h2>Theme Paths</h2>";
echo "<p><strong>Theme Directory:</strong> $theme_dir</p>";
echo "<p><strong>Theme URI:</strong> $theme_uri</p>";
echo "<p><strong>Site URL:</strong> " . site_url() . "</p>";

echo "<h2>Image Files Check</h2>";

$images_to_check = [
	'Hero Background (Shuri Castle)' => '/assets/images/shuri-castle.gif',
	'Main Logo' => '/assets/images/logos/budo-club-berchem-ico-3.png',
	'Bunbukan JPG' => '/assets/images/bunbukan.jpg',
	'Bunbukan JFIF' => '/assets/images/bunbukan.jfif',
	'Okinawa Gate (old)' => '/assets/images/okinawa-gate.jpg',
	'Favicon' => '/assets/images/favicon.png',
	'Instructors' => '/assets/images/instructors.jpg',
	'Karate' => '/assets/images/karate.jpg',
	'Kobudo' => '/assets/images/kobudo.jpg',
	'Dojo' => '/assets/images/dojo.jpg',
	'Instructor Alain' => '/assets/images/instructor-alain.jpg',
];

echo '</table>';

echo "<h2>Affiliation Logos</h2>";

$affiliation_logos = [
	'Budo Club Berchem' => '/assets/images/affiliations/budo-club-berchem-ico-3.png',
	'VKA' => '/assets/images/affiliations/vka-ico.png',
	'Shitokai Belgium' => '/assets/images/affiliations/shitokai-ico-2.png',
	'Dento Shito-ryu' => '/assets/images/affiliations/dento-shito-ryu-ico-8.png',
	'Ono-ha Itto-ryu' => '/assets/images/affiliations/ono-ha-itto-ryu-ico-7.png',
	'Hontai Yoshin-ryu' => '/assets/images/affiliations/Hontai-Yoshin-ryu-Ju-Jutsu-belgium-ico.jpg',
	'Sport Brussel' => '/assets/images/affiliations/sport-brussel-ico-4.png',
];

echo '<table border="1" cellpadding="10">';
echo '<tr><th>Organization</th><th>Status</th><th>Path</th><th>Preview</th></tr>';

foreach ($affiliation_logos as $name => $path) {
	$full_path = $theme_dir . $path;
	$full_url = $theme_uri . $path;
	$exists = file_exists($full_path);
	
	$status_class = $exists ? 'exists' : 'missing';
	$status_text = $exists ? '✅ EXISTS' : '❌ MISSING';
	
	echo '<tr>';
	echo "<td>$name</td>";
	echo "<td class='$status_class'>$status_text</td>";
	echo "<td><small>$path</small></td>";
	echo "<td>";
	if ($exists) {
		echo "<img src='$full_url' style='max-width:100px;max-height:100px;background:white;padding:5px;' />";
	} else {
		echo "N/A";
	}
	echo "</td>";
	echo '</tr>';
}


echo '<table border="1" cellpadding="10">';
echo '<tr><th>Image</th><th>Status</th><th>Path</th><th>Preview</th></tr>';

foreach ($images_to_check as $name => $path) {
	$full_path = $theme_dir . $path;
	$full_url = $theme_uri . $path;
	$exists = file_exists($full_path);
	
	$status_class = $exists ? 'exists' : 'missing';
	$status_text = $exists ? '✅ EXISTS' : '❌ MISSING';
	
	echo '<tr>';
	echo "<td>$name</td>";
	echo "<td class='$status_class'>$status_text</td>";
	echo "<td><small>$path</small></td>";
	echo "<td>";
	if ($exists) {
		echo "<img src='$full_url' style='max-width:100px;max-height:100px;' />";
	} else {
		echo "N/A";
	}
	echo "</td>";
	echo '</tr>';
}

echo '</table>';

echo "<h2>bunbukan_asset_url() Function Test</h2>";

if (function_exists('bunbukan_asset_url')) {
	echo "<p class='exists'>✅ Function exists</p>";
	
	$test_paths = [
		'Main Logo' => ['/assets/images/logos/budo-club-berchem-ico-3.png', ''],
		'Hero BG (Shuri Castle)' => ['/assets/images/shuri-castle.gif', ''],
		'Affiliation - Budo Club' => ['/assets/images/affiliations/budo-club-berchem-ico-3.png', '/bunbukan-eu/public/affiliations/budo-club-berchem-ico-3.png'],
		'Missing Instructors' => ['/assets/images/instructors.jpg', '/bunbukan-eu/public/images/budo_club_stage_nakamoto.jpg'],
	];
	
	echo '<table border="1" cellpadding="10">';
	echo '<tr><th>Test</th><th>Result URL</th><th>Works?</th></tr>';
	
	foreach ($test_paths as $name => $paths) {
		$result = bunbukan_asset_url($paths[0], $paths[1]);
		$works = !empty($result) ? 'YES' : 'NO';
		$class = !empty($result) ? 'exists' : 'missing';
		
		echo '<tr>';
		echo "<td>$name</td>";
		echo "<td><small>" . ($result ?: 'EMPTY') . "</small></td>";
		echo "<td class='$class'>$works</td>";
		echo '</tr>';
	}
	
	echo '</table>';
} else {
	echo "<p class='missing'>❌ Function does not exist!</p>";
}

echo "<h2>WordPress Theme Active?</h2>";
$current_theme = wp_get_theme();
echo "<p>Current Theme: <strong>" . $current_theme->get('Name') . "</strong></p>";
echo "<p>Theme Version: " . $current_theme->get('Version') . "</p>";
