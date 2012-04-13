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
		$info['name'] = JText::_('AEC_MI_NAME_interspireem');
		$info['desc'] = JText::_('AEC_MI_DESC_interspireem');

		return $info;
	}

	function Settings()
	{
		$li = array();
		$li[] = JHTML::_('select.option', 0, "--- --- ---" );

		if ( !empty( $this->settings['api_key'] ) ) {
			$MCAPI = new MCAPI( $this->settings['api_key'] );

			$lists = $MCAPI->lists();

			if ( !empty( $lists ) ) {
				foreach( $lists as $list ) {
					$li[] = JHTML::_('select.option', $list['id'], $list['name'] );
				}
			}
		}

		if ( !isset( $this->settings['list'] ) ) {
			$this->settings['list'] = 0;
		}

		if ( !isset( $this->settings['list_exp'] ) ) {
			$this->settings['list_exp'] = 0;
		}

		$settings = array();
		$settings['user_name']		= array( 'inputC' );
		$settings['user_token']	= array( 'inputC' );

		$settings['custom_details']	= array( 'inputD' );

		$settings['lists']['list']				= JHTML::_( 'select.genericlist', $li, 'list', 'size="4"', 'value', 'text', $this->settings['list'] );
		$settings['lists']['list_unsub']		= JHTML::_( 'select.genericlist', $li, 'list_unsub', 'size="4"', 'value', 'text', $this->settings['list_unsub'] );
		$settings['lists']['list_exp']			= JHTML::_( 'select.genericlist', $li, 'list_exp', 'size="4"', 'value', 'text', $this->settings['list_exp'] );
		$settings['lists']['list_exp_unsub']	= JHTML::_( 'select.genericlist', $li, 'list_exp_unsub', 'size="4"', 'value', 'text', $this->settings['list_exp_unsub'] );

		$settings['list']			= array( 'list' );
		$settings['list_unsub']		= array( 'list' );
		$settings['list_exp']		= array( 'list' );
		$settings['list_exp_unsub']	= array( 'list' );
		$settings['user_checkbox']	= array( 'toggle' );
		$settings['custominfo']		= array( 'inputD' );

		$rewriteswitches			= array( 'cms', 'user', 'expiration', 'subscription', 'plan', 'invoice' );
		$settings					= AECToolbox::rewriteEngineInfo( $rewriteswitches, $settings );

		return $settings;
	}

	function Defaults()
	{
		$settings = array();

		$settings['user_checkbox']		= 1;

		return $settings;
	}


	function profile_info( $request )
	{
		if ( !empty( $this->settings['api_key'] ) && !empty( $this->settings['account_id'] ) && !empty( $this->settings['account_name'] ) )  {
			$MCAPI = new MCAPI( $this->settings['api_key'] );

			$mcuser = $MCAPI->listMemberInfo( $this->settings['list'], $request->metaUser->cmsUser->email);

			if ( empty( $mcuser['id'] ) ) {
				$mcuser = $MCAPI->listMemberInfo( $this->settings['list_exp'], $request->metaUser->cmsUser->email);

				$listid = $this->settings['list_exp'];
			} else {
				$listid = $this->settings['list'];
			}

			$server = explode( '-', $this->settings['api_key'] );

			if ( !empty( $mcuser['id'] ) ) {
				$message = '<p><a href="http://' . $this->settings['account_name'] . '.' . $server[1] . '.list-manage.com/unsubscribe?u=' . $this->settings['account_id'] . '&id=' . $listid . '">Unsubscribe from our newsletter</a></p>';
				return $message;
			}
		}

		return '';
	}

	function getMIform( $request )
	{
		$settings = array();

		if ( empty( $this->settings['user_checkbox'] ) ) {
			return $settings;
		}

		$MCAPI = new MCAPI( $this->settings['api_key'] );

		$mcuser = $MCAPI->listMemberInfo( $this->settings['list'], $request->metaUser->cmsUser->email);

		if ( empty( $mcuser['id'] ) ) {
			if ( empty( $this->settings['user_checkbox'] ) ) {
				return $settings;
			}

			if ( !empty( $this->settings['custominfo'] ) ) {
				$settings['exp'] = array( 'p', "", $this->settings['custominfo'] );
			} else {
				$settings['exp'] = array( 'p', "", JText::_('MI_MI_interspireem_DEFAULT_NOTICE') );
			}

			$settings['get_newsletter'] = array( 'checkbox', 'mi_'.$this->id.'_get_newsletter', 1, 0, "Sign up to our Newsletter" );
		}

		return $settings;
	}

	function expiration_action( $request )
	{
		if ( empty( $this->settings['list_exp'] ) ) {
			return null;
		}

		$MCAPI = new MCAPI( $this->settings['api_key'] );

		$name = $request->metaUser->explodeName();

		$merge_vars = array( 'FNAME' => $name['first'], 'LNAME' => $name['last'] );

		$merge_vars = processor::customParams( $this->settings['custom_details'], $merge_vars, $request );

		$mcuser = $MCAPI->listMemberInfo( $this->settings['list_exp'], $request->metaUser->cmsUser->email );

		if ( empty( $mcuser['id'] ) ) {
			$result = $MCAPI->listSubscribe( $this->settings['list_exp'], $request->metaUser->cmsUser->email, $merge_vars );
		}

		$mcuser = $MCAPI->listMemberInfo( $this->settings['list_exp_unsub'], $request->metaUser->cmsUser->email );

		if ( !empty( $mcuser['id'] ) ) {
			$result = $MCAPI->listUnsubscribe( $this->settings['list_exp_unsub'], $request->metaUser->cmsUser->email, false, false, false);
		}
	}

	function action( $request )
	{
		if ( empty( $this->settings['list'] ) ) {
			return null;
		}

		$MCAPI = new MCAPI( $this->settings['api_key'] );

		$is_allowed = false;

		if ( empty( $this->settings['user_checkbox'] ) ) {
			$is_allowed = true;
		} elseif ( !empty( $this->settings['user_checkbox'] ) && !empty( $request->params['get_newsletter'] ) ) {
			$is_allowed = true;
		}

		$name = $request->metaUser->explodeName();

		$merge_vars = array( 'FNAME' => $name['first'], 'LNAME' => $name['last'] );

		$merge_vars = processor::customParams( $this->settings['custom_details'], $merge_vars, $request );

		$mcuser = $MCAPI->listMemberInfo( $this->settings['list'], $request->metaUser->cmsUser->email );

		if ( empty( $mcuser['id'] ) && $is_allowed ) {
			$result = $MCAPI->listSubscribe( $this->settings['list'], $request->metaUser->cmsUser->email, $merge_vars );
		}

		$mcuser = $MCAPI->listMemberInfo( $this->settings['list_unsub'], $request->metaUser->cmsUser->email );

		if ( !empty( $mcuser['id'] ) ) {
			$result = $MCAPI->listUnsubscribe( $this->settings['list_unsub'], $request->metaUser->cmsUser->email, false, false, false);
		}
	}

	function on_userchange_action( $request )
	{
		$MCAPI = new MCAPI( $this->settings['api_key'] );

		$email = $request->row->email;

		$mcuser = $MCAPI->listMemberInfo( $this->settings['list'], $email );

		$request->metaUser = new metaUser( $request->row->id );

		$country = AECToolbox::rewriteEngineRQ( $this->settings['country' . $request->area], $request );

		if ( $mcuser['id'] && ($request->trace == 'user' || $request->trace == 'adminuser') ) {
			// if the name was submitted in the last request, we can use that name.  Otherwise, use the current name
			if ( $request->post['name'] ) {
				$name = metaUser::_explodeName( $request->post['name'] );
			} else {
				$name = $request->metaUser->explodeName();
			}

			$merge_vars = array( 'FNAME' => $name['first'], 'LNAME' => $name['last'] );

			$merge_vars = processor::customParams( $this->settings['custom_details'], $merge_vars, $request );

			$result = $MCAPI->listUpdateMember( $this->settings['list'], $email, $merge_vars, '', false );			
		}
	}
	
	function createUser( $request, $list )
	{
		$xml = '<xmlrequest>
				<username>' . $this->settings['user_name'] . '</username>
				<usertoken>' . $this->settings['user_token'] . '</usertoken>
				<requesttype>subscribers</requesttype>
				<requestmethod>AddSubscriberToList</requestmethod>
				<details>
				<emailaddress>' . $request->metaUser->cmsUser->email . '</emailaddress>
				<mailinglist>' . $list . '</mailinglist>
				<format>html</format>
				<confirmed>yes</confirmed>
				<customfields>
				<item>
				<fieldid>1</fieldid>
				<value>John Smith</value>
				</item>
				</customfields>
				</details>
				</xmlrequest>
				';
		$ch = curl_init( 'http://www.yourdomain.com/IEM/xml.php' );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
		$result = @curl_exec( $ch );
		if( $result === false ) {
			echo "Error performing request";
		} else {
			$xml_doc = simplexml_load_string( $result );
			echo 'Status is ', $xml_doc->status, '<br/>';
			if ( $xml_doc->status == 'SUCCESS' ) {
				echo 'Data is ', $xml_doc->data, '<br/>';
			} else {
				echo 'Error is ', $xml_doc->errormessage, '<br/>';
			}
		}
	}
		
}

?>
