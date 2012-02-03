<?php
class AECexport_ods extends AECexport
{
	function AECexport_ods()
	{
		$this->lines = array();
	}

	function finishExport()
	{
		include_once( JPATH_SITE . '/components/com_acctexp/lib/ods-php/ods.php' );
		$ods = newOds();

		if ( !empty( $this->description ) && !empty( $this->sum ) ) {
			$export = array();
			$export['description'] = $this->description;
			$export[$this->type[0]] = $this->lines;
			$export['sum'] = $this->sum;

			$this->array_to_xml( $export, $xml );
		} else {
			$this->array_to_xml( $this->lines, $xml );
		}

		$fname = 'aecexport_' . urlencode( stripslashes( $this->name ) ) . '_' . date( 'Y_m_d', ( (int) gmdate('U') ) );

		saveOds($ods,'/tmp/'.$fname . '.' . $this->params['export_method']);

		exit;
	}

}
?>
