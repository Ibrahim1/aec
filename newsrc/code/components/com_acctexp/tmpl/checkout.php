<?php


$user = &JFactory::getUser();

HTML_frontend::aec_styling( $option );

?>
<div class="componentheading"><?php echo $InvoiceFactory->checkout['checkout_title']; ?></div>
<div id="checkout">
	<?php
	if ( $InvoiceFactory->checkout['customtext_checkout_keeporiginal'] && !empty( $InvoiceFactory->checkout['introtext'] ) ) { ?>
		<p><?php echo $InvoiceFactory->checkout['introtext']; ?></p>
		<?php
	}

	if ( $InvoiceFactory->checkout['customtext_checkout'] ) { ?>
		<p><?php echo $InvoiceFactory->checkout['customtext_checkout']; ?></p>
		<?php
	}

	$InvoiceFactory->invoice->deformatInvoiceNumber();

	?>
	<table id="aec_checkout">
	<?php if ( !empty( $InvoiceFactory->cartobject ) && !empty( $InvoiceFactory->cart ) ) { ?>
		<form name="confirmForm" action="<?php echo AECToolbox::deadsureURL( 'index.php?option=' . $option . '&task=cart', $cfg->cfg['ssl_signup'] ); ?>" method="post">
		<div id="update_button">You can always go back to: <input type="image" src="<?php echo JURI::root(true) . '/media/com_acctexp/images/site/your_cart_button.png'; ?>" border="0" name="submit" alt="submit" /></div>
		<?php echo JHTML::_( 'form.token' ); ?>
		</form><br /><br />
	<?php } ?>
	<?php
		foreach ( $InvoiceFactory->items->itemlist as $item ) {
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
							if ( !$cfg->cfg['amount_currency_symbolfirst'] ) {
								$strlen = 2;

								if ( !$cfg->cfg['amount_currency_symbol'] ) {
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

	<?php
	if ( !empty( $InvoiceFactory->checkout['enable_coupons'] ) ) { ?>
		<table width="100%" id="couponsbox">
			<tr>
				<td class="couponinfo">
					<strong><?php echo JText::_('CHECKOUT_COUPON_CODE'); ?></strong>
				</td>
			</tr>
			<?php
			if ( !empty( $InvoiceFactory->errors ) ) {
				foreach ( $InvoiceFactory->errors as $err ) { ?>
				<tr>
					<td class="couponerror">
						<p><strong><?php echo JText::_('COUPON_ERROR_PRETEXT'); ?></strong>&nbsp;<?php echo $err; ?></p>
					</td>
				</tr>
				<?php
				}
			} ?>
			<tr>
				<td class="coupondetails">
					<p><?php echo JText::_('CHECKOUT_COUPON_INFO'); ?></p>
					<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceAddCoupon', $cfg->cfg['ssl_signup'] ); ?>" method="post">
						<input type="text" size="20" name="coupon_code" class="inputbox" value="" />
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="task" value="InvoiceAddCoupon" />
						<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number; ?>" />
						<input type="submit" class="button" value="<?php echo JText::_('BUTTON_APPLY'); ?>" />
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
				</td>
			</tr>
		</table>
		<?php
	}

	$makegift = false;

	if ( !empty( $cfg->cfg['checkout_as_gift'] ) ) {
		if ( !empty( $cfg->cfg['checkout_as_gift_access'] ) ) {
			if ( $InvoiceFactory->metaUser->hasGroup( $cfg->cfg['checkout_as_gift_access'] ) ) {
				$makegift = true;
			}
		} else {
			$makegift = true;
		}
	}

	if ( $makegift ) { ?>
		<table width="100%" id="giftbox">
			<tr>
				<td class="couponinfo">
					<strong><?php echo JText::_('CHECKOUT_GIFT_HEAD'); ?></strong>
				</td>
			</tr>
			<tr>
				<td class="giftdetails">
					<?php if ( !empty( $InvoiceFactory->invoice->params['target_user'] ) ) { ?>
						<p>This purchase will be gifted to: <?php echo $InvoiceFactory->invoice->params['target_username']; ?> (<a href="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&task=InvoiceRemoveGift&invoice='.$InvoiceFactory->invoice->invoice_number.'&'. JUtility::getToken() .'=1', $cfg->cfg['ssl_signup'] ); ?>">undo?</a>)</p>
					<?php } else { ?>
					<p><?php echo JText::_('CHECKOUT_GIFT_INFO'); ?></p>
					<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceMakeGift', $cfg->cfg['ssl_signup'] ); ?>" method="post">
						<input type="text" size="20" name="user_ident" class="inputbox" value="" />
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="task" value="InvoiceMakeGift" />
						<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number; ?>" />
						<input type="submit" class="button" value="<?php echo JText::_('BUTTON_APPLY'); ?>" />
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
					<?php } ?>
				</td>
			</tr>
		</table>
		<?php
	}

	if ( !empty( $params ) ) { ?>
		<table width="100%" id="paramsbox">
			<tr>
				<td class="append_button">
					<form action="<?php echo AECToolbox::deadsureURL( 'index.php?option=com_acctexp&amp;task=InvoiceAddParams', $cfg->cfg['ssl_signup'] ); ?>" method="post">
						<?php echo $params; ?>
						<input type="hidden" name="option" value="<?php echo $option; ?>" />
						<input type="hidden" name="task" value="InvoiceAddParams" />
						<input type="hidden" name="invoice" value="<?php echo $InvoiceFactory->invoice->invoice_number; ?>" />
						<input type="submit" class="button" value="<?php echo JText::_('BUTTON_APPEND'); ?>" />
						<?php echo JHTML::_( 'form.token' ); ?>
					</form>
				</td>
			</tr>
		</table>
		<?php
	}

if ( is_string( $var ) ) {
	$var = trim ( $var );

	if ( $var == "<p></p>" ) {
		$var = null;
	}
}

if ( !empty( $var ) ) { ?>
<table width="100%" id="checkoutbox">
	<?php if ( ( strpos( $var, '<tr class="aec_formrow">' ) !== false ) || is_string( $InvoiceFactory->display_error ) ) { ?>
		<tr><th class="checkout_head"><?php echo $InvoiceFactory->checkout['customtext_checkout_table']; ?></th></tr>
	<?php } ?>
<?php if ( is_string( $InvoiceFactory->display_error ) ) { ?>
	<tr>
		<td class="checkout_error">
			<p><?php echo JText::_('CHECKOUT_ERROR_EXPLANATION') . ":"; ?></p>
			<p><strong><?php echo $InvoiceFactory->display_error; ?></strong></p>
			<p><?php echo JText::_('CHECKOUT_ERROR_FURTHEREXPLANATION'); ?></p>
		</td>
	</tr>
<?php } ?>
<?php if ( !empty( $InvoiceFactory->checkout['processor_addin'] ) ) { ?>
	<tr>
		<td class="checkout_processor_addin">
			<?php echo $InvoiceFactory->checkout['processor_addin']; ?>
		</td>
	</tr>
<?php } ?>
<?php if ( is_string( $var ) ) { ?>
	<tr>
		<td class="checkout_action">
			<?php print $var; ?>
		</td>
	</tr>
<?php } ?>
<?php } ?>
</table>
<table width="100%">
	<tr><td>
		<?php
		if ( !empty( $InvoiceFactory->pp ) ) {
			if ( is_object( $InvoiceFactory->pp ) ) {
				HTML_frontEnd::processorInfo( $option, $InvoiceFactory->pp, $cfg->cfg['displayccinfo'] );
			}
		}
		?>
	</td></tr>
</table>
</div>
<div class="aec_clearfix"></div>
<?php
?>
