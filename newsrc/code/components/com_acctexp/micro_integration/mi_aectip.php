<?php
/**
 * @version $Id: mi_aectip.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - AEC Tip
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_aectip
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_AECTIP_NAME');
		$info['desc'] = JText::_('AEC_MI_AECTIP_DESC');

		return $info;
	}

	function Settings()
	{
		$settings = array();

		$settings['max']	= array( 'inputB' );
		$settings['name']	= array( 'inputC' );
		$settings['desc']	= array( 'inputD' );

		return $settings;
	}

	function Defaults()
	{
		$settings = array();

		$settings['name']	= array( JText::_('MI_MI_AECTIP_USERSELECT_DEFAULT_NAME') );
		$settings['desc']	= array( JText::_('MI_MI_AECTIP_USERSELECT_DEFAULT_DESC') );

		return $settings;
	}

	function saveParams( $params )
	{
		foreach ( $params as $n => $v ) {
			if ( !empty( $v ) ) {
				$params[$n] = AECToolbox::correctAmount( $v );
			}
		}

		return $params;
	}

	function getMIform( $request )
	{
		$settings = array();

		if ( !empty( $this->settings['desc'] ) ) {
			$settings['desc'] = array( 'p', "", $this->settings['desc'] );
		}

		$settings['amt'] = array( 'inputC', $this->settings['name'], '', '' );

		return $settings;
	}

	function verifyMIform( $request )
	{
		$return = array();

		if ( !empty( $request->params['amt'] ) ) {
			if ( $request->params['amt'] > $this->settings['max'] ) {
				$return['error'] = JText::_('MI_MI_AECTIP_USERSELECT_TOOMUCH') . ' ' . $this->settings['max'];
			}
		}

		return $return;
	}

	function invoice_item_cost( $request )
	{
		$this->modifyPrice( $request );

		return true;
	}

	function modifyPrice( $request )
	{
		if ( !isset( $request->params['amt'] ) ) {
			return null;
		}

		$price = AECToolbox::correctAmount( $request->params['amt'] );

		$request->add['terms']->nextterm->addCost( $price, array( 'details' => $this->settings['name'], 'no-discount' => true ) );

		return null;
	}

}
?>
