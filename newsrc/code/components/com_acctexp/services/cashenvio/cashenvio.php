<?php

namespace services\cashenvio;

class service_cashenvio extends aecService
{
	public function getInfo()
	{
		return array(
			'slug' => 'cashenvio',
			'name' => 'Cashenvio',
			'description' => 'Exposes currency conversion rates as pulled from the Cashenvio DolarToday webservice.'
		);
	}

	public function getSettings()
	{
		$settings = array();
		$settings['commission'] = array( 'toggle', 'Commission', 'Commission to add on top of conversation percentage.' );

		return $settings;
	}

	private function cmdConvert( $amount )
	{
		$rates = $this->getRates();

		$amount = (int) $amount;

		$amount = $amount/100;

		$percentage = $rates->USDVEF->rate;

		if ( !empty($this->params['commission']) ) {
			$percentage += $this->params['commission'];
		}

		return $amount * $percentage;
	}

	private function getRates()
	{
		return json_decode(
			file_get_contents('https://s3.amazonaws.com/dolartoday/data.json')
		);
	}
}
