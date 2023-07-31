<?php
/**
 * The original .ds engine by @anytizer
 *
 * fill random contents in a .ds body structure
 * Returns full keys of $instrument
 */
function get_random_instrument($instrument_data=[]): array
{
	$rr = new random_ranger();

	$instrument = $instrument_data;
	$instrument["raw_id"] = guid();
	$instrument["raw_category"] = "dsx64"; // $raw_category;
	$instrument["raw_name"] = $instrument["raw_id"].".ds";
	
	$instrument["general_author"] = ".ds randomizer";
	
	// do not change: host application will verify this
	// https://github.com/LMMS/lmms/blob/master/src/core/DrumSynth.cpp
	$instrument["general_version"] = "DrumSynth v2.0";
	
	$instrument["general_comments"] = "DrumSynth configurations generated randomly.";
	
	// $rr->ranger(-12, +24, 0.5);
	// -13, -7, 0, 12
	// mostly 0.00
	$instrument["general_tuning"] = $rr->ranger(-12.0, +24.0, 0.03, false);
	
	// -12 to 100, mostly 0
	$instrument["general_stretch"] = $rr->ranger(40, 250, 0.10, false); // %
	
	// -18 to 100
	// mostly 100
	$instrument["general_level"] = $rr->ranger(-20, +100, 1, true); // DGain = ..."Level",0
	$instrument["general_filter"] = $rr->onoff(); // 0, 1
	$instrument["general_gain"] = $rr->ranger(0, 100, 1, true);
	$instrument["general_highpass"] = $rr->onoff(); // checkbox
	$instrument["general_resonance"] = $rr->ranger(0, 100, 0.10, false); // mostly 0
	
	// $rr->ranger(44400, 444000, 100); // to be > 1000
	// $general_filterenv = $rr->pick_one_of([22100, 222000, 441000, 442000, 443000, 444000]);
	$general_filterenv = $rr->ranger(50000, 444000, 100, true);
	$instrument["general_filterenv"] = $rr->envelope($general_filterenv); // 444000
	
	$instrument["noiseband1_active"] = 0; // $rr->onoff();
	$instrument["noiseband1_level"] = $rr->ranger(20, 200, 1);
	$instrument["noiseband1_f"] = $rr->ranger(500, 64000, 10);
	$instrument["noiseband1_df"] = floor($instrument["noiseband1_f"]/mt_rand(5, 10)); //$rr->ranger(0, 100, 1);
	#$instrument["noiseband1_envelope"] = $rr->envelope($rr->ranger(1000, $general_filterenv, mt_rand(5, 40))); // $rr->envelope(mt_rand(1000, 80000));
	$instrument["noiseband1_envelope"] = $rr->envelope(floor($general_filterenv/(float)$rr->ranger(1.0, 8.0, 0.01, false)));

	$instrument["noiseband2_active"] = $rr->onoff();
	$instrument["noiseband2_level"] = $rr->ranger(50, 150, 1);
	$instrument["noiseband2_f"] = $rr->ranger(500, 9000, 10);
	$instrument["noiseband2_df"] = floor($instrument["noiseband2_f"]/(4+$rr->ranger(0, 6, 1, true))); // $rr->ranger(0, 100, 1);
	#$instrument["noiseband2_envelope"] = $rr->envelope($rr->ranger(200, 50000, 50));
	$instrument["noiseband2_envelope"] = $rr->envelope(floor($general_filterenv/(float)$rr->ranger(1.0, 8.0, 0.01, false)));; // $rr->envelope($general_filterenv);

	$instrument["noise_active"] = $rr->onoff();
	$instrument["noise_level"] = $rr->ranger(0, 128, 1); // sliLev[1] = GetPrivateProfileInt(sec,"Level",0,dsfile);
	$instrument["noise_slope"] = $rr->ranger(-100, +100, 1); // GetPrivateProfileInt(sec,"Slope",0,dsfile);
	$instrument["noise_envelope"] = $rr->envelope($general_filterenv); // mt_rand(200, 64000)
	$instrument["noise_fixedseq"] = $rr->onoff(); // checkbox

	$overtones_envelope1 = $rr->ranger(1000, 44000, 50);
	$overtones_envelope2 = floor($overtones_envelope1/(mt_rand(70, 200)/100));
	$instrument["overtones_active"] = 0; // $rr->onoff();
	$instrument["overtones_level"] = $rr->ranger(50, 200, 1);
	$instrument["overtones_f1"] = $rr->ranger(20, 1000, 5);
	$instrument["overtones_f2"] = floor($instrument["overtones_f1"]/$rr->ranger(0.8, 2.0, 0.1, false)); // 80% - 120%
	$instrument["overtones_method"] = $rr->ranger(0, 3, 1); // A+B, A.B, AxB, A...B
	$instrument["overtones_param"] = $rr->ranger(0, 100, 1);
	#$instrument["overtones_envelope1"] = $rr->envelope($overtones_envelope1);
	#$instrument["overtones_envelope2"] = $rr->envelope($overtones_envelope2);
	$instrument["overtones_envelope1"] = $rr->envelope(floor($general_filterenv/(float)$rr->ranger(1.0, 4.0, 0.01, false))); // $rr->envelope($general_filterenv);
	$instrument["overtones_envelope2"] = $rr->envelope(floor($general_filterenv/(float)$rr->ranger(1.0, 4.0, 0.01, false))); // $rr->envelope($general_filterenv);
	$instrument["overtones_wave1"] = $rr->ranger(0, 4, 1); // sin, sin^2, tri, saw, square
	$instrument["overtones_track1"] = $instrument["overtones_method"]==3?0:$rr->onoff(); // checkbox
	$instrument["overtones_wave2"] = $rr->ranger(0, 4, 1);
	$instrument["overtones_track2"] = $instrument["overtones_method"]==3?0:$rr->onoff(); // $rr->onoff(); // checkbox
	$instrument["overtones_filter"] = $rr->onoff(); // checkbox

	$instrument["tone_active"] = 1; // $rr->onoff();
	$instrument["tone_level"] = $rr->ranger(0, 150, 1); // default: 128
	$instrument["tone_f1"] = $rr->ranger(100, 9999, 10, true);
	$instrument["tone_f2"] =  floor($instrument["tone_f1"]/$rr->ranger(1, 10, 0.01, true)); // $rr->ranger(0, 1000, 0.10, false); // half? - tenth?
	$instrument["tone_droop"] = $rr->ranger(0, 100, 0.10, false);
	$instrument["tone_phase"] = $rr->ranger(-180, +180, 0.10, false); // (-180, +180, 1, true); // degrees
	$instrument["tone_envelope"] = $rr->envelope(floor($general_filterenv/(float)$rr->ranger(1.0, 4.0, 0.01, false)));
	// $rr->envelope($rr->ranger(1000, 44400, 250)); // 444000

	$instrument["distortion_active"] = $rr->onoff();
	$instrument["distortion_clipping"] = $rr->ranger(0, 60, 1, true); // https://github.com/LMMS/lmms/blob/master/src/core/DrumSynth.cpp#L437
	$instrument["distortion_bits"] = $rr->ranger(0, 7, 1, true);
	$instrument["distortion_rate"] = $rr->ranger(0, 7, 1, true);
	
	return $instrument;
}


