<?php
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class processor_vcs extends POSTprocessor
{
	public function info()
	{
		$info = array();
		$info['name']			= 'VCS';
		$info['longname']		= JText::_('CFG_VCS_LONGNAME');
		$info['statement']		= JText::_('CFG_VCS_STATEMENT');
		$info['description']	= JText::_('CFG_VCS_DESCRIPTION');
		$info['currencies']		= 'ZAR';
		$info['cc_list']		= 'visa,mastercard';
		$info['recurring']		= 2;
		$info['notify_trail_thanks']	= 1;

		return $info;
	}

	public function settings()
	{
		$settings = array();
		$settings['testmode'] 		= 1;
		$settings['merchant_id']	= '1234';
		$settings['secret']			= "";
		$settings['currency']		= 'ZAR';

		$settings['occur_count']	= 0;
		$settings['sms_send']		= 0;
		$settings['sms_number']		= "";
		$settings['sms_message']	= "";

		$settings['item_name']		= sprintf( JText::_('CFG_PROCESSOR_ITEM_NAME_DEFAULT'), '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']	= "";

		return $settings;
	}

	public function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array( 'toggle');
		$settings['merchant_id']		= array( 'inputC');
		$settings['currency']			= array( 'list_currency' );

		$settings['occur_count']		= array( 'inputA');
		$settings['sms_send']			= array( 'toggle' );
		$settings['sms_number']			= array( 'inputC');
		$settings['sms_message']		= array( 'inputC');

		$settings['secret']				= array( 'inputC');

		$settings['item_name']			= array( 'inputE');
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	public function createGatewayLink( $request )
	{
		$var['post_url']	= 'https://www.vcs.co.za/vvonline/ccform.asp';

		if ( $this->settings['testmode'] == '1' ) {
			$var['test_transaction'] = '100';
			$var['test_success_url'] = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=vcsnotification' );
		}

		$var['p1']	= $this->settings['merchant_id'];
		$var['p2']	= $request->invoice->invoice_number;
		$var['p3']	= date("Y.m.d.G.i.s");

		if ( is_array( $request->int_var['amount'] ) ) {
			$var['p4']		= $request->int_var['amount']['amount3'];
			$var['p5']		= $this->settings['currency'];

			if ( !empty( $this->settings['occur_count'] ) ) {
				$var['p6']	= $this->settings['occur_count'];
			} else {
				$var['p6']	= 'U';
			}

			$var['p7']		= $this->convertPeriodUnit( $request->int_var['amount']['period3'], $request->int_var['amount']['unit3'] );
		} else {
			$var['p4']		= $request->int_var['amount'];
			$var['p5']		= $this->settings['currency'];
		}

		if ( !empty( $this->settings['sms_send'] ) ) {
			$var['p8']	= AECToolbox::rewriteEngine( $this->settings['sms_number'], $request->metaUser, $request->new_subscription, $request->invoice );
			$var['p9']	= AECToolbox::rewriteEngine( $this->settings['sms_message'], $request->metaUser, $request->new_subscription, $request->invoice );
		}

		$var['m_1'] = $request->int_var['return_url'];
		$var['m_2'] = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['m_3'] = AECToolbox::deadsureURL( 'vcsnotification' );
		$var['m_4'] = AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=cancel' );
		$var['m_5'] = $request->metaUser->cmsUser->name;
		$var['m_6'] = $request->metaUser->cmsUser->email;
		$var['m_7'] = AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );

		$var['UrlsProvided'] = 'Y';

		$var['ApprovedUrl']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=vcsnotification' );
		$var['DeclinedUrl']	= AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=vcsnotification' );

		if ( !empty($this->settings['secret']) ) {
			$values = array(
				"p1", "p2", "p3", "p4",
				"p5", "p6", "p7", "p8",
				"p9", "p10", "p11", "p12",
				"NextOccurDate", 'Budget', 'CardholderEmail',
				"m_1", "m_2", "m_3", "m_4",
				"m_5", "m_6", "m_7", "m_8",
				"m_9", "m_10"
			);

			$string = '';
			foreach ( $values as $k ) {
				if ( isset($var[$k]) ) {
					$string .= $var[$k];
				}
			}

			$var['Hash'] = md5( $string . $this->settings['secret'] );
		}

		return $var;
	}

	public function convertPeriodUnit( $period, $unit )
	{
		$r = $period;

		switch ( $unit ) {
			case 'D':
				if ( $period < 7 ) {
					$r = $unit;
				} elseif ( ( $period >= 7 ) && ( $period < 30 ) ) {
					$r = 'W';
				} elseif ( ( $period >= 30 ) && ( $period < 365 ) ) {
					$r = 'M';
				} else {
					$r = 'Y';
				}

				break;
			case 'W':
				if ( $period < 4 ) {
					$r = $unit;
				} elseif ( ( $period >= 4 ) && ( $period < 12 ) ) {
					$r = 'M';
				} elseif ( ( $period >= 12 ) && ( $period < 24 ) ) {
					$r = 'Q';
				} elseif ( ( $period >= 24 ) && ( $period < 48 ) ) {
					$r = '6';
				} else {
					$r = 'Y';
				}

				break;
			case 'M':
				if ( $period < 3 ) {
					$r = $unit;
				} elseif ( ( $period >= 3 ) && ( $period < 6 ) ) {
					$r = 'Q';
				} elseif ( ( $period >= 6 ) && ( $period < 12 ) ) {
					$r = '6';
				} else {
					$r = 'Y';
				}

				break;
			case 'Y':
				$r = $unit;

				break;
		}

		return $r;
	}

	public function parseNotification( $post )
	{
		$p2 = explode('-', $post['p2']);

		$response = array();
		$response['invoice'] = $p2[0];
		$response['amount_paid'] = $post['p6'];

		return $response;
	}

	public function validateNotification( $response, $post, $invoice )
	{
		$response['valid'] = 0;

		if ( isset( $this->settings['secret'] ) ) {
			$values = array(
				"p1", "p2", "p3", "p4",
				"p5", "p6", "p7", "p8",
				"p9", "p10", "p11", "p12",
				"Pam",
				"m_1", "m_2", "m_3", "m_4",
				"m_5", "m_6", "m_7", "m_8",
				"m_9", "m_10",
				"CardHolderIpAddr",
				"MaskedCardNumber",
				"TransactionType"
			);

			$str = "";
			foreach ( $values as $k ) {
				$str .= $post[$k];
			}

			$str .= $this->settings['secret'];

			if ( $str == $post['Hash'] ) {
				$response['valid'] = 1;
			} else {
				$response['pending_reason'] = 'hash error';
			}
		} else {
			$response['pending_reason'] = 'no secret set - please configure either a secret in your VCS and AEC VCS settings!';
		}

		if ( $post['p4'] == 'Duplicate' ) {
			$response['duplicate'] = true;
		}

		if ( substr( $post['p3'], 6 ) !== 'APPROVED' ) {
			$response['valid'] = 0;
			$response['pending_reason'] = $post['p3'];
		}

		return $response;
	}

}
