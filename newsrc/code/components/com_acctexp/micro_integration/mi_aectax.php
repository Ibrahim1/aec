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
		$settings = array();
		$settings['custominfo']			= array( 'inputD' );
		$settings['vat_no_request']		= array( 'list_yesno' );
		$settings['locations_amount']	= array( 'inputB' );

		if ( !empty( $this->settings['locations_amount'] ) ) {
			for ( $i=0; $i<$this->settings['locations_amount']; $i++ ) {
				$p = $i . '_';

				$settings[$p.'id']			= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_ID_NAME, $i+1 ), _MI_MI_AECTAX_SET_ID_DESC );
				$settings[$p.'text']		= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_TEXT_NAME, $i+1 ), _MI_MI_AECTAX_SET_TEXT_DESC );
				$settings[$p.'percentage']	= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_PERCENTAGE_NAME, $i+1 ), _MI_MI_AECTAX_SET_PERCENTAGE_DESC );
				$settings[$p.'mode']		= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_MODE_NAME, $i+1 ), _MI_MI_AECTAX_SET_MODE_DESC );
				$settings[$p.'extra']		= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_EXTRA_NAME, $i+1 ), _MI_MI_AECTAX_SET_EXTRA_DESC );
				$settings[$p.'mi']			= array( 'inputC', sprintf( _MI_MI_AECTAX_SET_MI_NAME, $i+1 ), _MI_MI_AECTAX_SET_MI_DESC );
			}
		}

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

		$settings['vat_number'] = array( 'inputC', _MI_MI_AECTAX_VAT_NUMBER_NAME, _MI_MI_AECTAX_VAT_NUMBER_DESC, '' );

		return $settings;
	}

	function verifyMIform( $request )
	{
		$return = array();

		if ( empty( $request->params['location'] ) || ( $request->params['location'] == "" ) ) {
			$return['error'] = "Please make a selection";
			return $return;
		}

		return $return;
	}

	function invoice_items( $request )
	{
		$locations = $this->getLocationList();

		$tt = 0;
		foreach ( $locations as $location ) {
			if ( $location['id'] == $request->params['location'] ) {
				$tt = $location['percentage'];
			}
		}

		if ( empty( $tt ) ) {
			return true;
		}

		// Append Tax Data to content
		$m = array_pop( $request->add );

		$x = $m;

		$total = $m['terms']->terms[0]->renderTotal();

		$tax = AECToolbox::correctAmount( 100 * ( $total / ( 100 + $tt/100 ) ) );

		$newtotal = AECToolbox::correctAmount( $total - $tax );

		$m['terms']->terms[0]->setCost( $newtotal );
		$m['cost'] = $newtotal;

		$request->add[] = $m;

		// Create tax
		$terms = new mammonTerms();
		$term = new mammonTerm();

		$term->set( 'duration', array( 'none' => true ) );
		$term->set( 'type', 'tax' );
		$term->addCost( $tax );

		$terms->addTerm( $term );

		$request->add[] = array( 'cost' => $tax, 'terms' => $terms );

		$request->add[] = $x;

		return true;
	}

	function invoice_items_checkout( $request )
	{
		$location = $this->getLocation( $request );

		if ( empty( $location ) ) {
			return true;
		}

		// Append Tax Data to content
		$m = array_pop( $request->add );

		$x = $m;

		$total = $m['terms']->terms[0]->renderTotal();

		$tax = AECToolbox::correctAmount( 100 * ( $total / ( 100 + $location['percentage']/100 ) ) );

		$newtotal = AECToolbox::correctAmount( $total - $tax );

		$m['terms']->terms[0]->setCost( $newtotal );
		$m['cost'] = $newtotal;

		$request->add[] = $m;

		// Create tax
		$terms = new mammonTerms();

		$term = new mammonTerm();
		$term->set( 'duration', array( 'none' => true ) );
		$term->set( 'type', 'tax' );
		$term->addCost( $newtotal );

		if ( !empty( $location['extra'] ) ) {
			$term->addCost( $tax, array( 'details' => $location['extra'] ), true );
		} else {
			$term->addCost( $tax, null, true );
		}

		$terms->addTerm( $term );

		$request->add[] = array( 'cost' => $tax, 'terms' => $terms );

		return true;
	}

	function addTax()
	{

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
		$locations = array();

		if ( !empty( $this->settings['locations_amount'] ) ) {
			for ( $i=0; $this->settings['locations_amount']>$i; $i++ ) {
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

		return $this->storeload();
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
