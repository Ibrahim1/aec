<?php
/**
 * @version $Id: tool_miimport.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Toolbox - MI Import
 * @copyright 2011-2015 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.3 http://www.gnu.org/licenses/gpl.html or, at your option, any later version
 */

// Dont allow direct linking
defined('_JEXEC') or die( 'Direct Access to this location is not allowed.' );

class tool_miimport
{
	public function Info()
	{
		$info = array();
		$info['name'] = "Micro Integration Import";
		$info['desc'] = "Import one or more MIs that have previously been exported.";

		return $info;
	}

	public function Settings()
	{
		$settings = array();

		if ( !empty( $_FILES ) ) {
			$file = file_get_contents($_FILES['import_file']['tmp_name']);

			$content = unserialize( base64_decode( $file ) );

			$settings['count']	= array( 'hidden', count( $content ) );

			foreach ( $content as $id => $mi ) {
				$settings[]						= array( 'section-head', 'Import #'.$id.' '.$mi->name);
				$settings[]						= array( 'p', $mi->desc);

				$settings[$id.'_data_import']	= array( 'toggle', 'Import this', '', 1 );
				$settings[$id.'_data']			= array( 'hidden', base64_encode( serialize( $mi ) ) );
			}
		} else {
			$settings['MAX_FILE_SIZE']	= array( 'hidden', '5120000' );
			$settings['import_file']	= array( 'file', 'Upload', 'Upload a file and select it for importing', '' );
		}

		return $settings;
	}

	public function Action()
	{
		if ( empty( $_POST['count'] ) ) {
			return null;
		}

		$count = 0;
		for ( $i=0; $i<$_POST['count']; $i++ ) {
			if ( !empty( $_POST[$i.'_data_import'] ) && !empty( $_POST[$i.'_data'] ) ) {
				$src = unserialize( base64_decode( $_POST[$i.'_data'] ) );

				$src->clear();

				$mi = new microIntegration();
				$mi->mergeParams( $mi, $src );

				$mi->check();
				if ( $mi->store() ) {
					$count++;
				}
			}
		}

		return "<h3>Success! " . $count . " MIs imported.</h3>";
	}

}
