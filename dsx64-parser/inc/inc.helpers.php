<?php
/**
 * Create a primary key ID
 */
function guid(): string
{
	mt_srand((int)microtime(false) * 10000);

	$rand = mt_rand(1000000, 9999999);
	$timestamp = date("HisYmd");
	$uniqid = uniqid(mt_rand(), true);

	$characters = strtoupper(md5($rand . $timestamp . $uniqid));
	$guid = preg_replace("/^([0-9A-F]{8})([0-9A-F]{4})([0-9A-F]{4})([0-9A-F]{4})([0-9A-F]{12})$/is", "$1-$2-$3-$4-$5", $characters);

	return $guid;
}
