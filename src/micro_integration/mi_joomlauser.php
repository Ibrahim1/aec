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

	function Settings()
	{
		$settings = array();
		$settings['activate'] = array( 'list_yesno' );

		return $settings;
	}

	function action( $request )
	{
		global $database;

		if ( $this->settings['activate'] ) {
			$query = 'UPDATE #__users'
					.' SET `block` = \'0\', `activation` = \'\''
					.' WHERE `id` = \'' . (int) $request->metaUser->userid . '\''
					;
			$database->setQuery( $query );
			$database->query() or die( $database->stderr() );
		}
	}
}

?>