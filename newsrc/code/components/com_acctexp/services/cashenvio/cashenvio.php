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


	}

	private function updateRates()
	{

	}
}
