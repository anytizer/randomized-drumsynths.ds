<?php
/**
 * Where are the original .ds files located at?
 * These files are generally within LMMS's data/samples/drumsynth/ folder
 */
$drumsynth_folder = "D:/Program Files/LMMS/data/samples/drumsynth";

/**
 * Where to save the [DB]/<GUID()>.ds files?
 */
$drumsynth_db2ds_folder = "D:/Program Files/LMMS/data/samples/drumsynth-dsx64";

/**
 * Where to write randomly generated .ds files?
 */
$drumsynth_random_folder = "D:/Program Files/LMMS/data/samples/dsx64-ds-randoms";

/**
 * Functions or data stem only, do not modify
 */
require_once("inc.helpers.php");
require_once("inc.instrument_data.php");
require_once("inc.random.php");

#require_once("inc.ds.php"); # most useful
require_once("inc.ds-short.php"); # trial copy
