<?php
/**
 * @version $Id: mi_aectax.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Overall Tax Management MI
 * @copyright 2006-2008 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aectax
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_AECTAX;
		$info['desc'] = _AEC_MI_DESC_AECTAX;

		return $info;
	}

	function Settings()
	{
		if ( isset( $this->settings['locations'] ) ) {
			$this->upgradeSettings();
		}

		$settings = array();
		$settings['custominfo']			= array( 'inputD' );
		$settings['vat_no_request']		= array( 'list_yesno' );
		$settings['vat_countrylist']	= array( 'list_yesno' );
		$settings['vat_localtax']		= array( 'list_yesno' );
		$settings['vat_percentage']		= array( 'inputC' );
		$settings['vat_mode']			= array( 'list' );
		$settings['vat_validation']		= array( 'list' );
		$settings['locations_amount']	= array( 'inputB' );

		$vatval = array();
		$vatval[] = mosHTML::makeOption( '0', _MI_MI_AECTAX_SET_VATVAL_NONE );
		$vatval[] = mosHTML::makeOption( '1', _MI_MI_AECTAX_SET_VATVAL_BASIC );
		$vatval[] = mosHTML::makeOption( '2', _MI_MI_AECTAX_SET_VATVAL_EXTENDED );

		if ( isset( $this->settings['vat_validation'] ) ) {
			$vval = $this->settings['vat_validation'];
		} else {
			$vval = '2';
		}

		$settings['lists']['vat_validation'] = mosHTML::selectList( $vatval, 'vat_validation', 'size="1"', 'value', 'text', $vval );

		$modes = array();
		$modes[] = mosHTML::makeOption( 'pseudo_subtract', _MI_MI_AECTAX_SET_MODE_PSEUDO_SUBTRACT );
		$modes[] = mosHTML::makeOption( 'add', _MI_MI_AECTAX_SET_MODE_ADD );
		$modes[] = mosHTML::makeOption( 'subtract', _MI_MI_AECTAX_SET_MODE_SUBTRACT );

		if ( !empty( $this->settings['locations_amount'] ) ) {
			for ( $i=0; $i<$this->settings['locations_amount']; $i++ ) {
				$p = $i . '_';

				$settings[$p.'id']			= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_ID_NAME, $i+1 ), _MI_MI_AECTAX_SET_ID_DESC );
				$settings[$p.'text']		= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_TEXT_NAME, $i+1 ), _MI_MI_AECTAX_SET_TEXT_DESC );
				$settings[$p.'percentage']	= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_PERCENTAGE_NAME, $i+1 ), _MI_MI_AECTAX_SET_PERCENTAGE_DESC );
				$settings[$p.'mode']		= array( 'list', sprintf( _MI_MI_AECTAX_SET_MODE_NAME, $i+1 ), _MI_MI_AECTAX_SET_MODE_DESC );
				$settings[$p.'extra']		= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_EXTRA_NAME, $i+1 ), _MI_MI_AECTAX_SET_EXTRA_DESC );
				$settings[$p.'mi']			= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_MI_NAME, $i+1 ), _MI_MI_AECTAX_SET_MI_DESC );

				if ( isset( $this->settings[$p.'mode'] ) ) {
					$val = $this->settings[$p.'mode'];
				} else {
					$val = 'pseudo_subtract';
				}

				$settings['lists'][$p.'mode']	= mosHTML::selectList( $modes, $p.'mode', 'size="1"', 'value', 'text', $val );
			}
		}

		$settings['lists']['vat_mode']			= mosHTML::selectList( $modes, $p.'mode', 'size="1"', 'value', 'text', $this->settings['vat_mode'] );

		return $settings;
	}

	function getMIform( $request )
	{
		$settings = array();

		$locations = $this->getLocationList();

		if ( !empty( $locations ) ) {
			if ( !empty( $this->settings['custominfo'] ) ) {
				$settings['exp'] = array( 'p', "", $this->settings['custominfo'] );
			} else {
				$settings['exp'] = array( 'p', "", _MI_MI_AECTAX_DEFAULT_NOTICE );
			}

			if ( count( $locations ) < 5 ) {
				$settings['location'] = array( 'hidden', null, 'mi_'.$this->id.'_location' );

				foreach ( $locations as $id => $choice ) {
					$settings['ef'.$id] = array( 'radio', 'mi_'.$this->id.'_location', $choice['id'], true, $choice['text'] );
				}
			} else {
				$settings['location'] = array( 'list', "", "" );

				$loc = array();
				$loc[] = mosHTML::makeOption( 0, "- - - - - - - -" );

				foreach ( $locations as $id => $choice ) {
					$loc[] = mosHTML::makeOption( $choice['id'], $choice['text'] );
				}

				$settings['lists']['location']	= mosHTML::selectList( $loc, 'location', 'size="1"', 'value', 'text', 0 );
			}

		} else {
			return false;
		}

		if ( !empty( $this->settings['vat_no_request'] ) ) {
			if ( !empty( $this->settings['custominfo'] ) ) {
				$settings['vat_desc'] = array( 'p', "", _MI_MI_AECTAX_VAT_DESC_NAME );
				$settings['vat_number'] = array( 'inputC', _MI_MI_AECTAX_VAT_NUMBER_NAME, _MI_MI_AECTAX_VAT_NUMBER_DESC, '' );
			}
		}

		return $settings;
	}

	function verifyMIform( $request )
	{
		$return = array();

		if ( empty( $request->params['location'] ) || ( $request->params['location'] == "" ) ) {
			$return['error'] = "Please make a selection";
			return $return;
		}

		if ( !empty( $this->settings['vat_no_request'] ) ) {
			if ( !empty( $request->params['vat_number'] ) && ( $request->params['vat_number'] !== "" ) ) {
				$vatlist = $this->vatList();

				$check = $this->checkVatNumber( $request->params['vat_number'], $request->params['location'], $vatlist );
				$return['error'] = "Please make a selection";
				return $return;
			}
		}

		return $return;
	}

	function invoice_items( $request )
	{
		$location = $this->getLocation( $request );

		if ( $location['id'] == $request->params['location'] ) {
			$request = $this->addTax( $request, $location, true );
		}

		return true;
	}

	function invoice_items_checkout( $request )
	{
		$location = $this->getLocation( $request );

		if ( $location['id'] == $request->params['location'] ) {
			$request = $this->addTax( $request, $location );
		}

		return true;
	}

	function action( $request )
	{
		$location = $this->getLocation( $request );

		if ( empty( $location['mi'] ) ) {
			return true;
		}

		$database = &JFactory::getDBO();

		$mi = new microIntegration( $database );

		if ( !$mi->mi_exists( $location['mi'] ) ) {
			return true;
		}

		$mi->load( $location['mi'] );

		if ( !$mi->callIntegration() ) {
			continue;
		}

		$action = 'action';

		$exchange = null;

		if ( $mi->relayAction( $request->metaUser, $exchange, $request->invoice, null, $action, $request->add ) === false ) {
			if ( $aecConfig->cfg['breakon_mi_error'] ) {
				return false;
			}
		}
	}

	function addTax( $request, $location, $double=false )
	{
		// Get Terms
		$m = array_pop( $request->add );

		if ( $double ) {
			$x = $m;
		}

		$total = $m['terms']->terms[0]->renderTotal();

		switch ( $location['mode'] ) {
			default:
			case 'pseudo_subtract':
				$newtotal = ( $total / ( 100 + $location['percentage'] ) ) * 100;

				$tax = AECToolbox::correctAmount( $total - $newtotal );
				break;
			case 'subtract':
				$tax = AECToolbox::correctAmount( $total * ( $location['percentage']/100 ) );

				$newtotal = $total;

				$total = AECToolbox::correctAmount( $newtotal - $tax );

				$tax = -$tax;
				break;
			case 'add':
				$tax = AECToolbox::correctAmount( $total * ( $location['percentage']/100 ) );

				$newtotal = $total;

				$total = AECToolbox::correctAmount( $newtotal + $tax );
				break;
		}

		if ( $double ) {
			$m['terms']->terms[0]->setCost( $newtotal );
			$m['cost'] = $newtotal;

			$request->add[] = $m;

			// Create tax
			$terms = new mammonTerms();
			$term = new mammonTerm();

			$term->addCost( $newtotal, null, true );

			if ( !empty( $location['extra'] ) ) {
				$term->addCost( $tax, array( 'details' => $location['extra'] ), true );
			} else {
				$term->addCost( $tax, null, true );
			}

			$terms->addTerm( $term );

			$request->add[] = array( 'cost' => $tax, 'terms' => $terms );

			if ( $location['mode'] != 'pseudo_subtract' ) {
				$x['terms']->terms[0]->setCost( $total );
				$x['cost'] = $total;
			}

			$request->add[] = $x;
		} else {
			$m['terms']->terms[0]->setCost( $newtotal );

			$m['terms']->terms[0]->addCost( $tax, array( 'details' => $location['extra'] ), true );
			$m['cost'] = $total;

			$request->add[] = $m;
		}

		return $request;
	}

	function getLocation( $request )
	{
		$locations = $this->getLocationList();

		foreach ( $locations as $location ) {
			if ( $location['id'] == $request->params['location'] ) {
				return $location;
			}
		}

		return null;
	}

	function getLocationList()
	{
		if ( isset( $this->settings['locations'] ) ) {
			$this->upgradeSettings();
		}

		$locations = array();
		if ( !empty( $this->settings['vat_countrylist'] ) ) {
			$list = $this->vatList();

			$conversion = AECToolbox::ISO3166_conversiontable( 'a3', 'a2' );

			foreach ( $list as $ccode => $litem ) {
				$text = constant( 'COUNTRYCODE_' . $conversion[$ccode] );

				if ( $this->settings['vat_localtax'] ) {
					$tax = $this->settings['vat_localtax_tax'];
				} else {
					$tax = $litem['tax'];
				}

				$locations[] = array(	'id'			=> $ccode,
										'text'			=> $text,
										'percentage'	=> $tax,
										'mode'			=> $this->settings['vat_mode'],
										'extra'			=> $tax . '%',
										'mi'			=> null
									);
			}
		}

		if ( !empty( $this->settings['locations_amount'] ) ) {
			for ( $i=0; $this->settings['locations_amount']>$i; $i++ ) {
				$locations[] = array(	'id'			=> $this->settings[$i.'_id'],
										'text'			=> $this->settings[$i.'_text'],
										'percentage'	=> $this->settings[$i.'_percentage'],
										'mode'			=> $this->settings[$i.'_mode'],
										'extra'			=> $this->settings[$i.'_extra'],
										'mi'			=> $this->settings[$i.'_mi']
									);
			}
		}

		return $locations;
	}

	function viesValidation( $number )
	{
		$db = &JFactory::getDBO();

		$path = '/comm/taxation_customs/vies/cgi-bin/viesquer/?VAT=' . $number . '&MS=$ViesMS&Lang=EN';

		$url = 'http://www.europa.eu.int' . $path;

		$tempprocessor = new processor( $db );

		$result = strtoupper( $tempprocessor->transmitRequest( $url, $path, null ) );

		if ( strpos( $result, 'REQUEST TIME-OUT' ) != 0 ) {
			return null;
		} elseif ( strpos( $result, 'YES, VALID VAT NUMBER' ) != 0 ) {
			return true;
		} else {
			return false;
		}
	}

	function checkVatNumber( $number, $country, $vatlist )
	{
		if ( in_array( $country, $number ) ) {
			return preg_match( $vatlist[$country]["regex"], $number );
		} else {
			return null;
		}
	}

	function vatList()
	{
		return array(	"AUT" => array( "tax" => "20",		"regex" => array( '/^(AT){0,1}U[0-9]{8}$/i' ) ),
						"BEL" => array( "tax" => "21",		"regex" => array( '/^(BE){0,1}[0]{0,1}[0-9]{9}$/i' ) ),
						"BGR" => array( "tax" => "20",		"regex" => array( '/^(BG){0,1}[0-9]{9,10}$/i' ) ),
						"CYP" => array( "tax" => "15",		"regex" => array( '/^(CY){0,1}[0-9]{8}[A-Z]$/i' ) ),
						"CZE" => array( "tax" => "20",		"regex" => array( '/^(CZ){0,1}[0-9]{8,10}$/i' ) ),
						"DEU" => array( "tax" => "19",		"regex" => array( '/^(DE){0,1}[0-9]{9}$/i' ) ),
						"DNK" => array( "tax" => "25",		"regex" => array( '/^(DK){0,1}[0-9]{8}$/i' ) ),
						"EST" => array( "tax" => "20",		"regex" => array( '/^(EE){0,1}[0-9]{9}$/i' ) ),
						"ESP" => array( "tax" => "16",		"regex" => array( '/^(ES){0,1}([0-9A-Z][0-9]{7}[A-Z])|([A-Z][0-9]{7}[0-9A-Z])$/i' ) ),
						"FIN" => array( "tax" => "22",		"regex" => array( '/^(FI){0,1}[0-9]{8}$/i' ) ),
						"FRA" => array( "tax" => "19.6",	"regex" => array( '/^(FR){0,1}[0-9A-Z]{2}[\ ]{0,1}[0-9]{9}$/i' ) ),
						"GBR" => array( "tax" => "17.5",	"regex" => array( '/^(GB){0,1}([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2})|([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2}[\ ]{0,1}[0-9]{3})|((GD|HA)[0-9]{3})$/i' ) ),
						"GRC" => array( "tax" => "19",		"regex" => array( '/^(GR){0,1}[0-9]{9}$/i' ) ),
						"HUN" => array( "tax" => "25",		"regex" => array( '/^(HU){0,1}[0-9]{8}$/i' ) ),
						"IRL" => array( "tax" => "21",		"regex" => array( '/^(IE){0,1}[0-9][0-9A-Z\+\*][0-9]{5}[A-Z]$/i' ) ),
						"ITA" => array( "tax" => "20",		"regex" => array( '/^(IT){0,1}[0-9]{11}$/i' ) ),
						"LTU" => array( "tax" => "21",		"regex" => array( '/^(LT){0,1}([0-9]{9}|[0-9]{12})$/i' ) ),
						"LUX" => array( "tax" => "15",		"regex" => array( '/^(LU){0,1}[0-9]{8}$/i' ) ),
						"LVA" => array( "tax" => "21",		"regex" => array( '/^(LV){0,1}[0-9]{11}$/i' ) ),
						"MLT" => array( "tax" => "18",		"regex" => array( '/^(MT){0,1}[0-9]{8}$/i' ) ),
						"NLD" => array( "tax" => "19",		"regex" => array( '/^(NL){0,1}[0-9]{9}B[0-9]{2}$/i' ) ),
						"POL" => array( "tax" => "22",		"regex" => array( '/^(PL){0,1}[0-9]{10}$/i' ) ),
						"PRT" => array( "tax" => "20",		"regex" => array( '/^(PT){0,1}[0-9]{9}$/i' ) ),
						"ROU" => array( "tax" => "19",		"regex" => array( '/^(RO){0,1}[0-9]{2,10}$/i' ) ),
						"SWE" => array( "tax" => "25",		"regex" => array( '/^(SE){0,1}[0-9]{12}$/i' ) ),
						"SVN" => array( "tax" => "20",		"regex" => array( '/^(SI){0,1}[0-9]{8}$/i' ) ),
						"SVK" => array( "tax" => "19",		"regex" => array( '/^(SK){0,1}[0-9]{10}$/i' ) )
					);
	}

	function upgradeSettings()
	{
		$llist = $this->oldLocationList();

		$this->settings['locations_amount'] = count( $llist );

		$i = 0;
		foreach ( $llist as $location ) {
			$p = $i . '_';

			foreach ( $location as $key => $value ) {
				$this->settings[$p.$key] = $value;
			}

			$i++;
		}

		unset( $this->settings['locations'] );

		foreach ( $this->settings as $k => $v ) {
			$this->_parent->params[$k] = $v;
		}

		return $this->_parent->storeload();
	}

	function oldLocationList()
	{
		$locations = array();

		$l = explode( "\n", $this->settings['locations'] );

		if ( !empty( $l ) ) {
			foreach ( $l as $loc ) {
				$location = explode( "|", $loc );

				if ( empty( $location[3] ) ) {
					$location[3] = null;
				}

				if ( empty( $location[4] ) ) {
					$location[4] = null;
				}

				$locations[] = array( 'id' => $location[0], 'text' => $location[1], 'percentage' => $location[2], 'extra' => $location[3], 'mi' => $location[4] );
			}
		}

		return $locations;
	}
}
?>
