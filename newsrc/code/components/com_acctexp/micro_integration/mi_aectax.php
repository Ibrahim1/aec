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
		$settings['vat_removeonvalid']	= array( 'list_yesno' );
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

		if ( !isset( $this->settings['vat_mode'] ) ) {
			$this->settings['vat_mode'] = 'pseudo_subtract';
		}

		$settings['lists']['vat_mode']			= mosHTML::selectList( $modes, 'vat_mode', 'size="1"', 'value', 'text', $this->settings['vat_mode'] );

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

				$vat_number = $this->clearVatNumber( $request->params['vat_number'] );

				$check = $this->checkVatNumber( $request->params['vat_number'], $request->params['location'], $vatlist );

				if ( !$check ) {
					$return['error'] = "Invalid VAT Number";
					return $return;
				}
			}
		}

		return $return;
	}

	function invoice_items( $request )
	{
		$location = $this->getLocation( $request );

		if ( !empty( $location ) ) {
			$item = array_pop( $request->add->itemlist );

			$request = $this->addTax( $request, $item, $location );
		}

		return true;
	}

	function invoice_items_total( $request )
	{
		$location = $this->getLocation( $request );

		$taxtypes		= array();
		$taxcollections	= array();

		// Collect all the taxes from the individual item costs
		foreach ( $request->add->itemlist as $item ) {
			foreach ( $item['terms']->terms as $term ) {
				foreach ( $term->cost as $cost ) {
					if ( $cost->type == 'tax' ) {
						if ( in_array( $cost->cost['details'], $taxtypes ) ) {
							$typeid = array_search( $cost->cost['details'], $taxtypes );
						} else {
							$taxtypes[] = $cost->cost['details'];

							$typeid = count( $taxtypes ) - 1;
						}

						if ( !isset( $taxcollections[$typeid] ) ) {
							$taxcollections[$typeid] = 0;
						}

						$taxcollections[$typeid] += $cost->renderCost();
					}
				}
			}
		}

		if ( count( $taxcollections ) == 0 ) {
			return null;
		}

		$total = serialize( $request->add->total );

		$taxamount = 0;

		if ( !isset( $request->add->tax ) ) {
			$request->add->tax = array();
		}

		// Add tax items to total
		foreach ( $taxcollections as $tid => $amount ) {
			// Create tax
			$term = new mammonTerm();

			if ( !empty( $taxtypes[$tid] ) ) {
				$term->addCost( $amount, array( 'details' => $taxtypes[$tid] ), true );
			} else {
				$term->addCost( $amount, null, true );
			}

			$terms = new mammonTerms();
			$terms->addTerm( $term );

			// Add the "Tax" row
			$request->add->tax[] = array( 'cost' => $amount, 'terms' => $terms );

			$taxamount += $amount;
		}

		$grand_total = AECToolbox::correctAmount( $request->add->total->cost['amount'] + $taxamount );

		// Modify grand total according to tax
		$request->add->grand_total->set( 'cost', array( 'amount' => $grand_total ) );

		$request->add->total = unserialize( $total );

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

	function addTax( $request, $item, $location )
	{
		$total = $item['terms']->terms[0]->renderTotal();

		if ( !empty( $this->settings['vat_no_request'] ) ) {
			if ( !empty( $request->params['vat_number'] ) && ( $request->params['vat_number'] !== "" ) ) {
				$vatlist = $this->vatList();

				$vat_number = $this->clearVatNumber( $request->params['vat_number'] );

				$check = $this->checkVatNumber( $vat_number, $request->params['location'], $vatlist );

				if ( $check ) {
					if ( ( $location['mode'] == 'pseudo_subtract' ) && ( $this->settings['vat_removeonvalid'] ) ) {
						$location['mode'] = 'subtract';
					} elseif ( ( $location['mode'] == 'add' ) && ( $this->settings['vat_removeonvalid'] ) ) {
						$location['mode'] = '';
					} elseif ( ( $location['mode'] == 'subtract' ) && ( $this->settings['vat_removeonvalid'] ) ) {
						$location['mode'] = '';
					}
				}
			}
		}

		switch ( $location['mode'] ) {
			default:
				$newtotal = $total;

				$tax = "0.00";
				break;
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

		$item['terms']->terms[0]->addCost( $tax, array( 'details' => $location['extra'] ), true );
		$item['cost'] = $item['terms']->renderTotal();

		$request->add->itemlist[] = $item;

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

	function clearVatNumber( $vat_number )
	{
		// Remove whitespace
		$vat_number = preg_replace('/\s\s+/', '', $vat_number);

		// Only allow alphanumeric characters
		$vat_number = preg_replace( "/[^a-z \d]/i", '', $vat_number );

		return $vat_number;
	}

	function checkVatNumber( $number, $country, $vatlist )
	{
		if ( !$this->settings['vat_validation'] ) {
			return true;
		}

		if ( strlen( $country ) == 2 ) {
			$conversion = AECToolbox::ISO3166_conversiontable( 'a2', 'a3' );

			$country = $conversion[$country];
		}

		$check = false;
		if ( array_key_exists( $country, $vatlist ) ) {
			$check = preg_match( $vatlist[$country]["regex"], $number );

			$countrycode = substr( $vatlist[$country]["regex"], 3, 2 );
		} else {
			$match = false;
			foreach ( $vatlist as $ccode => $cc ) {
				if ( !$match ) {
					$match = preg_match( $cc["regex"], $number );

					if ( $match ) {
						$check = true;

						$countrycode = substr( $cc["regex"], 3, 2 );
					}
				}
			}
		}

		if ( ( $this->settings['vat_validation'] == 2 ) && $check ) {
			return $this->viesValidation( substr( $number, 2 ), $countrycode );
		} else {
			return $check;
		}
	}

	function viesValidation( $number, $country )
	{
		$db = &JFactory::getDBO();

		$path = '/taxation_customs/vies/viesquer.do?vat=' . $number . '&ms=' . $country . '&iso=' . $country . '&lang=EN';

		$url = 'http://ec.europa.eu' . $path;

		$tempprocessor = new processor($db);

		$result = $tempprocessor->transmitRequest( $url, $path );

		if ( strpos( $result, 'Request time-out' ) != 0 ) {
			return null;
		} elseif ( strpos( $result, 'Yes, valid VAT number' ) != 0 ) {
			return true;
		} else {
			return false;
		}
	}

	function vatList()
	{
		return array(	"AUT" => array( "tax" => "20",		"regex" => '/^(AT){0,1}U[0-9]{8}$/i' ),
						"BEL" => array( "tax" => "21",		"regex" => '/^(BE){0,1}[0]{0,1}[0-9]{9}$/i' ),
						"BGR" => array( "tax" => "20",		"regex" => '/^(BG){0,1}[0-9]{9,10}$/i' ),
						"CYP" => array( "tax" => "15",		"regex" => '/^(CY){0,1}[0-9]{8}[A-Z]$/i' ),
						"CZE" => array( "tax" => "20",		"regex" => '/^(CZ){0,1}[0-9]{8,10}$/i' ),
						"DEU" => array( "tax" => "19",		"regex" => '/^(DE){0,1}[0-9]{9}$/i' ),
						"DNK" => array( "tax" => "25",		"regex" => '/^(DK){0,1}[0-9]{8}$/i' ),
						"EST" => array( "tax" => "20",		"regex" => '/^(EE){0,1}[0-9]{9}$/i' ),
						"ESP" => array( "tax" => "16",		"regex" => '/^(ES){0,1}([0-9A-Z][0-9]{7}[A-Z])|([A-Z][0-9]{7}[0-9A-Z])$/i' ),
						"FIN" => array( "tax" => "22",		"regex" => '/^(FI){0,1}[0-9]{8}$/i' ),
						"FRA" => array( "tax" => "19.6",	"regex" => '/^(FR){0,1}[0-9A-Z]{2}[\ ]{0,1}[0-9]{9}$/i' ),
						"GBR" => array( "tax" => "17.5",	"regex" => '/^(GB|UK){0,1}([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2})|([1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2}[\ ]{0,1}[0-9]{3})|((GD|HA)[0-9]{3})$/i' ),
						"GRC" => array( "tax" => "19",		"regex" => '/^(GR|EL){0,1}[0-9]{9}$/i' ),
						"HUN" => array( "tax" => "25",		"regex" => '/^(HU){0,1}[0-9]{8}$/i' ),
						"IRL" => array( "tax" => "21",		"regex" => '/^(IE){0,1}[0-9][0-9A-Z\+\*][0-9]{5}[A-Z]$/i' ),
						"ITA" => array( "tax" => "20",		"regex" => '/^(IT){0,1}[0-9]{11}$/i' ),
						"LTU" => array( "tax" => "21",		"regex" => '/^(LT){0,1}([0-9]{9}|[0-9]{12})$/i' ),
						"LUX" => array( "tax" => "15",		"regex" => '/^(LU){0,1}[0-9]{8}$/i' ),
						"LVA" => array( "tax" => "21",		"regex" => '/^(LV){0,1}[0-9]{11}$/i' ),
						"MLT" => array( "tax" => "18",		"regex" => '/^(MT){0,1}[0-9]{8}$/i' ),
						"NLD" => array( "tax" => "19",		"regex" => '/^(NL){0,1}[0-9]{9}B[0-9]{2}$/i' ),
						"POL" => array( "tax" => "22",		"regex" => '/^(PL){0,1}[0-9]{10}$/i' ),
						"PRT" => array( "tax" => "20",		"regex" => '/^(PT){0,1}[0-9]{9}$/i' ),
						"ROU" => array( "tax" => "19",		"regex" => '/^(RO){0,1}[0-9]{2,10}$/i' ),
						"SWE" => array( "tax" => "25",		"regex" => '/^(SE){0,1}[0-9]{12}$/i' ),
						"SVN" => array( "tax" => "20",		"regex" => '/^(SI){0,1}[0-9]{8}$/i' ),
						"SVK" => array( "tax" => "19",		"regex" => '/^(SK){0,1}[0-9]{10}$/i' )
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
