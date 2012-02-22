<?
$gwnames = PaymentProcessorHandler::getInstalledNameList( true );

if ( count( $gwnames ) && $gwnames[0] && !empty($tmpl->cfg['gwlist']) ) {
	$processors = array();
	foreach ( $gwnames as $procname ) {
		if ( !in_array( $procname, $tmpl->cfg['gwlist'] ) ) {
			continue;
		}

		$processor = trim( $procname );
		$processors[$processor] = new PaymentProcessor();
		if ( $processors[$processor]->loadName( $processor ) ) {
			$processors[$processor]->init();
			$processors[$processor]->getInfo();
			$processors[$processor]->getSettings();
		} else {
			unset( $processors[$processor] );
		}
	}
} else {
	$processors = false;
}

if ( !empty( $processors ) && !empty( $tmpl->cfg['gwlist'] ) ) { ?>
	<p>&nbsp;</p>
	<p><?= JText::_('NOT_ALLOWED_SECONDPAR') ?></p>
	<table id="cc_list">
		<? foreach ( $processors as $processor ) {
			$tmpl->tmpl( 'processor_details' );
		} ?>
	</table>
<? }

 ?>
