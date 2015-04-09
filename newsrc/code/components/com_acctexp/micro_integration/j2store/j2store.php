<?php
/**
 * @version $Id: mi_j2store.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - JNews
 * @copyright 2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class mi_j2store
{
	public function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_J2STORE');
		$info['desc'] = JText::_('AEC_MI_DESC_J2STORE');
		$info['type'] = array( 'ecommerce.shopping_cart', 'vendor.j2store' );

		return $info;
	}

	public function Settings()
	{
		return array();
	}

	public function invoice_item_cost( $request )
	{
		$results= $dispatcher->trigger('onJ2StoreGetLatestUserOrder',array($user_id)) ;
		$results[0] // will give you the order id of that user

		$request = $this->addCost( $request, $request->add, $option );

		return true;
	}

	public function addCost( $request, $item, $option )
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

		return $request;
	}

	public function action()
	{


		$results = $dispatcher->trigger('onJ2StoreUpdateOrderStatus',array($order_id,$status='')) ;
	}
}
