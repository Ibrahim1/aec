<?php

namespace services\cashenvio;

class service_cashenvio extends aecService
{
	public function Settings()
	{
		$settings = array();
		$settings['commission'] = array( 'toggle', 'Commission', 'Commission to add on top of conversation percentage.' );

		return $settings;
	}

	private function cmdConvert( $amount )
	{
		$amount = (int) $amount;

		$amount = $amount/100;

		$percentage =

		return
	}

	private function getRates()
	{
		return json_decode(
			file_get_contents('https://s3.amazonaws.com/dolartoday/data.json')
		);
	}
}
