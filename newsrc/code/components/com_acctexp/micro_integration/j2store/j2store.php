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
		$dispatcher = new JEventDispatcher();

		$results= $dispatcher->trigger(
			'onJ2StoreGetLatestUserOrder',
			array($request->metaUser->userid)
		);

		$order = $this->fetchOrder($results[0]);

		$request = $this->addCost( $request, $request->add, $order->cost, $order->id );

		if ( is_object($request->invoice) ) {
			$request->invoice->addParams( array('j2store_orderid' => $order->id) );
			$request->invoice->storeload();
		}

		return true;
	}

	private function fetchOrder( $id )
	{
		return new stdClass();
	}

	public function addCost( $request, $item, $amount, $info )
	{
		$item['terms']->terms[0]->addCost( $amount, array( 'details' => $info ) );

		$item['cost'] = $item['terms']->renderTotal();

		return $request;
	}

	public function action( $request )
	{
		$dispatcher = new JEventDispatcher();

		$params = $request->invoice->getParams();

		if ( isset($params['j2store_orderid']) ) {
			return $dispatcher->trigger(
				'onJ2StoreUpdateOrderStatus',
				array($params['j2store_orderid'], 'cleared')
			);
		}

		return null;
	}
}
