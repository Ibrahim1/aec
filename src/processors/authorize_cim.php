<?php
/**
 * @version $Id: authorize_cim.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Authorize CIM
 * @copyright 2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

require( 'authorizenet_cim/authorizenet.cim.class.php' );

class processor_authorize_cim extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']			= 'authorize_cim';
		$info['longname']		= _CFG_AUTHORIZE_CIM_LONGNAME;
		$info['statement']		= _CFG_AUTHORIZE_CIM_STATEMENT;
		$info['description']	= _CFG_AUTHORIZE_CIM_DESCRIPTION;
		$info['currencies']		= 'AFA,DZD,ADP,ARS,AMD,AWG,AUD,AZM,BSD,BHD,THB,PAB,BBD,BYB,BEF,BZD,BMD,VEB,BOB,BRL,BND,BGN,BIF,CAD,CVE,KYD,GHC,XOF,XAF,XPF,CLP,COP,KMF,BAM,NIO,CRC,CUP,CYP,CZK,GMD,'.
								'DKK,MKD,DEM,AED,DJF,STD,DOP,VND,GRD,XCD,EGP,SVC,ETB,EUR,FKP,FJD,HUF,CDF,FRF,GIP,XAU,HTG,PYG,GNF,GWP,GYD,HKD,UAH,ISK,INR,IRR,IQD,IEP,ITL,JMD,JOD,KES,PGK,LAK,EEK,'.
								'HRK,KWD,MWK,ZMK,AOR,MMK,GEL,LVL,LBP,ALL,HNL,SLL,ROL,BGL,LRD,LYD,SZL,LTL,LSL,LUF,MGF,MYR,MTL,TMM,FIM,MUR,MZM,MXN,MXV,MDL,MAD,BOV,NGN,ERN,NAD,NPR,ANG,NLG,YUM,ILS,'.
								'AON,TWD,ZRN,NZD,BTN,KPW,NOK,PEN,MRO,TOP,PKR,XPD,MOP,UYU,PHP,XPT,PTE,GBP,BWP,QAR,GTQ,ZAL,ZAR,OMR,KHR,MVR,IDR,RUB,RUR,RWF,SAR,ATS,SCR,XAG,SGD,SKK,SBD,KGS,SOS,ESP,'.
								'LKR,SHP,ECS,SDD,SRG,SEK,CHF,SYP,TJR,BDT,WST,TZS,KZT,TPE,SIT,TTD,MNT,TND,TRL,UGX,ECV,CLF,USN,USS,USD,UZS,VUV,KRW,YER,JPY,CNY,ZWD,PLN';
		$info['cc_list']		= "visa,mastercard,discover,americanexpress,echeck,jcb,dinersclub";
		$info['recurring']		= 2;
		$info['actions']		= array('cancel');
		$info['secure']			= 1;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['login']				= 'login';
		$settings['transaction_key']	= 'transaction_key';
		$settings['testmode']			= 0;
		$settings['currency']			= 'USD';
		$settings['promptAddress']		= 0;
		$settings['promptZipOnly']		= 0;
		$settings['dedicatedShipping']	= 0;
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']		= '';

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();
		$settings['testmode']			= array( 'list_yesno' );
		$settings['login'] 				= array( 'inputC' );
		$settings['transaction_key']	= array( 'inputC' );
		$settings['currency']			= array( 'list_currency' );
		$settings['promptAddress']		= array( 'list_yesno' );
		$settings['promptZipOnly']		= array( 'list_yesno' );
		$settings['dedicatedShipping']	= array( 'list_yesno' );
		$settings['item_name']			= array( 'inputE' );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function registerProfileTabs()
	{
		$tab			= array();
		$tab['details']	= _AEC_USERFORM_BILLING_DETAILS_NAME;

		if ( $this->settings['dedicatedShipping'] ) {
			$tab['shipping_details'] = _AEC_USERFORM_SHIPPING_DETAILS_NAME;
		}

		return $tab;
	}

	function customtab_details( $request )
	{
		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		$post = aecPostParamClear( $_POST );

		if ( !isset( $post['payprofileselect'] ) ) {
			$post['shipprofileselect'] = null;
		}

		if ( !empty( $post['edit_payprofile'] ) && ( $post['payprofileselect'] != "new" ) ) {
			$ppParams->paymentprofileid = $post['payprofileselect'];
		}

		if ( isset( $post['billFirstName'] ) && ( strpos( $post['cardNumber'], 'X' ) === false ) ) {
			$cim = $this->loadCIM( $ppParams );

			$udata = array( 'billTo_firstName' => 'billFirstName',
							'billTo_lastName' => 'billLastName',
							'billTo_company' => 'billCompany',
							'billTo_address' => 'billAddress',
							'billTo_city' => 'billCity',
							'billTo_state' => 'billState',
							'billTo_zip' => 'billZip',
							'billTo_country' => 'billCountry',
							'billTo_phoneNumber' => 'billPhone',
							'billTo_faxNumber' => 'billFax'
							);

			foreach ( $udata as $authvar => $aecvar ) {
				if ( !empty( $post[$aecvar] ) ) {
					$cim->setParameter( $authvar, trim( $post[$aecvar] ) );
				}
			}

			if ( !empty( $post['cardNumber'] ) || !empty( $post['account_no'] ) ) {
				if( !empty( $post['account_no'] ) ) {
					$basicdata['paymentType']		= 'echeck';
					$basicdata['accountType']		= 'checking';
					$basicdata['routingNumber']		= $post['routing_no'];
					$basicdata['accountNumber']		= $post['account_no'];
					$basicdata['nameOnAccount']		= $post['account_name'];
					$basicdata['echeckType']		= 'CCD';
					$basicdata['bankName']			= $post['bank_name'];
				} else {
					$basicdata['paymentType']		= 'creditcard';
					$basicdata['cardNumber']		= trim( $post['cardNumber'] );
					$basicdata['expirationDate']	= $post['expirationYear'] . '-' . $post['expirationMonth'];
				}

				foreach ( $basicdata as $key => $value ) {
					$cim->setParameter( $key, $value );
				}


				if ( $post['payprofileselect'] == "new" ) {
					$cim->createCustomerPaymentProfileRequest();

					if ( $cim->isSuccessful() ) {
						$profileid = $cim->substring_between( $cim->response,'<customerPaymentProfileId>','</customerPaymentProfileId>' );
						if ( !empty( $profileid ) ) {
							$ppParams = $this->payProfileAdd( $request, $profileid, $post, $ppParams );
						}
					}
				} else {
					if ( isset( $ppParams->paymentProfiles->{$post['payprofileselect']} ) ) {
						$stored_spid = $ppParams->paymentProfiles->{$post['payprofileselect']}->profileid;
						$cim->setParameter( 'customerPaymentProfileId', $stored_spid );
						$cim->updateCustomerPaymentProfileRequest();

						if ( $cim->isSuccessful() ) {
							$this->payProfileUpdate( $request, $post['payprofileselect'], $post, $ppParams );
						}
					}
				}

				$cim->updateCustomerPaymentProfileRequest();

				$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
				$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			}

			if ( !$this->settings['dedicatedShipping'] ) {
				$udata = array( 'shipTo_firstName' => 'billFirstName',
								'shipTo_lastName' => 'billLastName',
								'shipTo_company' => 'billCompany',
								'shipTo_address' => 'billAddress',
								'shipTo_city' => 'billCity',
								'shipTo_state' => 'billState',
								'shipTo_zip' => 'billZip',
								'shipTo_country' => 'billCountry',
								'shipTo_phoneNumber' => 'billPhone',
								'shipTo_faxNumber' => 'billFax'
								);

				foreach ( $udata as $authvar => $aecvar ) {
					if ( !empty( $post[$aecvar] ) ) {
						$cim->setParameter( $authvar, trim( $post[$aecvar] ) );
					}
				}

				if ( isset( $ppParams->shippingProfiles->{$ppParams->shippingprofileid} ) ) {
					$stored_spid = $ppParams->shippingProfiles->{$ppParams->shippingprofileid}->profileid;

					$cim->setParameter( 'customerAddressId', $stored_spid );
					$cim->updateCustomerShippingAddressRequest();

					if ( $cim->isSuccessful() ) {
						$this->shipProfileUpdate( $request, $post['shipprofileselect'], $post, $ppParams );
					}
				}
			}

			$cim = null;
			$cim = $this->loadCIM( $ppParams );
		} else {
			$cim = null;
		}

		$var = $this->ppProfileSelect( array(), $ppParams, true, $ppParams );
		$var2 = $this->checkoutform( $request, $cim );

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=subscriptiondetails', true ) . '" method="post">' . "\n";
		$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		$return .= $this->getParamsHTML( $var2 ) . '<br /><br />';
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="subscriptiondetails" />' . "\n";
		$return .= '<input type="hidden" name="sub" value="authorize_cim_details" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_APPLY . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function customtab_shipping_details( $request )
	{
		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		$post = aecPostParamClear( $_POST );

		if ( !isset( $post['shipprofileselect'] ) ) {
			$post['shipprofileselect'] = null;
		}

		if ( !empty( $post['edit_shipprofile'] ) && ( $post['shipprofileselect'] != "new" ) ) {
			$ppParams->shippingprofileid = $post['shipprofileselect'];
		}

		if ( isset( $post['billFirstName'] ) && empty( $post['edit_shipprofile'] ) ) {
			$cim = $this->loadCIM( $ppParams );

			$udata = array( 'shipTo_firstName' => 'billFirstName',
							'shipTo_lastName' => 'billLastName',
							'shipTo_company' => 'billCompany',
							'shipTo_address' => 'billAddress',
							'shipTo_city' => 'billCity',
							'shipTo_state' => 'billState',
							'shipTo_zip' => 'billZip',
							'shipTo_country' => 'billCountry',
							'shipTo_phoneNumber' => 'billPhone',
							'shipTo_faxNumber' => 'billFax'
							);

			foreach ( $udata as $authvar => $aecvar ) {
				if ( !empty( $post[$aecvar] ) ) {
					$cim->setParameter( $authvar, trim( $post[$aecvar] ) );
				}
			}

			if ( $post['shipprofileselect'] == "new" ) {
				$cim->createCustomerShippingAddressRequest();

				if ( $cim->isSuccessful() ) {
					$profileid = $cim->substring_between( $cim->response,'<customerAddressId>','</customerAddressId>' );
					if ( !empty( $profileid ) ) {
						$ppParams = $this->shipProfileAdd( $request, $profileid, $post, $ppParams );
					}
				}
			} else {
				if ( isset( $ppParams->shippingProfiles->{$post['shipprofileselect']} ) ) {
					$stored_spid = $ppParams->shippingProfiles->{$post['shipprofileselect']}->profileid;
					$cim->setParameter( 'customerAddressId', $stored_spid );
					$cim->updateCustomerShippingAddressRequest();

					if ( $cim->isSuccessful() ) {
						$this->shipProfileUpdate( $request, $post['shipprofileselect'], $post, $ppParams );
					}
				}
			}

			$cim = null;
			$cim = $this->loadCIM( $ppParams );
		} else {
			$cim = null;
		}

		$var = $this->shipProfileSelect( array(), $ppParams, true );
		$var2 = $this->checkoutform( $request, $cim, true, $ppParams );

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=authorize_cim_shipping_details', true ) . '" method="post">' . "\n";
		$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		$return .= $this->getParamsHTML( $var2 ) . '<br /><br />';
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="subscriptiondetails" />' . "\n";
		$return .= '<input type="hidden" name="sub" value="authorize_cim_shipping_details" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_APPLY . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function checkoutform( $request, $cim=null, $nobill=false, $ppParams=false )
	{
		$var = array();
		$hascim = false;
		$vcontent = array();

		if ( $ppParams === false ) {
			$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );
		}

		if ( empty( $cim ) ) {
			if ( $nobill ) {
				$cim = $this->loadCIMship( $ppParams );
			} else {
				$cim = $this->loadCIM( $ppParams );
			}

			if ( $cim->isSuccessful() ) {
				$hascim = true;
			}
		} else {
			$hascim = true;
		}

		if ( !$nobill ) {
			if ( $hascim ) {
				$var['params']['billUpdateInfo'] = array( 'p', _AEC_CCFORM_UPDATE_NAME, _AEC_CCFORM_UPDATE_DESC, '' );

				$vcontent['card_number'] = $cim->substring_between( $cim->response,'<cardNumber>','</cardNumber>' );
				$vcontent['account_no'] = $cim->substring_between( $cim->response,'<accountNumber>','</accountNumber>' );
				$vcontent['routing_no'] = $cim->substring_between( $cim->response,'<routingNumber>','</routingNumber>' );
			} else {
				$vcontent = '';
			}

			global $mainframe;

			$mainframe->addCustomHeadTag( '<script type="text/javascript" src="' . $mainframe->getCfg( 'live_site' ) . '/components/com_acctexp/lib/mootools/mootools.js"></script>' );
			$mainframe->addCustomHeadTag( '<script type="text/javascript" src="' . $mainframe->getCfg( 'live_site' ) . '/components/com_acctexp/lib/mootools/mootabs.js"></script>' );
			$mainframe->addCustomHeadTag( '<script type="text/javascript" charset="utf-8">window.addEvent(\'domready\', init);function init() {myTabs1 = new mootabs(\'myTabs\');}</script>' );

			$var['params'][] = array( 'tabberstart', '', '', '' );
			$var['params'][] = array( 'tabregisterstart', '', '', '' );
			$var['params'][] = array( 'tabregister', 'ccdetails', 'Credit Card', true );
			$var['params'][] = array( 'tabregister', 'echeckdetails', 'eCheck', false );
			$var['params'][] = array( 'tabregisterend', '', '', '' );

			$var['params'][] = array( 'tabstart', 'ccdetails', true, '' );
			$var = $this->getCCform( $var, array( 'card_number', 'card_exp_month', 'card_exp_year', 'card_cvv2' ), $vcontent );
			$var['params'][] = array( 'tabend', '', '', '' );

			$var['params'][] = array( 'tabstart', 'echeckdetails', true, '' );
			$var = $this->getECHECKform( $var );
			$var['params'][] = array( 'tabend', '', '', '' );

			$var['params'][] = array( 'tabberend', '', '', '' );
		}

		if ( !empty( $this->settings['promptAddress'] ) ) {
			$uservalues = array( 'firstName', 'lastName', 'company', 'address', 'city', 'state', 'zip', 'country', 'phone', 'fax' );

			$content = array();
			if ( $hascim ) {
				foreach ( $uservalues as $uv ) {
					if ( in_array( $uv, array( 'phone', 'fax' ) ) ) {
						$content[$uv] = $cim->substring_between( $cim->response,'<' . $uv . 'Number>','</' . $uv . 'Number>' );
					} else {
						if ( $nobill && ( $uv == 'address' ) ) {
							$content[$uv] = $cim->substring_between( $cim->response,'<' . $uv . '>','</' . $uv . '>', 1 );
						} else {
							$content[$uv] = $cim->substring_between( $cim->response,'<' . $uv . '>','</' . $uv . '>' );
						}
					}
				}
			}

			$var = $this->getUserform( $var, $uservalues, $request->metaUser, $content );
		}

		return $var;
	}

	function checkoutAction( $request )
	{
		global $aecConfig;

		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		// Actual form, with ProfileID reference numbers as options

		$return = '<form action="' . AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=checkout', true ) . '" method="post">' . "\n";

		if ( !empty( $ppParams ) ) {
			$cim = $this->loadCIM( $ppParams );

			$var = array();
			$var = $this->ppProfileSelect( $var, $ppParams, false, false );
			$var = $this->shipProfileSelect( $var, $ppParams, false, false, false );

			$return .= $this->getParamsHTML( $var ) . '<br /><br />';
		}

		$return .= $this->getParamsHTML( $this->checkoutform( $request, $cim ) ) . '<br /><br />';
		$return .= '<input type="hidden" name="invoice" value="' . $request->int_var['invoice'] . '" />' . "\n";
		$return .= '<input type="hidden" name="userid" value="' . $request->metaUser->userid . '" />' . "\n";
		$return .= '<input type="hidden" name="task" value="checkout" />' . "\n";
		$return .= '<input type="submit" class="button" value="' . _BUTTON_CHECKOUT . '" /><br /><br />' . "\n";
		$return .= '</form>' . "\n";

		return $return;
	}

	function createRequestXML( $request )
	{
		return "";
	}

	function transmitRequestXML( $xml, $request )
	{
		$return['valid'] = false;

		$ppParams = $request->metaUser->meta->getProcessorParams( $request->parent->id );

		if ( !empty( $ppParams ) ) {
			if ( $request->int_var['params']['payprofileselect'] != "new" ) {
				$ppParams->paymentprofileid = $request->int_var['params']['payprofileselect'];
			}

			if ( $request->int_var['params']['shipprofileselect'] != "new" ) {
				$ppParams->shippingprofileid = $request->int_var['params']['shipprofileselect'];
			}

			$cim = $this->loadCIM( $ppParams );
		} else {
			$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );
		}

		$basicdata = array(	'refId'					=> $request->int_var['invoice'],
							'merchantCustomerId'	=> $request->metaUser->cmsUser->id,
							'description'			=> $request->metaUser->cmsUser->name,
							'email'					=> $request->metaUser->cmsUser->email,
							'paymentType'			=> 'creditcard',
							'cardNumber'			=> trim( $request->int_var['params']['cardNumber'] ),
							'expirationDate'		=> $request->int_var['params']['expirationYear'] . '-' . $request->int_var['params']['expirationMonth']
							);

		if( !empty( $request->int_var['params']['account_no'] ) ) {
			$basicdata['paymentType']		= 'echeck';
			$basicdata['accountType']		= 'checking';
			$basicdata['routingNumber']		= $request->int_var['params']['routing_no'];
			$basicdata['accountNumber']		= $request->int_var['params']['account_no'];
			$basicdata['nameOnAccount']		= $request->int_var['params']['account_name'];
			$basicdata['echeckType']		= 'CCD';
			$basicdata['bankName']			= $request->int_var['params']['bank_name'];
		} else {
			$basicdata['paymentType']		= 'creditcard';
			$basicdata['cardNumber']		= trim( $request->int_var['params']['cardNumber'] );
			$basicdata['expirationDate']	= $request->int_var['params']['expirationYear'] . '-' . $request->int_var['params']['expirationMonth'];
		}

		foreach ( $basicdata as $key => $value ) {
			$cim->setParameter( $key, $value );
		}

		if ( !$this->settings['dedicatedShipping'] || empty( $ppParams ) ) {
			$udata = array( 'billTo_firstName' => 'billFirstName',
							'billTo_lastName' => 'billLastName',
							'billTo_company' => 'billCompany',
							'billTo_address' => 'billAddress',
							'billTo_city' => 'billCity',
							'billTo_state' => 'billState',
							'billTo_zip' => 'billZip',
							'billTo_country' => 'billCountry',
							'billTo_phoneNumber' => 'billPhone',
							'billTo_faxNumber' => 'billFax',
							'shipTo_firstName' => 'billFirstName',
							'shipTo_lastName' => 'billLastName',
							'shipTo_company' => 'billCompany',
							'shipTo_address' => 'billAddress',
							'shipTo_city' => 'billCity',
							'shipTo_state' => 'billState',
							'shipTo_zip' => 'billZip',
							'shipTo_country' => 'billCountry',
							'shipTo_phoneNumber' => 'billPhone',
							'shipTo_faxNumber' => 'billFax'
							);
		} else {
			$udata = array( 'billTo_firstName' => 'billFirstName',
							'billTo_lastName' => 'billLastName',
							'billTo_company' => 'billCompany',
							'billTo_address' => 'billAddress',
							'billTo_city' => 'billCity',
							'billTo_state' => 'billState',
							'billTo_zip' => 'billZip',
							'billTo_country' => 'billCountry',
							'billTo_phoneNumber' => 'billPhone',
							'billTo_faxNumber' => 'billFax'
							);
		}

		foreach ( $udata as $authvar => $aecvar ) {
			if ( !empty( $request->int_var['params'][$aecvar] ) ) {
				$cim->setParameter( $authvar, trim( $request->int_var['params'][$aecvar] ) );
			}
		}

		if ( !empty( $ppParams ) ) {
			if ( strpos( $request->int_var['params']['cardNumber'], 'X' ) === false ) {
				if ( $request->int_var['params']['payprofileselect'] == "new" ) {
					$cim->createCustomerPaymentProfileRequest();

					if ( $cim->isSuccessful() ) {
						$profileid = $cim->substring_between( $cim->response,'<customerPaymentProfileId>','</customerPaymentProfileId>' );
						if ( !empty( $profileid ) ) {
							$ppParams = $this->payProfileAdd( $request, $profileid, $request->int_var['params'], $ppParams );
						}
					}

					$cim->setParameter( 'customerPaymentProfileId', (int) $profileid );
				} else {
					$stored_ppid = $ppParams->paymentProfiles->{$request->int_var['params']['payprofileselect']}->profileid;
					$cim->setParameter( 'customerPaymentProfileId', (int) $stored_ppid );
					$cim->updateCustomerPaymentProfileRequest();

					if ( $cim->isSuccessful() ) {
						$this->payProfileUpdate( $request, $request->int_var['params']['payprofileselect'], $request->int_var['params'], $ppParams );
					}
				}

				if ( $this->settings['dedicatedShipping'] ) {
					$stored_spid = $ppParams->shippingProfiles->{$request->int_var['params']['shipprofileselect']}->profileid;

					$cim->setParameter( 'customerAddressId', (int) $stored_spid );
					$cim->updateCustomerShippingAddressRequest();

					if ( $cim->isSuccessful() ) {
						$this->shipProfileUpdate( $request, $request->int_var['params']['shipprofileselect'], $request->int_var['params'], $ppParams );
					}
				}
			} else {
				$stored_ppid = $ppParams->paymentProfiles->{$request->int_var['params']['payprofileselect']}->profileid;
				$cim->setParameter( 'customerPaymentProfileId', (int) $stored_ppid );

				if ( $this->settings['dedicatedShipping'] ) {
					$stored_spid = $ppParams->shippingProfiles->{$request->int_var['params']['shipprofileselect']}->profileid;

					$cim->setParameter( 'customerAddressId', (int) $stored_spid );
				}
			}
		} else {
			$cim->createCustomerProfileRequest();

			if ( $cim->isSuccessful() ) {
				$ppParams = $this->ProfileAdd( $request, $cim->customerProfileId );

				$cim = $this->loadCIM( $ppParams );

				$profileid = $cim->substring_between( $cim->response,'<customerPaymentProfileId>','</customerPaymentProfileId>' );
				if ( !empty( $profileid ) ) {
					$ppParams = $this->payProfileAdd( $request, $profileid, $request->int_var['params'], $ppParams );
				}

				$cim->setParameter( 'customerPaymentProfileId', (int) $profileid );

				$addprofileid = $cim->substring_between( $cim->response,'<customerAddressId>','</customerAddressId>' );
				if ( !empty( $addprofileid ) ) {
					$ppParams = $this->shipProfileAdd( $request, $addprofileid, $request->int_var['params'], $ppParams );
				}

				$cim->setParameter( 'customerAddressId', (int) $addprofileid );
			}
		}

		if ( $cim->isSuccessful() ) {
			if ( is_array( $request->int_var['amount'] ) ) {
				$cim->setParameter( 'transactionRecurringBilling',	'true' );
				if ( isset( $request->int_var['amount']['amount1'] ) ) {
					$cim->setParameter( 'transaction_amount',	$request->int_var['amount']['amount1'] );
				} else {
					$cim->setParameter( 'transaction_amount',	$request->int_var['amount']['amount3'] );
				}
			} else {
				$cim->setParameter( 'transaction_amount',	$request->int_var['amount'] );
			}

			$cim->setParameter( 'transactionType',		'profileTransAuthCapture' );
			$cim->setParameter( 'transactionCardCode',	trim( $request->int_var['params']['cardVV2'] ) );

			$cim->createCustomerProfileTransactionRequest();

			if ( $cim->isSuccessful() ) {
				$return['valid']	= true;
				$return['invoice']	= $cim->refId;
			} else {
				$return['error']	= $cim->code . ": " . $cim->text;
			}
		} else {
			$return['error']		= $cim->code . ": " . $cim->text;
		}

		return $return;
	}

	function customaction_cancel( $request )
	{
		$return['valid']	= 0;
		$return['cancel']	= true;

		return $return;
	}

	function ppProfileSelect( $var, $ppParams, $select=false, $btn=true )
	{
		$profiles = get_object_vars( $ppParams->paymentProfiles );

		$var['params'][] = array( 'p', _AEC_USERFORM_BILLING_DETAILS_NAME, '' );

		if ( !empty( $profiles ) ) {
			// Single-Select Payment Option
			foreach ( $profiles as $pid => $pobj ) {
				$info = array();

				$info_array = get_object_vars( $pobj->profilehash );

				foreach ( $info_array as $iak => $iav ) {
					if ( !empty( $iav ) ) {
						$info[] = $iav;
					}
				}

				if ( $ppParams->paymentprofileid == $pid ) {
					$text = '<strong>' . implode( '<br />', $info ) . '</strong>';
				} else {
					$text = implode( '<br />', $info );
				}

				$var['params'][] = array( 'radio', 'payprofileselect', $pid, $ppParams->paymentprofileid, $text );
			}

			if ( count( $profiles ) < 10 ) {
				$var['params'][] = array( 'radio', 'payprofileselect', "new", $ppParams->paymentprofileid, 'create new profile' );
			}

			if ( $btn ) {
				$var['params']['edit_payprofile'] = array( 'submit', '', '', ( $select ? _BUTTON_SELECT : _BUTTON_EDIT ) );
			}
		}

		return $var;
	}

	function shipProfileSelect( $var, $ppParams, $select=false, $btn=true, $new=true )
	{
		$profiles = get_object_vars( $ppParams->shippingProfiles );

		$var['params'][] = array( 'p', _AEC_USERFORM_SHIPPING_DETAILS_NAME, '' );

		if ( !empty( $profiles ) ) {
			// Single-Select Shipment Data
			foreach ( $profiles as $pid => $pobj ) {
				$info = array();

				$info_array = get_object_vars( $pobj->profilehash );

				foreach ( $info_array as $iak => $iav ) {
					if ( !empty( $iav ) ) {
						$info[] = $iav;
					}
				}

				if ( $ppParams->shippingprofileid == $pid ) {
					$text = '<strong>' . implode( '<br />', $info ) . '</strong>';
				} else {
					$text = implode( '<br />', $info );
				}

				$var['params'][] = array( 'radio', 'shipprofileselect', $pid, $ppParams->shippingprofileid, $text );
			}

			if ( ( count( $profiles ) < 10 ) && $new ) {
				$var['params'][] = array( 'radio', 'shipprofileselect', "new", $ppParams->shippingprofileid, 'create new profile' );
			}

			if ( $btn ) {
				$var['params']['edit_shipprofile'] = array( 'submit', '', '', ( $select ? _BUTTON_SELECT : _BUTTON_EDIT ) );
			}
		}

		return $var;
	}

	function ProfileAdd( $request, $profileid )
	{
		$ppParams = new stdClass();

		$ppParams->profileid			= $profileid;

		$ppParams->paymentprofileid		= '';
		$ppParams->paymentProfiles		= new stdClass();

		$ppParams->shippingprofileid	= '';
		$ppParams->shippingProfiles		= new stdClass();

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function shipProfileAdd( $request, $profileid, $post, $ppParams )
	{
		$profiles = get_object_vars( $ppParams->shippingProfiles );

		$pointer = count( $profiles );

		$ppParams->shippingProfiles->$pointer = new stdClass();
		$ppParams->shippingProfiles->$pointer->profileid = $profileid;

		$hash = new stdClass();
		$hash->name		= $post['billFirstName'] . ' ' . $post['billLastName'];
		$hash->address	= $post['billAddress'];
		$hash->zipcity	= $post['billZip'] . ' ' . $post['billCity'];

		$ppParams->shippingProfiles->$pointer->profilehash = $hash;

		$ppParams->shippingprofileid = $pointer;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function shipProfileUpdate( $request, $profileid, $post, $ppParams )
	{
		$hash = new stdClass();
		$hash->name		= $post['billFirstName'] . ' ' . $post['billLastName'];
		$hash->address	= $post['billAddress'];
		$hash->zipcity	= $post['billZip'] . ' ' . $post['billCity'];

		$ppParams->shippingProfiles->$profileid->profilehash = $hash;

		$ppParams->shippingprofileid = $profileid;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileAdd( $request, $profileid, $post, $ppParams )
	{
		$profiles = get_object_vars( $ppParams->paymentProfiles );

		$pointer = count( $profiles );

		$data = new stdClass();
		$data->profileid = $profileid;

		$hash = new stdClass();
		$hash->name		= $post['billFirstName'] . ' ' . $post['billLastName'];
		$hash->address	= $post['billAddress'];
		$hash->zipcity	= $post['billZip'] . ' ' . $post['billCity'];

		if ( !empty( $post['account_no'] ) ) {
			$hash->cc		= 'XXXX' . substr( $post['account_no'], -4 );
		} else {
			$hash->cc		= 'XXXX' . substr( $post['cardNumber'], -4 );
		}

		$data->profilehash = $hash;

		$ppParams->paymentProfiles->$pointer = $data;

		$ppParams->paymentprofileid = $pointer;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function payProfileUpdate( $request, $profileid, $post, $ppParams )
	{
		$hash = new stdClass();
		$hash->name		= $post['billFirstName'] . ' ' . $post['billLastName'];
		$hash->address	= $post['billAddress'];
		$hash->zipcity	= $post['billZip'] . ' ' . $post['billCity'];

		if ( !empty( $post['account_no'] ) ) {
			$hash->cc		= 'XXXX' . substr( $post['account_no'], -4 );
		} else {
			$hash->cc		= 'XXXX' . substr( $post['cardNumber'], -4 );
		}

		$ppParams->paymentProfiles->$profileid->profilehash = $hash;

		$ppParams->paymentprofileid = $profileid;

		$request->metaUser->meta->setProcessorParams( $request->parent->id, $ppParams );

		return $ppParams;
	}

	function loadCIM( $ppParams )
	{
		$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );

		if ( empty( $ppParams->profileid ) ) {
			return $cim;
		}

		$cim->setParameter( 'customerProfileId', $ppParams->profileid );
		$cim->getCustomerProfileRequest();

		if ( $cim->isSuccessful() ) {
			return $cim;
		} else {
			return $cim;
		}
	}

	function loadCIMship( $ppParams )
	{
		$cim = new AuthNetCim( $this->settings['login'], $this->settings['transaction_key'], $this->settings['testmode'] );

		$cim->setParameter( 'customerProfileId', $ppParams->paymentProfiles->{$ppParams->profileid}->profileid );
		$cim->setParameter( 'customerAddressId', $ppParams->shippingProfiles->{$ppParams->shippingprofileid}->profileid );
		$cim->getCustomerShippingAddressRequest();

		if ( $cim->isSuccessful() ) {
			return $cim;
		} else {
			return $cim;
		}
	}

	function prepareValidation( $subscription_list )
	{
		return true;
	}

	function validateSubscription( $subscription_id )
	{
		global $database;

		$subscription = new Subscription( $database );
		$subscription->load( $subscription_id );

		$allowed = array( "Trial", "Active" );

		if ( !in_array( $subscription->status, $allowed ) ) {
			return false;
		}

		$invoice = new Invoice( $database );
		$invoice->loadbySubscriptionId( $subscription_id );

		$metaUser = new metaUser( $invoice->userid );
		$ppParams = $metaUser->meta->getProcessorParams( $request->parent->id );

		if ( !empty( $ppParams->profileid ) ) {
			$cim = $this->loadCIM( $ppParams );

			$cim->setParameter( 'customerProfileId',		$ppParams->paymentProfiles->{$ppParams->profileid}->profileid );
			$cim->getCustomerProfileRequest();

			$cim->setParameter( 'customerProfileId',		$cim->customerProfileId );
			$cim->setParameter( 'customerPaymentProfileId',	$cim->customerPaymentProfileId );
			$cim->setParameter( 'customerAddressId',		$cim->customerAddressId );

			$invoice->computeAmount();

			$cim->setParameter( 'transaction_amount',		$invoice->amount );

			$cim->setParameter( 'refId',					$invoice->invoice_number );
			$cim->setParameter( 'merchantCustomerId',		$invoice->userid );

			$cim->setParameter( 'transactionType',			'profileTransAuthCapture' );

			$cim->createCustomerProfileTransactionRequest();

			if ( $cim->isSuccessful() ) {
				$invoice->pay();
				return true;
			}
		}

		return false;
	}

}
?>