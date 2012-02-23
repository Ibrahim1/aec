<?
/**
 * @version $Id: itemlist.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Main Frontend
 * @copyright 2012 Copyright (C) David Deutsch
 * @author David Deutsch <skore@valanx.org> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' ) ?>

<table id="aec_checkout">
<?php if ( !empty( $InvoiceFactory->cartobject ) && !empty( $InvoiceFactory->cart ) ) { ?>
	<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cart', $tmpl->cfg['ssl_signup'] ) ?>" method="post">
	<div id="update_button">You can always go back to: <input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/your_cart_button.png' ?>" border="0" name="submit" alt="submit" /></div>
	<?php echo JHTML::_( 'form.token' ) ?>
	</form><br /><br />
<?php } ?>
<?php foreach ( $InvoiceFactory->items->itemlist as $item ) {
		if ( !empty( $item['terms'] ) ) {
			$terms = $item['terms']->getTerms();
		} else {
			continue;
		}

		$add = "";

		if ( isset( $item['quantity'] ) ) {
			if ( $item['quantity'] > 1 ) {
				$add = " (x" . $item['quantity'] . ")";
			}
		}

		if ( isset( $item['name'] ) ) {
			// This is an item, show its name
			echo '<tr><td><h4>' . $item['name'] . $add . '</h4></td></tr>';
		}

		if ( isset( $item['desc'] ) && $InvoiceFactory->checkout['checkout_display_descriptions'] ) {
			// This is an item, show its description
			echo '<tr><td>' . $item['desc'] . '</td></tr>';
		}

		foreach ( $terms as $tid => $term ) {
			if ( !is_object( $term ) ) {
				continue;
			}

			$ttype = 'aec_termtype_' . $term->type;

			$applicable = ( $tid >= $item['terms']->pointer ) ? '' : '&nbsp;('.JText::_('AEC_CHECKOUT_NOTAPPLICABLE').')';

			$current = ( $tid == $item['terms']->pointer ) ? ' current_period' : '';

			// Headline - What type is this term
			echo '<tr class="aec_term_typerow' . $current . '"><th colspan="2" class="' . $ttype . '">' . JText::_( strtoupper( $ttype ) ) . $applicable . '</th></tr>';

			if ( !isset( $term->duration['none'] ) && empty( $item['params']['hide_duration_checkout'] ) ) {
				// Subheadline - specify the details of this term
				echo '<tr class="aec_term_durationrow' . $current . '"><td colspan="2" class="aec_term_duration">' . JText::_('AEC_CHECKOUT_DURATION') . ': ' . $term->renderDuration() . '</td></tr>';
			}

			// Iterate through costs
			foreach ( $term->renderCost() as $citem ) {
				$t = JText::_( strtoupper( 'aec_checkout_' . $citem->type ) );

				if ( isset( $item['quantity'] ) ) {
					$amount = AECToolbox::correctAmount( $citem->cost['amount'] * $item['quantity'] );
				} else {
					$amount = AECToolbox::correctAmount( $citem->cost['amount'] );
				}

				$c = AECToolbox::formatAmount( $amount, $InvoiceFactory->payment->currency );

				switch ( $citem->type ) {
					case 'discount':
						$ta = $t;
						if ( !empty( $citem->cost['details'] ) ) {
							$ta .= '&nbsp;(' . $citem->cost['details'] . ')';
						}

						if ( !empty( $citem->cost['coupon'] ) ) {
							$ta .= '&nbsp;[<a href="'
								. AECToolbox::deadsureURL( 'index.php?option=' . $option
								. '&amp;task=InvoiceRemoveCoupon&amp;invoice=' . $InvoiceFactory->invoice->invoice_number
								. '&amp;coupon_code=' . $citem->cost['coupon'] )
								. '" title="' . JText::_('CHECKOUT_INVOICE_COUPON_REMOVE') . '">'
								. JText::_('CHECKOUT_INVOICE_COUPON_REMOVE') . '</a>]';
						}

						$t = $ta;

						// Strip out currency symbol and replace with blanks
						if ( !$tmpl->cfg['amount_currency_symbolfirst'] ) {
							$strlen = 2;

							if ( !$tmpl->cfg['amount_currency_symbol'] ) {
								$strlen = 1 + strlen( $InvoiceFactory->payment->currency ) * 2;
							}

							for( $i=0; $i<=$strlen+1;$i++ ) {
								//$c .= '&nbsp;';
							}
						}
						break;
					case 'tax':
						if ( !empty( $citem->cost['details'] ) ) {
							$t .= '&nbsp;( ' . $citem->cost['details'] . ' )';
						}
						break;
					case 'cost':
						if ( !empty( $citem->cost['details'] ) ) {
							$t = $citem->cost['details'];
						}
					break;
					case 'total': break;
					default: break;
				}

				echo '<tr class="aec_term_' . $citem->type . 'row' . $current . '"><td class="aec_term_' . $citem->type . 'title">' . $t . ':' . '</td><td class="aec_term_' . $citem->type . 'amount">' . $c . '</td></tr>';
			}

			// Draw Separator Line
			echo '<tr class="aec_term_row_sep"><td colspan="2"></td></tr>';
		}
	}

	if ( count( $InvoiceFactory->items->itemlist ) > 1 ) {
		echo '<tr class="aec_term_row_sep"><td colspan="2"></td></tr>';
		echo '<tr class="aec_term_totalhead current_period"><th colspan="2" class="' . $ttype . '">' . JText::_('CART_ROW_TOTAL') . '</th></tr>';

		if ( !empty( $InvoiceFactory->items->total ) ) {
			$c = AECToolbox::formatAmount( $InvoiceFactory->items->total->renderCost(), $InvoiceFactory->payment->currency );

			echo '<tr class="aec_term_costrow current_period"><td class="aec_term_totaltitle">' . JText::_('AEC_CHECKOUT_TOTAL') . ':' . '</td><td class="aec_term_costamount">' . $c . '</td></tr>';
		}

		if ( !empty( $InvoiceFactory->items->discount ) ) {
			// Iterate through full discounts
			foreach ( $InvoiceFactory->items->discount as $citems ) {
				foreach ( $citems as $ccitem ) {
					$citem = $ccitem->renderCost();

					foreach ( $citem as $cost ) {
						if ( $cost->type == 'discount' ) {
							$t = JText::_( strtoupper( 'aec_checkout_' . $cost->type ) );

							$amount = AECToolbox::correctAmount( $cost->cost['amount'] );

							$c = AECToolbox::formatAmount( $amount, $InvoiceFactory->payment->currency );

							if ( !empty( $cost->cost['details'] ) ) {
								$t .= '&nbsp;(' . $cost->cost['details'] . ')';
							}

							if ( !empty( $cost->cost['coupon'] ) ) {
								$t .= '&nbsp;[<a href="'
									. AECToolbox::deadsureURL( 'index.php?option=' . $option
									. '&amp;task=InvoiceRemoveCoupon&amp;invoice=' . $InvoiceFactory->invoice->invoice_number
									. '&amp;coupon_code=' . $cost->cost['coupon'] )
									. '" title="' . JText::_('CHECKOUT_INVOICE_COUPON_REMOVE') . '">'
									. JText::_('CHECKOUT_INVOICE_COUPON_REMOVE') . '</a>]';
							}

							echo '<tr class="aec_term_' . $cost->type . 'row current_period"><td class="aec_term_' . $cost->type . 'title">' . $t . ':' . '</td><td class="aec_term_' . $cost->type . 'amount">' . $c . '</td></tr>';
						}
					}
				}
			}
		}

		if ( !empty( $InvoiceFactory->items->tax ) ) {
			// Iterate through taxes
			foreach ( $InvoiceFactory->items->tax as $titems ) {
				foreach ( $titems['terms']->terms as $titem ) {
					$citem = $titem->renderCost();

					foreach ( $citem as $cost ) {
						if ( $cost->type == 'tax' ) {
							$t = JText::_( strtoupper( 'aec_checkout_' . $cost->type ) );

							$amount = AECToolbox::correctAmount( $cost->cost['amount'] );

							$c = AECToolbox::formatAmount( $amount, $InvoiceFactory->payment->currency );

							if ( !empty( $cost->cost['details'] ) ) {
								$t .= '&nbsp;( ' . $cost->cost['details'] . ' )';
							}

							echo '<tr class="aec_term_' . $cost->type . 'row current_period"><td class="aec_term_' . $cost->type . 'title">' . $t . ':' . '</td><td class="aec_term_' . $cost->type . 'amount">' . $c . '</td></tr>';
						}
					}
				}
			}

		}

		if ( !empty( $InvoiceFactory->items->grand_total ) ) {
			$c = AECToolbox::formatAmount( $InvoiceFactory->items->grand_total->renderCost(), $InvoiceFactory->payment->currency );

			echo '<tr class="aec_term_totalrow current_period"><td class="aec_term_totaltitle">' . JText::_('AEC_CHECKOUT_GRAND_TOTAL') . ':' . '</td><td class="aec_term_totalamount">' . $c . '</td></tr>';
		}

		echo '<tr class="aec_term_row_sep"><td colspan="2"></td></tr>';
	}
?>
</table>