/**
 * make .ds string body
 */
function ds($instrument=[]): string
{
	$ds="[General]
RawID={$instrument['raw_id']}
Author={$instrument['general_author']}
Version={$instrument['general_version']}
Comment={$instrument['general_comments']}
Tuning={$instrument['general_tuning']}
Stretch={$instrument['general_stretch']}
Level={$instrument['general_level']}
Filter={$instrument['general_filter']}
Gain={$instrument['general_gain']}
HighPass={$instrument['general_highpass']}
Resonance={$instrument['general_resonance']}
FilterEnv={$instrument['general_filterenv']}

[Tone]
On={$instrument['tone_active']}
Level={$instrument['tone_level']}
F1={$instrument['tone_f1']}
F2={$instrument['tone_f2']}
Droop={$instrument['tone_droop']}
Phase={$instrument['tone_phase']}
Envelope={$instrument['tone_envelope']}

[Noise]
On={$instrument['noise_active']}
Level={$instrument['noise_level']}
Slope={$instrument['noise_slope']}
Envelope={$instrument['noise_envelope']}

[Overtones]
On={$instrument['overtones_active']}
Level={$instrument['overtones_level']}
F1={$instrument['overtones_f1']}
Wave1={$instrument['overtones_wave1']}
Track1={$instrument['overtones_track1']}
F2={$instrument['overtones_f2']}
Wave2={$instrument['overtones_wave2']}
Track2={$instrument['overtones_track2']}
Filter={$instrument['overtones_filter']}
Method={$instrument['overtones_method']}
Param={$instrument['overtones_param']}
Envelope1={$instrument['overtones_envelope1']}
Envelope2={$instrument['overtones_envelope2']}

[NoiseBand]
On={$instrument['noiseband1_active']}
Level={$instrument['noiseband1_level']}
F={$instrument['noiseband1_f']}
dF={$instrument['noiseband1_df']}
Envelope={$instrument['noiseband1_envelope']}

[NoiseBand2]
On={$instrument['noiseband2_active']}
Level={$instrument['noiseband2_level']}
F={$instrument['noiseband2_f']}
dF={$instrument['noiseband2_df']}
Envelope={$instrument['noiseband2_envelope']}

[Distortion]
On={$instrument['distortion_active']}
Bits={$instrument['distortion_bits']}
Clipping={$instrument['distortion_clipping']}
Rate={$instrument['distortion_rate']}
";

	return $ds;
}
