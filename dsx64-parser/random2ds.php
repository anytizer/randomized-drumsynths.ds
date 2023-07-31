<?php
# randomly create .ds files
# target inside LMMS folder or, mklink them there

require_once("inc/inc.config.php");

if(!is_dir($drumsynth_random_folder))
{
	mkdir($drumsynth_random_folder, 0777, true);
}

/**
 * remove residual files from old lot.
 */
$existing = glob("{$drumsynth_random_folder}/*.ds");
foreach($existing as $existing_ds_file)
{
	unlink($existing_ds_file);
}

$total = 20;
for($i=0; $i<$total; ++$i)
{
	$instrument = get_random_instrument($instrument_data);
	$ds = ds($instrument);

	$output_file = "{$drumsynth_random_folder}/{$instrument['raw_id']}.ds";
	file_put_contents($output_file, $ds);
}

echo "Wrote a total of {$total} .ds file(s)!";
