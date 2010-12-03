<?php
/**
 * @version $Id: tool_miimport.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - MI Import
 * @copyright 2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class tool_miimport
{
	function Info()
	{
		$info = array();
		$info['name'] = "Micro Integration Import";
		$info['desc'] = "Import one or more MIs that have previously been exported.";

		return $info;
	}

	function Settings()
	{
		$db = &JFactory::getDBO();

		$settings = array();

		if ( !empty( $_FILES ) ) {
			echo file_get_contents($_FILES['import_file']['tmp_name']);exit;

			$content = unserialize( base64_decode( $file ) );

			$settings['count']	= array( 'hidden', base64_encode( serialize( $mi ) ) );

			foreach ( $content as $id => $mi ) {
				$settings[$id.'_data']	= array( 'hidden', base64_encode( serialize( $mi ) ) );
			}
		} else {
			$settings['MAX_FILE_SIZE']	= array( 'hidden', '5120000' );
			$settings['import_file']	= array( 'file', 'Upload', 'Upload a file and select it for importing', '' );
		}

		return $settings;
	}

	function Action()
	{
		$db = &JFactory::getDBO();

		$app = JFactory::getApplication();

		$list = array();
		if ( empty( $_POST['count'] ) ) {
			return null;
		}

		foreach ( $_POST['micro_integrations'] as $mi_id ) {
			$mi = new microIntegration( $db );
			$mi->load( $mi_id );

			$mi->id = 0;

			$mi->clear();

			$list[] = clone( $mi );
		}

		// Generate somewhat unique filename
		$fname = 'aec_mi_export_' . date( 'Y_m_d', ( time() + ( $app->getCfg( 'offset' ) * 3600 ) ) ) . '_' . ( time() - strtotime( date( 'Y_m_d' ) ) );

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");

		header("Content-Type: application/download");
		header('Content-Disposition: inline; filename="' . $fname . '.mi"');

		echo base64_encode( serialize( $list ) );

		exit;
	}

}
?>
