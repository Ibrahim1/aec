﻿<?php
// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

class mi_livedrive
{

	function Info()
	{
		$info = array();
		$info['name'] = 'LiveDrive MI';
		$info['desc'] = 'Full Description';

		return $info;
	}

	function checkInstallation()
	{
		// THIS FUNCTION IS NOT OBLIGATORY - IF YOU DON'T NEED IT, DON'T USE IT

		// As explained below this checks whether the installation of this
		// feature has already taken place. If that is not the case, we call install below
		// Also check out the below example for a db check for the table that is created
		// within the install function.

		$database = &JFactory::getDBO();

		global $mainframe;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array($mainframe->getCfg( 'dbprefix' )."_acctexp_mi_livedrive", $tables);
	}

	
	function install()
	{
		// THIS FUNCTION IS NOT OBLIGATORY - IF YOU DON'T NEED IT, DON'T USE IT

		// In this function, you can specify what has to be done before you
		// can use this Integration. Common applications could be the creation
		// of a database table (please prefix with "acctexp_mi_" if you care
		// for readability of databases) or the installation of other files.
		// Below is an example how a sample db table creation could look like:

		$database = &JFactory::getDBO();

		$query =	"CREATE TABLE IF NOT EXISTS `#__livedrive_users` (" . "\n" .
					"`id` bigint(20) NOT NULL auto_increment," . "\n" .
					"`userid` bigint(20) NOT NULL," . "\n" .
					"`active` int(4) NOT NULL default '1'," . "\n" .
					"`params` text NULL," . "\n" .
                                        "`user_name` varchar(250) NOT NULL," . "\n" .
		                        "`subscription_date` double NOT NULL," . "\n" .
					"PRIMARY KEY  (`id`)" . "\n" .
					") COLLATE utf8_general_ci;";
		$database->setQuery( $query );
		$database->query();
		return;
	}


	function Settings()
	{
		$settings = array();
		$settings['ld_duration_period']	= array("inputA", "Duration", "description");
		$settings['ld_duration_unit']	= array("fieldset", "Duration Unit", "description3");
		$settings['ld_api_key']			= array("inputE", "Api Key", "description2");
		$settings['user_details']		= array( "inputD", "User Details", "description4" );

		return $settings;
	}

	function action( $request )
	{
		$params = $request->metaUser->meta->custom_params;

		if ( !empty($params['temp_pw']) ) {
			$password = $params['temp_pw'];aecDebug( "Retrieving password: ".$password );

			$request->metaUser->meta->custom_params['is_stored'] = true;

			unset( $request->metaUser->meta->custom_params['temp_pw'] );

			$request->metaUser->meta->storeload();
		}
	}

	function on_userchange_action( $request )
	{
		if ( $request->trace == 'registration' ) {
			$password = $this->getPWrequest( $request );

			$params = $request->metaUser->meta->custom_params;

			if ( empty( $params['is_stored'] ) && empty( $params['temp_pw']) && !empty( $request->row->password ) ) {
				if ( empty( $request->metaUser->meta->id ) ) {
					$request->metaUser->meta->createNew( $request->row->id );
				}
				
				$request->metaUser->meta->custom_params['temp_pw'] = $password;aecDebug( "Storing password: ".$password );
		        $request->metaUser->meta->storeload();
    		}
		}
	}

	function getPWrequest( $request )
	{
		if ( !empty( $request->post['password_clear'] ) ) {
			return $request->post['password_clear'];
		} elseif ( !empty( $request->post['password'] ) ) {
			return $request->post['password'];
		} elseif ( !empty( $request->post['password2'] ) ) {
			return $request->post['password2'];
		} else {
			return "";
		}
	}


}

?>
