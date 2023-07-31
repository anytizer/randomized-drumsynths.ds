<?php
function make_seed(): float
{
  list($usec, $sec) = explode(' ', microtime());
  return $sec + $usec * 1000000;
}

class random_ranger
{
	public function pick_one_of($array=[])
	{
		mt_srand(make_seed(), MT_RAND_MT19937);

		$item = $array[mt_rand(0, count($array)-1)];
		return $item;
	}

	public function ranger($from, $upto, $steps=1, $integer_only=true)
	{
		assert($from <= $upto);
		
		$array = range($from, $upto, $steps);
		
		$formatting_string = $integer_only==true?"%d":"%01.2f";		
		foreach($array as $a => $r)
		{
			$array[$a] = sprintf($formatting_string, $r);
		}

		$random = $this->pick_one_of($array);
		return $random;
	}

	/**
	 * returns 0 or 1, not boolean but integer
	 */
	public function onoff()
	{
		#return "1"; // 0, 1
		return $this->pick_one_of([1, 0]);
	}
	
	/**
	 * Build data for "envelope"
	 */
	public function envelope($max=444000): string
	{
		$max = (int)$max; // validation
		
		$initial_amplitude = $this->ranger(20, 100, 5, true);
		$data = [
			// start with maximum value
			"0,{$initial_amplitude}",
		];
		
		// https://github.com/LMMS/lmms/blob/master/src/core/DrumSynth.cpp#L75
		$steps = mt_rand(2, 6); // how many 1+envelopes points?
		$per_time = floor($max/$steps);
		
		$runner = 0; // min($times);
		for($s=1; $s<=$steps; ++$s)
		{
			$amplitude = mt_rand(0, 100);
			
			$runner += $per_time;
			$data[] = "{$runner},{$amplitude}";
		}


		// @todo something glitchy here
		if($runner <= $max)
		{
			// when there were perfect divisions,
			// overwrite the last amplitude to 0 value
			// it also corrects the .ds files (without having repeated ending points)
			$data[count($data)-1] = "{$max},0";
		}
		else
		{
			$data[] = "{$max},0";
		}

		return implode(" ", $data); // 0,100 ... 444000,0
	}
}