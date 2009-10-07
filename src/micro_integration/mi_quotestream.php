<?php
/**
 * @version $Id: mi_quotestream.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Google Analytics
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_quotestream
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_QUOTESTREAM;
		$info['desc'] = _AEC_MI_DESC_QUOTESTREAM;

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['login']			= array( 'inputB' );
		$settings['password']		= array( 'inputB' );
		$settings['proId']			= array( 'inputB' );
		$settings['clientGroupId']	= array( 'inputB' );


		return $settings;
	}

	function action( $request )
	{
		$params = $request->metaUser->meta->setMIParams( $request->parent->id, $request->plan->id );

		if ( empty( $params['has_quotestream_user'] ) ) {
			if ( $this->createQSuser( $request ) ) {
				$request->metaUser->meta->setMIParams( $request->parent->id, $request->plan->id, array( 'has_quotestream_user' => true ) );
				$request->metaUser->meta->storeload();
			}
		}

		return true;
	}

	function createQSuser( $request )
	{
		$login = array(	'login' => $this->settings['login'],
						'password' => $this->settings['password'],
						'features' => SOAP_USE_XSI_ARRAY_TYPE
						);

		$client = new SoapClient('https://app.quotemedia.com/services/UserWebservice?wsdl', $login );

		$namearray	= explode( " ", $request->metaUser->cmsUser->name );

		$name = array();
		$name['first_first']	= $namearray[0];
		$maxname				= count($namearray) - 1;
		$name['last']			= $namearray[$maxname];

		unset( $namearray[$maxname] );

		$name['first']			= implode( ' ', $namearray );

		$user = array(	//'address'		=> 'street or mailing address',								// varchar(64)
						//'city'		=> 'city',													// varchar(32)
						//'country'		=> 'country',												// varchar(2) Use ISO 3166
						'email'			=> substr( 0, 50, $request->metaUser->cmsUser->email ),		// varchar(64)
						'firstName'		=> substr( 0, 32, $name['first'] ),							// varchar(32)
						'lastName'		=> substr( 0, 32, $name['last'] ),							// varchar(32)
						'password'		=> substr( 0, 50, $request->metaUser->cmsUser->password ),	// varchar(32) required
						//'phone'		=> 'phone',													// varchar(24)
						//'products'	=> 'packagesArray',											// array of strings
						//'state'		=> 'XX',													// varchar(2) default: US
						'username'		=> substr( 0, 50, $request->metaUser->cmsUser->username )	// varchar(50) required
						//'wemail'		=> 'wirelessemail@somewhere.com',							// varchar(64)
						//'zip'			=> 'postal',												// varchar(15)
						);

		if ( !empty( $this->settings['clientGroupId'] ) ) {
			// varchar(15)
			$user['clientGroupId'] = substr( 0, 15, $this->settings['clientGroupId'] );
		}

		if ( !empty( $this->settings['proId'] ) ) {
			// int(10) if proId specified by quotemedia, else omit
			$user['proId'] = (int) ( min( 9999999999, $this->settings['proId'] ) );
		}

		try {
			$client->createUser( $user );
		} catch ( SoapFault $soapFault ) {
			echo $soapFault;
			return false;
		}

		return true;
	}


	function getQSpackages( $request )
	{
		$login = array(	'login' => $this->settings['login'],
						'password' => $this->settings['password'],
						'features' => SOAP_USE_XSI_ARRAY_TYPE
						);

		$client = new SoapClient('https://app.quotemedia.com/services/UserWebservice?wsdl', $login );

		try {
			$pkgs = $client->getAllPackages( $this->settings['login'] );
		} catch ( SoapFault $soapFault ) {
			echo $soapFault;
			return false;
		}

		return true;
	}
}
?>
