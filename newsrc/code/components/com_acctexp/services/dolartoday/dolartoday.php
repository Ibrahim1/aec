<?php

class service_dolartoday extends aecService
{
	public function getInfo()
	{
		return array(
			'slug' => 'dolartoday',
			'name' => 'dolartoday',
			'description' => 'Exposes currency conversion rates as pulled from the dolartoday.com webservice.'
		);
	}

	public function getSettings()
	{
		$settings = array();
		$settings['multiplier'] = array( 'inputB', 'Multiplier', 'Change the conversion by this multiplier (for instance if you want to add a commission).' );
		$settings['cache_age'] = array( 'inputB', 'Cache Age', 'Number of minutes that you want to keep a cache of the conversion list' );

		return $settings;
	}

	protected function cmdConvert( $request )
	{
		if ( is_object($request) ) {
			$amount = $request->amount;

			$mode = $request->mode;
		} else {
			$amount = $request;

			$mode = 'USDVEF';
		}

		$rates = $this->getRates();

		if ( !property_exists($rates, $mode) ) {
			$mode = 'USD';
		}

		$amount = (int) $amount;

		$amount = $amount/100;

		$percentage = $rates->$mode->dolartoday;

		$amount *= $percentage;

		if ( !empty($this->params['multiplier']) ) {
			$amount *= $this->params['multiplier'];
		}

		return round($amount, 2);
	}

	private function getRates()
	{
		if (
			isset($this->data->timestamp)
			&& !empty($this->data->cache)
			&& !empty($this->params['cache_age'])
		) {
			if ( (time() - $this->data->timestamp) > ($this->params['cache_age']*60) ) {
				return $this->data->cache;
			}
		}

		$data = json_decode(
			file_get_contents('https://s3.amazonaws.com/dolartoday/data.json')
		);

		if ( !empty($data) ) {
			$this->data->timestamp = time();
			$this->data->cache = $data;

			$this->storeload();
		}

		return $data;
	}
}
