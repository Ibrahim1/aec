<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_example {

	function checkInstallation()
	{
		// As explained below this checks whether the installation of this
		// feature has already taken place. If that is not the case, we call install below
		// Also check out the below example for a db check for the table that is created
		// within the install function.

		global $database, $mosConfig_dbprefix;

		$tables	= array();
		$tables	= $database->getTableList();

		return in_array($mosConfig_dbprefix."_acctexp_mi_sampletable", $tables);
	}

	function install()
	{
		// In this function, you can specify what has to be done before you
		// can use this Integration. Common applications could be the creation
		// of a database table (please prefix with "acctexp_mi_" if you care
		// for readability of databases) or the installation of other files.
		// Below is an example how a sample db table creation could look like:

		global $database;

		$query =	"CREATE TABLE IF NOT EXISTS `#__acctexp_mi_sampletable` (" . "\n" .
					"`id` int(11) NOT NULL auto_increment," . "\n" .
					"`userid` int(11) NOT NULL," . "\n" .
					"`active` int(4) NOT NULL default '1'," . "\n" .
					"`params` text NULL," . "\n" .
					"PRIMARY KEY  (`id`)" . "\n" .
					")";
		$database->setQuery( $query );
		$database->query();
		return;
	}

	function Settings( $params )
	{
		// Here you create an array of standard variables for your integration.
		// I didn't want to go through the trouble to have you create your own
		// settings tab here, so this is a standardized one-size-fits-all
		// automatically built thing. Refer to the admin.acctexp.html.php function
		// HTML_myCommon::createSettingsParticle to see what types of entries are possible.
		// Be sure to give them unique variable names or you they won't save correctly
		// in the best, or create an error in the worst case.

		// remember to link the params array correctly to the settings - call on the same name

		// Syntax: ['name'] => Field Type || Field Name || Field Description
		$settings = array();
		$settings['param_name1'] = array("inputA", "Name", "description");
		$settings['param_name2'] = array("inputD", "SET", "description2");

		return $settings;
	}

	function pre_expiration_action( $params, $userid, $plan, $mi_id )
	{
		// Here you can specify whatever you want to happen when the plan runs out.
	}

	function expiration_action( $params, $userid, $plan )
	{
		// Here you can specify whatever you want to happen before the plan runs out.
	}

	function action( $params, $userid, $plan )
	{
		// And here what should happen when the plan is applied.
		// Note that both functions always receive the full parameter array
		// as well as the current user ID. So parameters are accessed by
		// $param['var_name'] and of course have the same variable name
		// that you applied to them in the Settings function.
	}

	function on_userchange_action( $params, $row, $post, $trace )
	{
		// If your integration relies on knowing the username and password,
		// you can change what you saved with this function when the user is changed
		// trace can be either 'registration' for a account creation on registration.
		// 'user' for a change by a user in his/her profile
		// 'adminuser' for a change by the admin in the backend
	}

	function delete( $params )
	{
		// Trigger an action here in case the MI is deleted.
	}

	function profile_info( $params, $userid )
	{
		// Return Info to the MySubscription page of this user
	}

}

// And here you can of course include your own code. If you need to include whole php files,
// please do so within subfolders that are named like your php micro integration file.

?>