<?php
/**
 * @version $Id: mi_interspireem.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Micro Integrations - Interspire Email Marketer
 * @copyright 2011-2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_interspireem
{
	function Info()
	{
		$info = array();
		$info['name'] = JText::_('AEC_MI_NAME_INTERSPIREEM');
		$info['desc'] = JText::_('AEC_MI_DESC_INTERSPIREEM');
		$info['type'] = array( 'sharing.newsletter', 'vendor.interspire' );

		return $info;
	}

	function Settings()
	{
		$li = array();
		$li[] = JHTML::_('select.option', 0, "--- --- ---" );

		if ( !empty( $this->settings['user_token'] ) ) {
			$lists = $this->GetLists();

			if ( !empty( $lists ) ) {
				foreach( $lists as $list ) {
					$li[] = JHTML::_('select.option', $list->listid, $list->name );
				}
			}
		}

		if ( !isset( $this->settings['list'] ) ) {
			$this->settings['list'] = 0;
		}

		if ( !isset( $this->settings['list_exp'] ) ) {
			$this->settings['list_exp'] = 0;
		}

		$settings['lists']['list']				= JHTML::_( 'select.genericlist', $li, 'list', 'size="4"', 'value', 'text', $this->settings['list'] );
		$settings['lists']['list_exp']			= JHTML::_( 'select.genericlist', $li, 'list_exp', 'size="4"', 'value', 'text', $this->settings['list_exp'] );

		$settings = array();
		$settings['user_name']			= array( 'inputC' );
		$settings['user_token']			= array( 'inputC' );

		$settings['custom_details']		= array( 'inputD' );

		$settings['list']				= array( 'list' );
		$settings['list_exp']			= array( 'list' );

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings					= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function expiration_action( $request )
	{
		if ( !empty( $this->settings['list'] ) ) {
			$this->DeleteSubscriber( $request, $this->settings['list'] );
		}

		if ( !empty( $this->settings['list_exp'] ) ) {
			$this->AddSubscriberToList( $request, $request->metaUser->cmsUser->email, $this->settings['list_exp'] );
		}
	}

	function action( $request )
	{
		if ( !empty( $this->settings['list'] ) ) {
			$this->AddSubscriberToList( $request, $request->metaUser->cmsUser->email, $this->settings['list'] );
		}
	}

	function on_userchange_action( $request )
	{
		if ( !empty( $this->settings['list'] ) ) {
			$this->DeleteSubscriber( $request, $request->metaUser->cmsUser->email, $this->settings['list'] );
		}

		if ( !empty( $this->settings['list'] ) ) {
			$this->AddSubscriberToList( $request, $request->post['email'], $this->settings['list'] );
		}
	}

	function DeleteSubscriber( $request, $list )
	{
		$data = '<emailaddress>' . $request->metaUser->cmsUser->email . '</emailaddress>
				<list>' . $list . '</list>';

		$xml = $this->getRequest( 'subscribers' ,'DeleteSubscriber' , $data );

		$result = $this->sendRequest( $xml );
	}
	
	function GetLists()
	{
		$xml = $this->getRequest( 'user' ,'GetLists' , '' );

		$result = $this->sendRequest( $xml );

		$xml_doc = simplexml_load_string( $result );
		
		return $xml_doc->data;
	}

	function IsContactOnList( $request, $list )
	{
		$data = '<emailaddress>' . $request->metaUser->cmsUser->email . '</emailaddress>
				<list>' . $list . '</list>';

		$xml = $this->getRequest( 'subscribers' ,'IsContactOnList' , $data );

		$result = $this->sendRequest( $xml );
	}

	function AddSubscriberToList( $request, $email, $list )
	{
		$data = '<emailaddress>' . $email . '</emailaddress>
				<mailinglist>' . $list . '</mailinglist>
				<format>html</format>
				<confirmed>yes</confirmed>
				<customfields>
				<item>
				<fieldid>1</fieldid>
				<value>John Smith</value>
				</item>
				</customfields>';

		$xml = $this->getRequest( 'subscribers', 'AddSubscriberToList', $data );

		$result = $this->sendRequest( $xml );
	}

	function GetCustomFieldData()
	{
		$data = '<listids>' . $this->settings['list'] . '</listids>';

		$xml = $this->getRequest( 'lists', 'GetCustomFields<', $data );

		$result = $this->sendRequest( $xml );
	}

	function getRequest($type, $method, $data )
	{
		$xml = '<xmlrequest>
				<username>' . $this->settings['user_name'] . '</username>
				<usertoken>' . $this->settings['user_token'] . '</usertoken>
				<requesttype>' . $type . '</requesttype>
				<requestmethod>' . $method . '</requestmethod>
				<details>' . $data . '</details>
				</xmlrequest>
				';

		return $xml;
	}

	function sendRequest( $xml )
	{
		$db = &JFactory::getDBO();

		$path = '/IEM/xml.php';

		$url = 'http://www.yourdomain.com' . $path;

		$tempprocessor = new processor($db);

		return $tempprocessor->transmitRequest( $url, $path, $xml );
	}
}

?>
