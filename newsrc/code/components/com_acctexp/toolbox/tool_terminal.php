<?php
/**
 * @version $Id: tool_terminal.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - Terminal
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_terminal
{
	function Info()
	{
		$info = array();
		$info['name'] = "Terminal";
		$info['desc'] = "Fire off exotic queries. Consulting the manual is pretty much mandatory. Sorry about that.";

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();

		if ( !empty( $_POST['type'] ) && !empty( $_POST['id'] ) && empty( $_POST['edit'] ) ) {
			$db = &JFactory::getDBO();

			$settings['edit']	= array( 'hidden', 1 );
			$settings['type']	= array( 'hidden', $_POST['type'] );

			$fixed = array();

			switch ( $_POST['type'] ) {
				case 'metauser':
					$fixed = array( 'userid' );

					$object = new metaUserDB( $db );

					$_POST['id'] = $object->getIDbyUserid( $_POST['id'] );
					break;
				case 'processor':
					if ( !is_numeric( $_POST['id'] ) )  {
						$query = 'SELECT `id`'
								. ' FROM #__acctexp_config_processors'
								. ' WHERE `name` = \'' . $db->getEscaped( $_POST['id'] ) . '\''
								;
						$db->setQuery( $query );

						$_POST['id'] = $db->loadResult();
					}

					$object = new processor( $db );
					break;
				case 'invoice':
					if ( !is_numeric( $_POST['id'] ) )  {
						$_POST['id'] = AECfetchfromDB::InvoiceIDfromNumber( $_POST['id'] );
					}

					$object = new Invoice( $db );
					break;
			}

			$object->load( $_POST['id'] );

			$vars = get_object_vars( $object );

			$encoded = $object->declareParamFields();

			foreach ( $vars as $k => $v ) {
				if ( is_null( $k ) ) {
					$k = "";
				}

				if ( $k == 'id' ) {
					$settings['id']	= array( 'hidden', $v );
				} elseif ( in_array( $k, $fixed ) ) {
					$settings[$k]	= array( 'p', $k, $k, $v );
				} elseif ( in_array( $k, $encoded ) ) {
					$v = jsoonHandler::encode( $v );

					if ( $v === "null" ) {
						$v = "";
					}

					$settings[$k]	= array( 'inputD', $k, $k, $v );
				} elseif ( strpos( $k, '_' ) !== 0 ) {
					$settings[$k]	= array( 'inputD', $k, $k, $v );
				}
			}
		} else {
			$settings['type']	= array( 'list', 'Item Type', 'The type of Item you want to edit' );
			$settings['id']		= array( 'inputC', 'Item ID', 'Identification for your Item' );

			$types = array( 'metauser' => 'MetaUser Information', 'processor' => 'Payment Processor', 'invoice' => 'Invoice' );

			$typelist = array();
			foreach ( $types as $type => $typename ) {
				$typelist[] = JHTML::_('select.option', $type, $typename );
			}

			$settings['lists']['type'] = JHTML::_('select.genericlist', $typelist, 'type', 'size="3"', 'value', 'text', array());
		}

		return $settings;
	}

	function Action()
	{
		if ( empty( $_POST['query'] ) ) {
			return null;
		}

		$_POST['query'] = trim( aecGetParam( 'search', 0 ) );


	if ( strpos( $_POST['query'], 'supercommand:' ) !== false ) {
		$supercommand = new aecSuperCommand();

		if ( $supercommand->parseString( $_POST['query'] ) ) {
			if ( strpos( $_POST['query'], '!' ) === 0 ) {
				$armed = true;
			} else {
				$armed = false;
			}

			$return = $supercommand->query( $armed );

			if ( $return > 1 ) {
				$multiple = true;
			} else {
				$multiple = false;
			}

			if ( ( $return != false ) && !$armed ) {
				$r['search'] = "!" . $_POST['query'];

				$r['return'] = '<div style="font-size:110%;border: 2px solid #da5;padding:16px;">This supercommand would affect ' . $return . " user" . ($multiple ? "s":"") . ". Click the query button again to carry out the command.</div>";
			} elseif ( $return != false ) {
				$r['search'] = "";
				$r['return'] = '<div style="font-size:110%;border: 2px solid #da5;padding:16px;">If you\'re so clever, you tell us what <strong>colour</strong> it should be!? (Everything went fine. Really! It affected ' . $return . " user" . ($multiple ? "s":"") . ")</div>";
			} else {
				$r['search'] = "";
				$r['return'] = '<div style="font-size:110%;border: 2px solid #da5;padding:16px;">Something went wrong. No users found.</div>';
			}

			return $r;
		}

		return "I think you ought to know I'm feeling very depressed. (Something was wrong with your query.)";
	}

		if ( strpos( $_POST['query'], 'jsonserialencode' ) === 0 ) {
			$s = trim( substr( $searcc, 16 ) );
			if ( !empty( $s ) ) {
				$return = base64_encode( serialize( jsoonHandler::decode( $s ) ) );
				return '<div style="text-align:left;">' . $return . '</div>';
			}
		}

		if ( strpos( $_POST['query'], 'serialdecodejson' ) === 0 ) {
			$s = trim( substr( $searcc, 16 ) );
			if ( !empty( $s ) ) {
				$return = jsoonHandler::encode( unserialize( base64_decode( $s ) ) );
				return '<div style="text-align:left;">' . $return . '</div>';
			}
		}

		if ( strpos( $_POST['query'], 'serialdecode' ) === 0 ) {
			$s = trim( substr( $searcc, 12 ) );
			if ( !empty( $s ) ) {
				$return = unserialize( base64_decode( $s ) );
				return '<div style="text-align:left;">' . obsafe_print_r( $return, true, true ) . '</div>';
			}
		}

		if ( strpos( $_POST['query'], 'unserialize' ) === 0 ) {
			$s = trim( substr( $searcc, 11 ) );
			if ( !empty( $s ) ) {
				$return = unserialize( $s );
				return '<div style="text-align:left;">' . obsafe_print_r( $return, true, true ) . '</div>';
			}
		}

		$maybe = array( '?', '??', '???', '????', 'what to do', 'need strategy', 'help', 'help me', 'huh?', 'AAAAH!' );

		if ( in_array( $_POST['query'], $maybe ) ) {
			include_once( JPATH_SITE . '/components/com_acctexp/lib/eucalib/eucalib.add.php' );

			$ed = ( rand( 1, 4 ) );
			$edf = ${'edition_0' . $ed};
			$maxed = count( ${'edition_0' . $ed} );

			return $edf['quote_' . str_pad( rand( 1, ( $maxed + 1 ) ), 2, '0' )];
		}

		if ( strpos( $_POST['query'], 'logthis:' ) === 0 ) {
			$eventlog = new eventLog( $db );
			$eventlog->issue( 'debug', 'debug', 'debug entry: '.str_replace( 'logthis:', '', $_POST['query'] ), 128 );

			return 'alright, logged.';
		}

	}

}
?>
