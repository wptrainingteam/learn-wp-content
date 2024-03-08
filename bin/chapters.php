<?php
// Auto generate YouTube chapters from Markdown headings

// loop through all files in the current directory with the .md extension
foreach (glob('*.md') as $file) {
	$headings = [];
	// read the contents of the markdown file
	$contents = file_get_contents($file);
	// loop through each line of the file
	foreach (explode("\n", $contents) as $line) {
		// if the line starts with a hash, it's a heading
		if (substr($line, 0, 1) === '#') {
			// only work with lines that have only 2 hashes in them
			if (substr_count($line, '#') !== 2) {
				continue;
			}
			// remove all hashes and any leading/trailing whitespace
			$line = trim(str_replace('#', '', $line));
			// store the heading in the headings array
			$headings[] = '0:00 ' . $line;
		}
	}
	// write the headings to the bottom of the markdown file, with a heading of "YouTube chapters"
	file_put_contents($file, $contents . "\n\n## YouTube chapters\n\n" . implode("\n", $headings));
}