<?php
// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mi_joomlauser
{
	function Info()
	{
		$info = array();
		$info['name'] = _AEC_MI_NAME_JOOMLAUSER;
		$info['desc'] = _AEC_MI_DESC_JOOMLAUSER;

		return $info;
	}

	function Settings( $params )
	{
		$settings = array();
		$settings['activate'] = array( 'list_yesno' );

		return $settings;
	}

	function action( $params, $metaUser, $invoice, $plan )
	{
		global $database;

		if ( $params['activate'] ) {
			$query = 'UPDATE #__users'
			.' SET block = \'0\', activation = \'\''
			.' WHERE id = \'' . (int) $metaUser->userid . '\'';
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		}
	}

	function on_userchange_action( $params, $row, $post, $trace )
	{

	}

}

?>