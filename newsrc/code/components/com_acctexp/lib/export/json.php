<?php
class AECexport_json extends AECexport
{
	function AECexport_json()
	{
		$this->lines = array();
	}

	function putln( $array )
	{
		$this->lines[] = $array;
	}

	function finishExport()
	{
		echo json_encode( $this->lines );

		exit;
	}

}
?>
