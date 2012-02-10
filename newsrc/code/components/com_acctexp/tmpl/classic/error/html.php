<?php
$app = JFactory::getApplication();

$text = strip_tags( addslashes( nl2br( $text ) ) );

switch ( $mode ) {
	case 2:
		echo "<script>$action</script> \n";
		break;

	case 1:
	default:
		echo "<script>alert('$text'); $action</script> \n";
		echo '<noscript>';
		echo "$text\n";
		echo '</noscript>';
		break;
}

$app->close();
?>
