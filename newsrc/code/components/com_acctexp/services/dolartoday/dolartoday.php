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
		if ( is_array($request) ) {
			$amount = $request['amount'];

			$mode = $request['mode'];
		} else {
			$amount = $request;

			$mode = 'USD';
		}

		$rates = $this->getRates();

		if ( !property_exists($rates, $mode) ) {
			$mode = 'USD';
		}

		$amount = (int) $amount;

		$amount = $amount/100;

		$percentage = $rates->$mode->dolartoday;

		$amount = $amount * $percentage;

		if ( !empty($this->params['multiplier']) ) {
			$amount = $amount * $this->params['multiplier'];
		}

		return round($amount, 2);
	}

	private function getRates()
	{
		if (
			isset($this->data->timestamp)
			&& !empty($this->data->cache)
			&& is_object($this->data->cache)
			&& !empty($this->params['cache_age'])
		) {
			if ( (time() - $this->data->timestamp) > ($this->params['cache_age']*60) ) {
				return $this->data->cache;
			}
		}

		$url = 'https://s3.amazonaws.com/dolartoday/data.json';

		$data = utf8_encode( file_get_contents($url) );

		$data = json_decode($data);

		if ( !empty($data) ) {
			$this->data->timestamp = time();
			$this->data->cache = $data;

			$this->storeload();
		}

		return $data;
	}
}
