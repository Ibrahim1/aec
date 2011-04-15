<?php
/**
 * @version $Id: mi_aecmodifycost.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Modify Cost MI
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this option is not allowed.' );

class mi_aecmodifycost
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_AECMODIFYCOST_NAME');
		$info['desc'] = JText::_('AEC_MI_AECMODIFYCOST_DESC');

		return $info;
	}

	function Settings()
	{
		$settings = array();
		$settings['custominfo']		= array( 'inputD' );
		$settings['options']		= array( 'inputB' );

		$modes = array();
		$modes[] = JHTML::_('select.option', 'basic', JText::_('MI_MI_AECMODIFYCOST_SET_MODE_BASIC') );
		$modes[] = JHTML::_('select.option', 'percentage', JText::_('MI_MI_AECMODIFYCOST_SET_MODE_PERCENTAGE') );

		if ( !empty( $this->settings['options'] ) ) {
			for ( $i=0; $i<$this->settings['options']; $i++ ) {
				$p = $i . '_';

				$settings[$p.'id']			= array( 'inputC', sprintf( JText::_('MI_MI_AECMODIFYCOST_SET_ID_NAME'), $i+1 ), JText::_('MI_MI_AECMODIFYCOST_SET_ID_DESC') );
				$settings[$p.'text']		= array( 'inputC', sprintf( JText::_('MI_MI_AECMODIFYCOST_SET_TEXT_NAME'), $i+1 ), JText::_('MI_MI_AECMODIFYCOST_SET_TEXT_DESC') );
				$settings[$p.'amount']		= array( 'inputC', sprintf( JText::_('MI_MI_AECMODIFYCOST_SET_AMOUNT_NAME'), $i+1 ), JText::_('MI_MI_AECMODIFYCOST_SET_AMOUNT_DESC') );
				$settings[$p.'mode']		= array( 'list', sprintf( JText::_('MI_MI_AECMODIFYCOST_SET_MODE_NAME'), $i+1 ), JText::_('MI_MI_AECMODIFYCOST_SET_MODE_DESC') );
				$settings[$p.'extra']		= array( 'inputC', sprintf( JText::_('MI_MI_AECMODIFYCOST_SET_EXTRA_NAME'), $i+1 ), JText::_('MI_MI_AECMODIFYCOST_SET_EXTRA_DESC') );
				$settings[$p.'mi']			= array( 'inputC', sprintf( JText::_('MI_MI_AECMODIFYCOST_SET_MI_NAME'), $i+1 ), JText::_('MI_MI_AECMODIFYCOST_SET_MI_DESC') );

				if ( isset( $this->settings[$p.'mode'] ) ) {
					$val = $this->settings[$p.'mode'];
				} else {
					$val = 'basic';
				}

				$settings['lists'][$p.'mode']			= JHTML::_('select.genericlist', $modes, $p.'mode', 'size="1"', 'value', 'text', $val );
			}
		}

		return $settings;
	}

	function getMIform( $request )
	{
		$settings = array();

		$options = $this->getOptionList();

		if ( !empty( $options ) ) {
			if ( !empty( $this->settings['custominfo'] ) ) {
				$settings['exp'] = array( 'p', "", $this->settings['custominfo'] );
			} else {
				$settings['exp'] = array( 'p', "", JText::_('MI_MI_AECMODIFYCOST_DEFAULT_NOTICE') );
			}

			if ( count( $options ) < 5 ) {
				$settings['option'] = array( 'hidden', null, 'mi_'.$this->id.'_option' );

				foreach ( $options as $id => $choice ) {
					$settings['ef'.$id] = array( 'radio', 'mi_'.$this->id.'_option', $choice['id'], true, $choice['text'] );
				}
			} else {
				$settings['option'] = array( 'list', "", "" );

				$loc = array();
				$loc[] = JHTML::_('select.option', 0, "- - - - - - - -" );

				foreach ( $options as $id => $choice ) {
					$loc[] = JHTML::_('select.option', $choice['id'], $choice['text'] );
				}

				$settings['lists']['option']	= JHTML::_('select.genericlist', $loc, 'option', 'size="1"', 'value', 'text', 0 );
			}

		} else {
			return false;
		}

		return $settings;
	}

	function verifyMIform( $request )
	{
		$return = array();

		if ( empty( $request->params['option'] ) || ( $request->params['option'] == "" ) ) {
			$return['error'] = "Please make a selection";
			return $return;
		}

		return $return;
	}

	function invoice_item_cost( $request )
	{
		$option = $this->getOption( $request );

		if ( !empty( $option ) ) {
			$request = $this->addCost( $request, $request->add, $option );
		}

		return true;
	}

	function addCost( $request, $item, $option )
	{
		$total = $item['terms']->terms[0]->renderTotal();

		if ( $option['mode'] == 'basic' ) {
			$extracost = $option['amount'];
		} else {
			$extracost = AECToolbox::correctAmount( $total * ( $option['amount']/100 ) );
		}

		$newtotal = AECToolbox::correctAmount( $total + $option['amount'] );

		$item['terms']->terms[0]->addCost( $extracost, array( 'details' => $option['extra'] ) );
		$item['cost'] = $item['terms']->renderTotal();

		$request->add->itemlist[] = $item;

		return $request;
	}

	function action( $request )
	{
		$option = $this->getOption( $request );

		if ( empty( $option['mi'] ) ) {
			return true;
		}

		$db = &JFactory::getDBO();

		$mi = new microIntegration( $db );

		if ( !$mi->mi_exists( $option['mi'] ) ) {
			return true;
		}

		$mi->load( $option['mi'] );

		if ( !$mi->callIntegration() ) {
			continue;
		}

		$action = 'action';

		$exchange = $params = null;

		if ( $mi->relayAction( $request->metaUser, $exchange, $request->invoice, null, $action, $request->add, $params ) === false ) {
			global $aecConfig;

			if ( $aecConfig->cfg['breakon_mi_error'] ) {
				return false;
			}
		}

		return true;
	}

	function getOption( $request )
	{
		$options = $this->getOptionList();

		foreach ( $options as $option ) {
			if ( $option['id'] == $request->params['option'] ) {
				return $option;
			}
		}

		return null;
	}

	function getOptionList()
	{
		$options = array();
		if ( !empty( $this->settings['options'] ) ) {
			for ( $i=0; $this->settings['options']>$i; $i++ ) {
				$options[] = array(	'id'			=> $this->settings[$i.'_id'],
									'text'			=> $this->settings[$i.'_text'],
									'amount'		=> $this->settings[$i.'_amount'],
									'mode'			=> $this->settings[$i.'_mode'],
									'extra'			=> $this->settings[$i.'_extra'],
									'mi'			=> $this->settings[$i.'_mi']
								);
			}
		}

		return $options;
	}

}
?>
