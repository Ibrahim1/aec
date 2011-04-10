<?php
/**
 * @version $Id: brazilian_portuguese.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Language - Frontend - English
 * @copyright 2006-2010 Copyright (C) David Deutsch
 * @author David Deutsch <skore@skore.de> & Team AEC - http://www.valanx.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
( defined('_JEXEC') || defined( '_VALID_MOS' ) ) or die( 'Direct Access to this location is not allowed.' );

if( defined( '_AEC_LANG' ) ) {
	return;
}

define( '_AEC_EXPIRE_TODAY',	'Esta conta est&aacute; activa at&eacute; o dia de hoje' );
define( '_AEC_EXPIRE_FUTURE',	'Esta conta est&aacute; activa at&eacute;' );
define( '_AEC_EXPIRE_PAST',	'Esta conta &eacute; v&aacute;lida at&eacute;' );
define( '_AEC_DAYS_ELAPSED',	'dia(s) decorridos');
define( '_AEC_EXPIRE_TRIAL_TODAY',	'Est&aacute; demontra&ccedil;&atilde;o est&aacute; activa at&eacute; hoje' );
define( '_AEC_EXPIRE_TRIAL_FUTURE',	'Est&aacute; demontra&ccedil;&atilde;o est&aacute; activa at&eacute;' );
define( '_AEC_EXPIRE_TRIAL_PAST',	'Est&aacute; demonstra&ccedil;&atilde;o est&aacute; v&aacute;lida at&eacute;' );

define( '_AEC_EXPIRE_NOT_SET',	'N&atilde;o Definido' );
define( '_AEC_GEN_ERROR',	' Erro Geral: Houve um problema com o processamento do seu pedido. Por favor contacte o Administrador do Site.' );

// payments
define( '_AEC_PAYM_METHOD_FREE',	'Gr&aacute;tis' );
define( '_AEC_PAYM_METHOD_NONE',	'Nenhum' );
define( '_AEC_PAYM_METHOD_TRANSFER',	'Transfer&ecirc;ncia' );

// processor errors
define( '_AEC_MSG_PROC_INVOICE_FAILED_SH',	'Falha no processamento da Factura' );
define( '_AEC_MSG_PROC_INVOICE_FAILED_EV',	'Processamento de notifica&ccedil;&atilde;o %s para %s falhou - o n&uacute;mero do recibo n&atilde;o existe:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_SH',	'Ac&ccedil;&atilde;o Factura do Pagamento' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV',	'Resposta a Notifica&ccedil;&atilde;o de Pagamento:' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_STATUS',	'Estado da Factura' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_FRAUD',	'Falhou a verifica&ccedil;&atilde;o do valor, pago: %s, factura: %s - pagamento abortado' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CURR',	'Falou a verifica&ccedil;&atilde;o da moeda, pago %s, factura: %s - pagamento abortado' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID',	'Pagamento v&aacute;lido, ac&ccedil;&atilde;o realizada' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_VALID_APPFAIL',	'Pagamento v&aacute;lido, ac&ccedil;&atilde;o falhou!' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_TRIAL',	'Pagamento v&aacute;lido - demonstra&ccedil;&atilde;o gratuita' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_PEND',	'Pagamento invalido - est&aacute; pendente, raz&atilde;o: %s' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CANCEL',	'Sem pagamento - inscri&ccedil;&atilde;o Cancelada' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK',	'Sem Pagamento - Estorno' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_CHARGEBACK_SETTLE',	'Sem Pagamento - Estorno da Liquida&ccedil;&atilde;o' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS',	', O estado do utilizador foi actualizado para \'Cancelado\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_HOLD',	', O estado do utilizador foi actualizado para \'Em Espera\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_USTATUS_ACTIVE',	', O estado do utilizador foi actualizado para \'Activo\'' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EOT',	'Sem Pagamento - Fim da inscri&ccedil;&atilde;o' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_DUPLICATE','Sem pagamento - Duplicado' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_NULL','Sem Pagamento - Nulo' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_U_ERROR',	'Erro Desconhecido' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_REFUND',	'Sem Pagamento - inscri&ccedil;&atilde;o Eliminada (retorno)' );
define( '_AEC_MSG_PROC_INVOICE_ACTION_EV_EXPIRED',	', Utilizador expirou' );

// --== PAYMENT PLANS PAGE ==--
define( '_PAYPLANS_HEADER', 'Planos de Pagamento');
define( '_NOPLANS_ERROR', 'N&atilde;o existem planos de pagamento dispon&iacute;veis. Por favor contacte o administrador.');
define( '_NOPLANS_AUTHERROR', 'You are not authorized to access this option. Please contact administrator if you have any further questions.');
define( '_PLANGROUP_BACK', '&lt; Go back');

// --== ACCOUNT DETAILS PAGE ==--
define( '_CHK_USERNAME_AVAIL', 'O Nome de Utilizador %s n&atilde;o dispon&iacute;vel');
define( '_CHK_USERNAME_NOTAVAIL', 'O Nome de Utilizador %s j&aacute; se encontra atribuido!');

// --== MY SUBSCRIPTION PAGE ==--
define( '_MYSUBSCRIPTION_TITLE', 'Minha Conta');
define( '_MEMBER_SINCE', 'Membro desde');
define( '_HISTORY_COL1_TITLE', 'Factura');
define( '_HISTORY_COL2_TITLE', 'Valor');
define( '_HISTORY_COL3_TITLE', 'Data de Pagamento');
define( '_HISTORY_COL4_TITLE', 'Metodo');
define( '_HISTORY_COL5_TITLE', 'Ac&ccedil;&atilde;o');
define( '_HISTORY_COL6_TITLE', 'Plano');
define( '_HISTORY_ACTION_REPEAT', 'pagar');
define( '_HISTORY_ACTION_CANCEL', 'cancelar');
define( '_RENEW_LIFETIME', 'Voc&ecirc; tem um tempo &uacute;til de inscri&ccedil;&atilde;o.');
define( '_RENEW_DAYSLEFT', 'Dias em Falta');
define( '_RENEW_DAYSLEFT_TRIAL', 'Dias em Falta da Demonstra&ccedil;&atilde;o');
define( '_RENEW_DAYSLEFT_EXCLUDED', 'Est&aacute; exclu&iacute;do por expira&ccedil;&atilde;o');
define( '_RENEW_DAYSLEFT_INFINITE', '&infin;');
define( '_RENEW_INFO', 'Est&aacute; a utilizar pagamentos recorrentes.');
define( '_RENEW_OFFLINE', 'Renovar');
define( '_RENEW_BUTTON_UPGRADE', 'Upgrade');
define( '_PAYMENT_PENDING_REASON_ECHECK', 'Confirma&ccedil;&atilde;o n&atilde;o esclarecida (1-4 dias uteis)');
define( '_PAYMENT_PENDING_REASON_TRANSFER', 'aguardando pagamento por transfer&ecirc;ncia');
define( '_YOUR_SUBSCRIPTION', 'A sua inscri&ccedil;&atilde;o');
define( '_YOUR_FURTHER_SUBSCRIPTIONS', 'Outras Inscri&ccedil;&otilde;es');
define( '_PLAN_PROCESSOR_ACTIONS', 'Para isto, tem as seguintes op&ccedil;&otilde;es:');
define( '_AEC_SUBDETAILS_TAB_OVERVIEW', 'Vis&atilde;o Geral');
define( '_AEC_SUBDETAILS_TAB_INVOICES', 'Facturas');
define( '_AEC_SUBDETAILS_TAB_DETAILS', 'Detalhes');

define( '_HISTORY_ACTION_PRINT', 'print');
define( '_INVOICEPRINT_DATE', 'Date');
define( '_INVOICEPRINT_ID', 'ID');
define( '_INVOICEPRINT_REFERENCE_NUMBER', 'Reference Number');
define( '_INVOICEPRINT_ITEM_NAME', 'Item Name');
define( '_INVOICEPRINT_UNIT_PRICE', 'Unit Price');
define( '_INVOICEPRINT_QUANTITY', 'Quantity');
define( '_INVOICEPRINT_TOTAL', 'Total');
define( '_INVOICEPRINT_GRAND_TOTAL', 'Grand Total');

define( '_INVOICEPRINT_ADDRESSFIELD', 'Enter your Address here - it will then show on the printout.');
define( '_INVOICEPRINT_PRINT', 'Print');
define( '_INVOICEPRINT_BLOCKNOTICE', 'This block (including the text field and print button) will not show on your printout.');
define( '_INVOICEPRINT_PRINT_TYPEABOVE', 'Please type your address into the field above.');
define( '_INVOICEPRINT_PAIDSTATUS_UNPAID', '<strong>This invoice has not been paid yet.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_CANCEL', '<strong>This payment was canceled.</strong>');
define( '_INVOICEPRINT_PAIDSTATUS_PAID', 'This invoice has been paid on: %s');
define( '_INVOICEPRINT_RECURRINGSTATUS_RECURRING', 'This invoice is billed on a recurring basis. The invoice amount listing may represent that of the next billing cycle, not of the one that has been paid for last. The list of payments above clarifies what has been paid and when.');
define( '_INVOICEPRINT_RECURRINGSTATUS_ONCE', 'This invoice involves multiple, separate, payments. The invoice amount listing may represent that of the next billing cycle, not of the one that has been paid for last. The list of payments above clarifies what has been paid and when.');

define( '_AEC_YOUSURE', 'Are you sure?');

define( '_AEC_WILLEXPIRE', 'This membership will expire');
define( '_AEC_WILLRENEW', 'This membership will renew');
define( '_AEC_ISLIFETIME', 'Lifetime Membership');

// --== EXPIRATION PAGE ==--
define( '_EXPIRE_INFO', 'A sua Conta est&aacute; activa at&eacute;');
define( '_RENEW_BUTTON', 'Renovar Agora');
define( '_RENEW_BUTTON_CONTINUE', 'Estenda anterior Conta de Membro');
define( '_ACCT_DATE_FORMAT', '%d-%m-%Y');
define( '_EXPIRED', 'A sua conta est&aacute; desactivada; Obrigado por nos contactar para a renova&ccedil;&atilde;o da sua inscri&ccedil;&atilde;o. Data de Expira&ccedil;&atilde;o :');
define( '_EXPIRED_TRIAL', 'O seu per&iacute;odo de demonstra&ccedil;&atilde;o expira em: ');
define( '_ERRTIMESTAMP', 'Impossivel de converter Data/Hora.');
define( '_EXPIRED_TITLE', 'Conta Expirada !');
define( '_DEAR', 'Caro(a) %s');

// --== CONFIRMATION FORM ==--
define( '_CONFIRM_TITLE', 'Formul&aacute;rio de Confirma&ccedil;&atilde;o');
define( '_CONFIRM_COL1_TITLE', 'Conta');
define( '_CONFIRM_COL2_TITLE', 'Detalhe');
define( '_CONFIRM_COL3_TITLE', 'Valor');
define( '_CONFIRM_ROW_NAME', 'Nome: ');
define( '_CONFIRM_ROW_USERNAME', 'Nome de Utilizador: ');
define( '_CONFIRM_ROW_EMAIL', 'E-mail:');
define( '_CONFIRM_INFO', 'Por favor clique no bot&atilde;o Continuar para completar a sua inscri&ccedil;&atilde;o.');
define( '_BUTTON_CONFIRM', 'Continua');
define( '_CONFIRM_TOS', 'Eu li e concordo com os Termos do Servi&ccedil;o');
define( '_CONFIRM_TOS_IFRAME', 'Eu li e concordo com os Termos do Servi&ccedil;o (acima)');
define( '_CONFIRM_TOS_ERROR', 'Por favor leia e concorde com os nossos Termos do Servi&ccedil;o');
define( '_CONFIRM_COUPON_INFO', 'Se tiver um cup&atilde;o promocional, pode inserir o seu codigo na Pagina de Verifica&ccedil;&atilde;o para obter um desconto sobre o seu pagamento');
define( '_CONFIRM_COUPON_INFO_BOTH', 'Se tiver um cup&atilde;o promocional, pode inserir o seu c&oacute;digo aqui, ou na Pagina de Verifica&ccedil;&atilde;o para obter um desconto sobre o seu pagamento');
define( '_CONFIRM_FREETRIAL', 'Demonstra&ccedil;&atilde;o Gr&aacute;tis');
define( '_CONFIRM_YOU_HAVE_SELECTED', 'You have selected');

define( '_CONFIRM_DIFFERENT_USER_DETAILS', 'Want to change the user details?');
define( '_CONFIRM_DIFFERENT_ITEM', 'Wanted to select a different item?');

// --== SHOPPING CART FORM ==--
define( '_CART_TITLE', 'Shopping Cart');
define( '_CART_ROW_TOTAL', 'Total');
define( '_CART_INFO', 'Please use the Continue-Button below to complete your purchase.');
define( '_CART_CLEAR_ALL', 'clear the whole cart');
define( '_CART_DELETE_ITEM', 'remove');

// --== EXCEPTION FORM ==--
define( '_EXCEPTION_TITLE', 'Additional Information Required');
define( '_EXCEPTION_TITLE_NOFORM', 'Please note:');
define( '_EXCEPTION_INFO', 'To proceed with your checkout, we need you to provide additional information as specified below:');

// --== PROMPT PASSWORD FORM ==--
define( '_AEC_PROMPT_PASSWORD', 'por raz&otilde;es de seguran&ccedil;a, precisa inserir a sua senha para continuar.');
define( '_AEC_PROMPT_PASSWORD_WRONG', 'A Senha inserida n&atilde;o corresponde com a sua senha indicada na inscri&ccedil;&atilde;o. Por favor tente novamente.');
define( '_AEC_PROMPT_PASSWORD_BUTTON', 'Continua');

// --== CHECKOUT FORM ==--
define( '_CHECKOUT_TITLE', 'Verifica&ccedil;&atilde;o');
define( '_CHECKOUT_INFO', 'A sua inscri&ccedil;&atilde;o foi guardada, Nesta p&aacute;gina pode completar o seu pagamento - %s. Se acorrer algum erro, pode sempre voltar atr&aacute;s e retomar este processo, fazendo o acesso no nosso site com o seu Nome de utilizador e Senha - O nosso Sistema fornecer&aacute; uma op&ccedil;&atilde;o para repetir o seu pagamento novamente..');
define( '_CHECKOUT_INFO_REPEAT', 'Obrigado por voltar. Nesta p&aacute;gina, poder&aacute; completar o seu pagamento - %s. Se acorrer algum erro, pode sempre voltar atr&aacute;s e retomar este processo, fazendo o login no nosso site com o seu Nome de utilizador e Senha - O nosso Sistema fornecer&aacute; uma op&ccedil;&atilde;o para repetir o seu pagamento novamente.');
define( '_BUTTON_CHECKOUT', 'Verifica&ccedil;&atilde;o');
define( '_BUTTON_APPEND', 'Anexar');
define( '_BUTTON_APPLY', 'Aplicar');
define( '_BUTTON_EDIT', 'Editar');
define( '_BUTTON_SELECT', 'Selecionar');
define( '_CHECKOUT_COUPON_CODE', 'C&oacute;digo Cup&atilde;o');
define( '_CHECKOUT_INVOICE_AMOUNT', 'Valor da Factura');
define( '_CHECKOUT_INVOICE_COUPON', 'Cup&atilde;o');
define( '_CHECKOUT_INVOICE_COUPON_REMOVE', 'remover');
define( '_CHECKOUT_INVOICE_TOTAL_AMOUNT', 'Valor Total');
define( '_CHECKOUT_COUPON_INFO', 'Se tiver um cup&atilde;o promocional, pode inserir aqui o eu c&oacute;digo para obter um desconto no seu pagamento');
define( '_CHECKOUT_GIFT_HEAD', 'Gift to another user');
define( '_CHECKOUT_GIFT_INFO', 'Enter details for another user of this site to give the item(s) you are about to purchase to that account.');

define( '_AEC_TERMTYPE_TRIAL', 'Factura&ccedil;&atilde;o Inicial');
define( '_AEC_TERMTYPE_TERM', 'Termo Regular de Factura&ccedil;&atilde;o');
define( '_AEC_CHECKOUT_TERM', 'Termo de Factura&ccedil;&atilde;o');
define( '_AEC_CHECKOUT_NOTAPPLICABLE', 'n&atilde;o aplic&aacute;vel');
define( '_AEC_CHECKOUT_FUTURETERM', 'termo futuro');
define( '_AEC_CHECKOUT_COST', 'Custo');
define( '_AEC_CHECKOUT_TAX', 'Tax');
define( '_AEC_CHECKOUT_DISCOUNT', 'Desconto');
define( '_AEC_CHECKOUT_TOTAL', 'Total');
define( '_AEC_CHECKOUT_GRAND_TOTAL', 'Grand Total');
define( '_AEC_CHECKOUT_DURATION', 'Dura&ccedil;&atilde;o');

define( '_AEC_CHECKOUT_DUR_LIFETIME', 'Dura&ccedil;&atilde;o');

define( '_AEC_CHECKOUT_DUR_DAY', 'Dia');
define( '_AEC_CHECKOUT_DUR_DAYS', 'Dias');
define( '_AEC_CHECKOUT_DUR_WEEK', 'Semana');
define( '_AEC_CHECKOUT_DUR_WEEKS', 'Semanas');
define( '_AEC_CHECKOUT_DUR_MONTH', 'M&ecirc;s');
define( '_AEC_CHECKOUT_DUR_MONTHS', 'Meses');
define( '_AEC_CHECKOUT_DUR_YEAR', 'Ano');
define( '_AEC_CHECKOUT_DUR_YEARS', 'Anos');

// --== ALLOPASS SPECIFIC ==--
define( '_REGTITLE','INSCRI&Ccedil;&Atilde;O');
define( '_ERRORCODE','Erro de C&oacute;digo');
define( '_FTEXTA','O c&oacute;digo utilizado n&atilde;o &eacute; v&aacute;lido! Para obter um c&oacute;digo v&aacute;lido, disque o n&uacute;mero de telefone, indicado na janela pop-up, ap&oacute;s ter clicado no &iacute;cone relativo ao seu pa&iacute;s. O seu browser dever&aacute; permitir o uso de cookies. Se voc&ecirc; tiver a certeza de que o seu c&oacute;digo est&aacute; correto, aguarde alguns segundos e tente novamente! Se n&atilde;o, tome nota da data e hora deste aviso de erro e informe o Webmaster deste problema, indicando o c&oacute;digo utilizado.');
define( '_RECODE','Introduza o c&oacute;digo novamente!');

// --== REGISTRATION STEPS ==--
define( '_STEP_DATA', 'Os seus Dados');
define( '_STEP_CONFIRM', 'Confirmar');
define( '_STEP_PLAN', 'Selecionar Plano');
define( '_STEP_EXPIRED', 'Expirou!');

// --== NOT ALLOWED PAGE ==--
define( '_NOT_ALLOWED_HEADLINE', '&Eacute; necess&aacute;rio ser Membro!');
define( '_NOT_ALLOWED_FIRSTPAR', 'O conte&uacute;do que pretende visualizar apenas se encontra dispon&iacute;vel para os membros do Site. Se j&aacute; &eacute; Membro, processa ao seu login para poder visualizar o conte&uacute;do. Por favor clique no link se pretender registar-se: ');
define( '_NOT_ALLOWED_REGISTERLINK', 'P&aacute;gina de Inscri&ccedil;&atilde;o');
define( '_NOT_ALLOWED_FIRSTPAR_LOGGED', 'O conte&uacute;do que pretende visualizar apenas est&aacute; dispon&iacute;vel para membros do Site com determinada Inscri&ccedil;&atilde;o. Por favor clique no seguinte link se pretende modificar a sua inscri&ccedil;&atilde;o: ');
define( '_NOT_ALLOWED_REGISTERLINK_LOGGED', 'P&aacute;gina de Inscri&ccedil;&atilde;o');
define( '_NOT_ALLOWED_SECONDPAR', 'Registar-se apenas demorar&aacute; alguns minutos - n&oacute;s usamos o servi&ccedil;o de:');

// --== CANCELLED PAGE ==--
define( '_CANCEL_TITLE', 'Resultado da Inscri&ccedil;&atilde;o: Cancelada!');
define( '_CANCEL_MSG', 'O nosso Sistema recebeu a informa&ccedil;&atilde;o, que voc&ecirc; escolheu cancelar o seu pagamento. Se isto aconteceu devido a um problema encontrado no nosso site, por favor n&atilde;o hesite em nos contactar!');

// --== PENDING PAGE ==--
define( '_PENDING_TITLE', 'Account Pending');
define( '_WARN_PENDING', 'A sua conta encontra-se pendente. Se continuar neste estado durante algumas horas e o sue pagamento for confirmado, por favor contacte o administrador do Site.');
define( '_PENDING_OPENINVOICE', 'Aparentemente voc&ecirc; tem um factura por liquidar nos nossos registos - Se existe algo de errado com esta situa&ccedil;&atilde;o, voc&ecirc; pode ir a Pagina de Verifica&ccedil;&atilde;o novamente para repetir o processo:');
define( '_GOTO_CHECKOUT', 'Ir para a Pagina de Verifica&ccedil;&atilde;o');
define( '_GOTO_CHECKOUT_CANCEL', 'voc&ecirc; tamb&eacute;m pode cancelar o pagamento (voc&ecirc; ter&aacute; a possibilidade de ir a p&aacute;gina Selec&ccedil;&atilde;o de Plano uma vez mais):');
define( '_PENDING_NOINVOICE', 'Aparentemente cancelou a &uacute;nica factura que possu&iacute;a na sua conta. Por favor use o bot&atilde;o abaixo para ir para a p&aacute;gina de Selec&ccedil;&atilde;o de Plano novamente:');
define( '_PENDING_NOINVOICE_BUTTON', 'Selec&ccedil;&atilde;o de Plano');
define( '_PENDING_REASON_ECHECK', '(De acordo com as nossas informa&ccedil;&otilde;es, voc&ecirc; decidiu pagar por echeck(ou similar), assim sendo ter&aacute; de esperar at&eacute; que o seu pagamento seja liberado - normalmente demora 1-4 dias.)');
define( '_PENDING_REASON_WAITING_RESPONSE', '(De acordo com as nossas informa&ccedil;&otilde;es, estamos &agrave; espera de uma resposta da entidade que realiza o processamento dos pagamentos. Ser&aacute; notificado quando o seu pagamento for confirmado. Pedimos desculpa pela demora.)');
define( '_PENDING_REASON_TRANSFER', '(De acordo com as nossas informa&ccedil;&otilde;es, decidiu realizar o pagamento de forma offline, deste modo ter&aacute; de esperar at&eacute; confirmar-mos o seu pagamento - o que poder&aacute; demorar alguns dias.)');

// --== HOLD PAGE ==--
define( '_HOLD_TITLE', 'Conta em Espera');
define( '_HOLD_EXPLANATION', 'A sua conta continua em espera. A causa mais prov&aacute;vel para esta situa&ccedil;&atilde;o &eacute; que poder&aacute; existir algum problema com o seu pagamento realizado recentemente. Se n&atilde;o receber um email nas pr&oacute;ximas 24 Horas, por favor contacte o administrador do Site.');

// --== THANK YOU PAGE ==--
define( '_THANKYOU_TITLE', 'Obrigado!');
define( '_SUB_FEPARTICLE_HEAD', 'Inscri&ccedil;&atilde;o Completa!');
define( '_SUB_FEPARTICLE_HEAD_RENEW', 'Renova&ccedil;&atilde;o da Inscri&ccedil;&atilde;o Completa!');
define( '_SUB_FEPARTICLE_LOGIN', 'J&aacute; pode efectuar o seu acesso.');
define( '_SUB_FEPARTICLE_THANKS', 'Obrigado pela sua Inscri&ccedil;&atilde;o. ');
define( '_SUB_FEPARTICLE_THANKSRENEW', 'Obrigado pela renova&ccedil;&atilde;o da sua inscri&ccedil;&atilde;o. ');
define( '_SUB_FEPARTICLE_PROCESS', 'O nosso Sistema ir&aacute; agora processar o seu pedido. ');
define( '_SUB_FEPARTICLE_PROCESSPAY', 'O nosso Sistema ir&aacute; agora aguardar o seu pagamento. ');
define( '_SUB_FEPARTICLE_ACTMAIL', 'Voc&ecirc; ir&aacute; receber um email com um link de activa&ccedil;&atilde;o, ap&oacute;s o nosso sistema ter processado o seu pedido. ');
define( '_SUB_FEPARTICLE_MAIL', 'Voc&ecirc; ir&aacute; receber um email, ap&oacute;s o nosso sistema ter processado o seu pedido. ');

// --== CHECKOUT ERROR PAGE ==--
define( '_CHECKOUT_ERROR_TITLE', 'Erro enquanto se processava o pagamento!');
define( '_CHECKOUT_ERROR_EXPLANATION', 'Ocorreu um erro no processamento do seu pagamento');
define( '_CHECKOUT_ERROR_OPENINVOICE', 'Isto deixa a sua factura por liquidar. Para repetir o pagamento, poder&aacute; ir a p&aacute;gina de Verifica&ccedil;&atilde;o novamente, e tentar uma vez mais:');
define( '_CHECKOUT_ERROR_FURTHEREXPLANATION', 'This leaves your invoice uncleared, but you can try to check out again below. If you experience further problems or need any assistance with your checkout, please do not hesitate to contact us.');

// --== COUPON INFO ==--
define( '_COUPON_INFO', 'Coupons:');
define( '_COUPON_INFO_CONFIRM', 'If you want to use one or more coupons for this payment, you can do so on the checkout page.');
define( '_COUPON_INFO_CHECKOUT', 'Please enter your coupon code here and click the button to append it to this payment.');

// --== COUPON ERROR MESSAGES ==--
define( '_COUPON_WARNING_AMOUNT', 'Um Cup&atilde;o que adicionou a esta factura n&atilde;o afectar&aacute; o pr&oacute;ximo pagamento, embora ele n&atilde;o afecta esta factura, ir&aacute; afectar um pagamento posterior.');
define( '_COUPON_ERROR_PRETEXT', 'Estamos muito tristes:');
define( '_COUPON_ERROR_EXPIRED', 'Este cup&atilde;o expirou.');
define( '_COUPON_ERROR_NOTSTARTED', 'O uso deste cup&atilde;o n&atilde;o &eacute; permitido neste momento.');
define( '_COUPON_ERROR_NOTFOUND', 'N&atilde;o &eacute; poss&iacute;vel encontrar o c&oacute;digo do cup&atilde;o introduzido.');
define( '_COUPON_ERROR_MAX_REUSE', 'Excedeu o limite m&aacute;ximo de uso deste cup&atilde;o.');
define( '_COUPON_ERROR_PERMISSION', 'n&atilde;o possui permiss&atilde;o para utilizar este cup&atilde;o.');
define( '_COUPON_ERROR_WRONG_USAGE', 'n&atilde;o pode utilizar este cup&atilde;o para esta opera&ccedil;&atilde;o.');
define( '_COUPON_ERROR_WRONG_PLAN', 'N&atilde;o se encontra no Plano de Inscri&ccedil;&atilde;o correcto para este cup&atilde;o.');
define( '_COUPON_ERROR_WRONG_PLAN_PREVIOUS', 'Para utilizar este cup&atilde;o, o seu &uacute;ltimo Plano de Inscri&ccedil;&atilde;o deve ser diferente.');
define( '_COUPON_ERROR_WRONG_PLANS_OVERALL', 'N&atilde;o tem no seu hist&oacute;rico o Plano de Inscri&ccedil;&atilde;o correcto para utilizar este cup&atilde;o.');
define( '_COUPON_ERROR_TRIAL_ONLY', 'Apenas poder&aacute; utiliar este cup&atilde;o por um per&iacute;odo experimental.');
define( '_COUPON_ERROR_COMBINATION', 'N&atilde;o pode utilizar este cup&atilde;o com um dos outros.');
define( '_COUPON_ERROR_SPONSORSHIP_ENDED', 'Patroc&iacute;nio para este Cup&atilde;o terminou ou est&aacute; actualmente inactivo.');

// ----======== EMAIL TEXT ========----
define( '_AEC_SEND_SUB',	'Detalhe da Conta para %s em %s' );
define( '_AEC_USEND_MSG',	'Ol&aacute; %s,\n\nObrigado pelo registo em %s.\n\nPode agora aceder a %s utilizando o seu Nome de Utilizador e Senha definidos no seu registo.' );
define( '_AEC_USEND_MSG_ACTIVATE',	'Ol&aacute; %s,\n\nObrigado pelo seu registo em %s. A sua conta foi criada e deve ser activada antes de poder utiliza-la.\nPara activar a conta clique no seguinte link ou copie para o seu browser:\n%s\n\nAp&oacute;s activar a sua conta pode aceder a %s utilizando o seu Nome de Utilizador e Senha:\n\nNome de Utilizador - %s\nSenha - %s' );
define( '_ACCTEXP_SEND_MSG','Inscri&ccedil;&atilde;o para %s em %s');
define( '_ACCTEXP_SEND_MSG_RENEW','Renova&ccedil;&atilde;o de inscri&ccedil;&atilde;o para %s em %s');
define( '_ACCTEXP_MAILPARTICLE_GREETING', 'Ol&aacute; %s, \n\n');
define( '_ACCTEXP_MAILPARTICLE_THANKSREG', 'Obrigado pelo seu registo em %s.');
define( '_ACCTEXP_MAILPARTICLE_THANKSREN', 'Obrigado pela renova&ccedil;&atilde;o da inscri&ccedil;&atilde;o em %s.');
define( '_ACCTEXP_MAILPARTICLE_PAYREC', 'O seu pagamento para a Inscri&ccedil;&atilde;o foi recebido.');
define( '_ACCTEXP_MAILPARTICLE_LOGIN', 'Pode agora aceder a %s com o seu Nome de Utilizdor e Senha.');
define( '_ACCTEXP_MAILPARTICLE_FOOTER','\n\nPor favor, n&atilde;o responda a esta mensagem, &eacute; uma resposta autom&aacute;tica gerada pelo Sistema e &eacute; apenas para fins informativos.');
define( '_ACCTEXP_ASEND_MSG',	'Ol&aacute; %s,\n\na um novo utilizador criou uma Inscri&ccedil;&atilde;o em [ %s ].\n\nMais detalhes:\n\nNome.........: %s\nE-mail........: %s\nNome de Utilizador.....: %s\nRef. Inscri&ccedil;&atilde;o....: %s\nInscri&ccedil;&atilde;o.: %s\nIP...........: %s\nISP..........: %s\n\nPor favor n&atilde;o responda a esta mensagem, &eacute; um resposta autom&aacute;tica gerada pelo sistema e &eacute; apenas para fins informativos.' );
define( '_ACCTEXP_ASEND_MSG_RENEW',	'Ol&aacute; %s,\n\num utilizador renovou a inscri&ccedil;&atilde;o em [ %s ].\n\nMais Detalhes:\n\nNome.........: %s\nE-mail........: %s\nNome de Utilizador.....: %s\nRef. Inscri&ccedil;&atilde;o....: %s\nInscri&ccedil;&atilde;o.: %s\nIP...........: %s\nISP..........: %s\n\nPor favor, n&atilde;o responda a esta mensagem, &eacute; uma resposta autom&aacute;tica gerada pelo Sistema e &eacute; apenas para fins informativos.' );
define( '_AEC_ASEND_MSG_NEW_REG',	'Ol&aacute; %s,\n\n novo registo em [ %s ].\n\nMais Detalhes:\n\nNome.....: %s\nE-mail.: %s\nNome de Utilizador....: %s\nIP.......: %s\nISP......: %s\n\nPor favor, n&atilde;o responda a esta mensagem, &eacute; uma resposta autom&aacute;tica gerada pelo Sistema e &eacute; apenas para fins informativos.' );
define( '_AEC_ASEND_NOTICE',	'AEC %s: %s em %s' );
define( '_AEC_ASEND_NOTICE_MSG',	'Segundo o n&iacute;vel de relat&oacute;rio de E-Mail selecionado, esta &eacute; uma notifica&ccedil;&atilde;o autom&aacute;tica sobre um entrada no EventLog.\n\nOs detalhes desta mensagem s&atilde;o:\n\n--- --- --- ---\n\n%s\n\n--- --- --- ---\n\nPor favor, n&atilde;o responda a esta mensagem, &eacute; uma resposta autom&aacute;tica gerada pelo Sistema e &eacute; apenas para fins informativos. Pode alterar o n&iacute;vel de entradas reportadas nas suas configura&ccedil;&otilde;es do AEC.' );

// ----======== COUNTRY CODES ========----

define( 'COUNTRYCODE_SELECT', 'Selecione Pa&iacute;s' );

define( 'COUNTRYCODE_AD', 'Andorra' );
define( 'COUNTRYCODE_AE', 'United Arab Emirates' );
define( 'COUNTRYCODE_AF', 'Afghanistan' );
define( 'COUNTRYCODE_AG', 'Antigua and Barbuda' );
define( 'COUNTRYCODE_AI', 'Anguilla' );
define( 'COUNTRYCODE_AL', 'Albania' );
define( 'COUNTRYCODE_AM', 'Armenia' );
define( 'COUNTRYCODE_AN', 'Netherlands Antilles' );
define( 'COUNTRYCODE_AO', 'Angola' );
define( 'COUNTRYCODE_AQ', 'Antarctica' );
define( 'COUNTRYCODE_AR', 'Argentina' );
define( 'COUNTRYCODE_AS', 'American Samoa' );
define( 'COUNTRYCODE_AT', 'Austria' );
define( 'COUNTRYCODE_AU', 'Australia' );
define( 'COUNTRYCODE_AW', 'Aruba' );
define( 'COUNTRYCODE_AX', 'Aland Islands &#65279;land Island\'s' );
define( 'COUNTRYCODE_AZ', 'Azerbaijan' );
define( 'COUNTRYCODE_BA', 'Bosnia and Herzegovina' );
define( 'COUNTRYCODE_BB', 'Barbados' );
define( 'COUNTRYCODE_BD', 'Bangladesh' );
define( 'COUNTRYCODE_BE', 'Belgium' );
define( 'COUNTRYCODE_BF', 'Burkina Faso' );
define( 'COUNTRYCODE_BG', 'Bulgaria' );
define( 'COUNTRYCODE_BH', 'Bahrain' );
define( 'COUNTRYCODE_BI', 'Burundi' );
define( 'COUNTRYCODE_BJ', 'Benin' );
define( 'COUNTRYCODE_BL', 'Saint Barth&eacute;lemy' );
define( 'COUNTRYCODE_BM', 'Bermuda' );
define( 'COUNTRYCODE_BN', 'Brunei Darussalam' );
define( 'COUNTRYCODE_BO', 'Bolivia, Plurinational State of' );
define( 'COUNTRYCODE_BR', 'Brazil' );
define( 'COUNTRYCODE_BS', 'Bahamas' );
define( 'COUNTRYCODE_BT', 'Bhutan' );
define( 'COUNTRYCODE_BV', 'Bouvet Island' );
define( 'COUNTRYCODE_BW', 'Botswana' );
define( 'COUNTRYCODE_BY', 'Belarus' );
define( 'COUNTRYCODE_BZ', 'Belize' );
define( 'COUNTRYCODE_CA', 'Canada' );
define( 'COUNTRYCODE_CC', 'Cocos (Keeling) Islands' );
define( 'COUNTRYCODE_CD', 'Congo, the Democratic Republic of the' );
define( 'COUNTRYCODE_CF', 'Central African Republic' );
define( 'COUNTRYCODE_CG', 'Congo' );
define( 'COUNTRYCODE_CH', 'Switzerland' );
define( 'COUNTRYCODE_CI', 'Cote d\'Ivoire' );
define( 'COUNTRYCODE_CK', 'Cook Islands' );
define( 'COUNTRYCODE_CL', 'Chile' );
define( 'COUNTRYCODE_CM', 'Cameroon' );
define( 'COUNTRYCODE_CN', 'China' );
define( 'COUNTRYCODE_CO', 'Colombia' );
define( 'COUNTRYCODE_CR', 'Costa Rica' );
define( 'COUNTRYCODE_CU', 'Cuba' );
define( 'COUNTRYCODE_CV', 'Cape Verde' );
define( 'COUNTRYCODE_CX', 'Christmas Island' );
define( 'COUNTRYCODE_CY', 'Cyprus' );
define( 'COUNTRYCODE_CZ', 'Czech Republic' );
define( 'COUNTRYCODE_DE', 'Germany' );
define( 'COUNTRYCODE_DJ', 'Djibouti' );
define( 'COUNTRYCODE_DK', 'Denmark' );
define( 'COUNTRYCODE_DM', 'Dominica' );
define( 'COUNTRYCODE_DO', 'Dominican Republic' );
define( 'COUNTRYCODE_DZ', 'Algeria' );
define( 'COUNTRYCODE_EC', 'Ecuador' );
define( 'COUNTRYCODE_EE', 'Estonia' );
define( 'COUNTRYCODE_EG', 'Egypt' );
define( 'COUNTRYCODE_EH', 'Western Sahara' );
define( 'COUNTRYCODE_ER', 'Eritrea' );
define( 'COUNTRYCODE_ES', 'Spain' );
define( 'COUNTRYCODE_ET', 'Ethiopia' );
define( 'COUNTRYCODE_FI', 'Finland' );
define( 'COUNTRYCODE_FJ', 'Fiji' );
define( 'COUNTRYCODE_FK', 'Falkland Islands (Malvinas)' );
define( 'COUNTRYCODE_FM', 'Micronesia, Federated States of' );
define( 'COUNTRYCODE_FO', 'Faroe Islands' );
define( 'COUNTRYCODE_FR', 'France' );
define( 'COUNTRYCODE_GA', 'Gabon' );
define( 'COUNTRYCODE_GB', 'United Kingdom' );
define( 'COUNTRYCODE_GD', 'Grenada' );
define( 'COUNTRYCODE_GE', 'Georgia' );
define( 'COUNTRYCODE_GF', 'French Guiana' );
define( 'COUNTRYCODE_GG', 'Guernsey' );
define( 'COUNTRYCODE_GH', 'Ghana' );
define( 'COUNTRYCODE_GI', 'Gibraltar' );
define( 'COUNTRYCODE_GL', 'Greenland' );
define( 'COUNTRYCODE_GM', 'Gambia' );
define( 'COUNTRYCODE_GN', 'Guinea' );
define( 'COUNTRYCODE_GP', 'Guadeloupe' );
define( 'COUNTRYCODE_GQ', 'Equatorial Guinea' );
define( 'COUNTRYCODE_GR', 'Greece' );
define( 'COUNTRYCODE_GS', 'South Georgia and the South Sandwich Islands' );
define( 'COUNTRYCODE_GT', 'Guatemala' );
define( 'COUNTRYCODE_GU', 'Guam' );
define( 'COUNTRYCODE_GW', 'Guinea-Bissau' );
define( 'COUNTRYCODE_GY', 'Guyana' );
define( 'COUNTRYCODE_HK', 'Hong Kong' );
define( 'COUNTRYCODE_HM', 'Heard Island and McDonald Islands' );
define( 'COUNTRYCODE_HN', 'Honduras' );
define( 'COUNTRYCODE_HR', 'Croatia' );
define( 'COUNTRYCODE_HT', 'Haiti' );
define( 'COUNTRYCODE_HU', 'Hungary' );
define( 'COUNTRYCODE_ID', 'Indonesia' );
define( 'COUNTRYCODE_IE', 'Ireland' );
define( 'COUNTRYCODE_IL', 'Israel' );
define( 'COUNTRYCODE_IM', 'Isle of Man' );
define( 'COUNTRYCODE_IN', 'India' );
define( 'COUNTRYCODE_IO', 'British Indian Ocean Territory' );
define( 'COUNTRYCODE_IQ', 'Iraq' );
define( 'COUNTRYCODE_IR', 'Iran, Islamic Republic of' );
define( 'COUNTRYCODE_IS', 'Iceland' );
define( 'COUNTRYCODE_IT', 'Italy' );
define( 'COUNTRYCODE_JE', 'Jersey' );
define( 'COUNTRYCODE_JM', 'Jamaica' );
define( 'COUNTRYCODE_JO', 'Jordan' );
define( 'COUNTRYCODE_JP', 'Japan' );
define( 'COUNTRYCODE_KE', 'Kenya' );
define( 'COUNTRYCODE_KG', 'Kyrgyzstan' );
define( 'COUNTRYCODE_KH', 'Cambodia' );
define( 'COUNTRYCODE_KI', 'Kiribati' );
define( 'COUNTRYCODE_KM', 'Comoros' );
define( 'COUNTRYCODE_KN', 'Saint Kitts and Nevis' );
define( 'COUNTRYCODE_KP', 'Korea, Democratic People\'s Republic of' );
define( 'COUNTRYCODE_KR', 'Korea, Republic of' );
define( 'COUNTRYCODE_KW', 'Kuwait' );
define( 'COUNTRYCODE_KY', 'Cayman Islands' );
define( 'COUNTRYCODE_KZ', 'Kazakhstan' );
define( 'COUNTRYCODE_LA', 'Lao People\'s Democratic Republic' );
define( 'COUNTRYCODE_LB', 'Lebanon' );
define( 'COUNTRYCODE_LC', 'Saint Lucia' );
define( 'COUNTRYCODE_LI', 'Liechtenstein' );
define( 'COUNTRYCODE_LK', 'Sri Lanka' );
define( 'COUNTRYCODE_LR', 'Liberia' );
define( 'COUNTRYCODE_LS', 'Lesotho' );
define( 'COUNTRYCODE_LT', 'Lithuania' );
define( 'COUNTRYCODE_LU', 'Luxembourg' );
define( 'COUNTRYCODE_LV', 'Latvia' );
define( 'COUNTRYCODE_LY', 'Libyan Arab Jamahiriya' );
define( 'COUNTRYCODE_MA', 'Morocco' );
define( 'COUNTRYCODE_MC', 'Monaco' );
define( 'COUNTRYCODE_MD', 'Moldova, Republic of' );
define( 'COUNTRYCODE_ME', 'Montenegro' );
define( 'COUNTRYCODE_MF', 'Saint Martin (French part)' );
define( 'COUNTRYCODE_MG', 'Madagascar' );
define( 'COUNTRYCODE_MH', 'Marshall Islands' );
define( 'COUNTRYCODE_MK', 'Macedonia, the former Yugoslav Republic of' );
define( 'COUNTRYCODE_ML', 'Mali' );
define( 'COUNTRYCODE_MM', 'Myanmar' );
define( 'COUNTRYCODE_MN', 'Mongolia' );
define( 'COUNTRYCODE_MO', 'Macao' );
define( 'COUNTRYCODE_MP', 'Northern Mariana Islands' );
define( 'COUNTRYCODE_MQ', 'Martinique' );
define( 'COUNTRYCODE_MR', 'Mauritania' );
define( 'COUNTRYCODE_MS', 'Montserrat' );
define( 'COUNTRYCODE_MT', 'Malta' );
define( 'COUNTRYCODE_MU', 'Mauritius' );
define( 'COUNTRYCODE_MV', 'Maldives' );
define( 'COUNTRYCODE_MW', 'Malawi' );
define( 'COUNTRYCODE_MX', 'Mexico' );
define( 'COUNTRYCODE_MY', 'Malaysia' );
define( 'COUNTRYCODE_MZ', 'Mozambique' );
define( 'COUNTRYCODE_NA', 'Namibia' );
define( 'COUNTRYCODE_NC', 'New Caledonia' );
define( 'COUNTRYCODE_NE', 'Niger' );
define( 'COUNTRYCODE_NF', 'Norfolk Island' );
define( 'COUNTRYCODE_NG', 'Nigeria' );
define( 'COUNTRYCODE_NI', 'Nicaragua' );
define( 'COUNTRYCODE_NL', 'Netherlands' );
define( 'COUNTRYCODE_NO', 'Norway' );
define( 'COUNTRYCODE_NP', 'Nepal' );
define( 'COUNTRYCODE_NR', 'Nauru' );
define( 'COUNTRYCODE_NU', 'Niue' );
define( 'COUNTRYCODE_NZ', 'New Zealand' );
define( 'COUNTRYCODE_OM', 'Oman' );
define( 'COUNTRYCODE_PA', 'Panama' );
define( 'COUNTRYCODE_PE', 'Peru' );
define( 'COUNTRYCODE_PF', 'French Polynesia' );
define( 'COUNTRYCODE_PG', 'Papua New Guinea' );
define( 'COUNTRYCODE_PH', 'Philippines' );
define( 'COUNTRYCODE_PK', 'Pakistan' );
define( 'COUNTRYCODE_PL', 'Poland' );
define( 'COUNTRYCODE_PM', 'Saint Pierre and Miquelon' );
define( 'COUNTRYCODE_PN', 'Pitcairn' );
define( 'COUNTRYCODE_PR', 'Puerto Rico' );
define( 'COUNTRYCODE_PS', 'Palestinian Territory, Occupied' );
define( 'COUNTRYCODE_PT', 'Portugal' );
define( 'COUNTRYCODE_PW', 'Palau' );
define( 'COUNTRYCODE_PY', 'Paraguay' );
define( 'COUNTRYCODE_QA', 'Qatar' );
define( 'COUNTRYCODE_RE', 'Reunion' );
define( 'COUNTRYCODE_RO', 'Romania' );
define( 'COUNTRYCODE_RS', 'Serbia' );
define( 'COUNTRYCODE_RU', 'Russian Federation' );
define( 'COUNTRYCODE_RW', 'Rwanda' );
define( 'COUNTRYCODE_SA', 'Saudi Arabia' );
define( 'COUNTRYCODE_SB', 'Solomon Islands' );
define( 'COUNTRYCODE_SC', 'Seychelles' );
define( 'COUNTRYCODE_SD', 'Sudan' );
define( 'COUNTRYCODE_SE', 'Sweden' );
define( 'COUNTRYCODE_SG', 'Singapore' );
define( 'COUNTRYCODE_SH', 'Saint Helena' );
define( 'COUNTRYCODE_SI', 'Slovenia' );
define( 'COUNTRYCODE_SJ', 'Svalbard and Jan Mayen' );
define( 'COUNTRYCODE_SK', 'Slovakia' );
define( 'COUNTRYCODE_SL', 'Sierra Leone' );
define( 'COUNTRYCODE_SM', 'San Marino' );
define( 'COUNTRYCODE_SN', 'Senegal' );
define( 'COUNTRYCODE_SO', 'Somalia' );
define( 'COUNTRYCODE_SR', 'Suriname' );
define( 'COUNTRYCODE_ST', 'Sao Tome and Principe' );
define( 'COUNTRYCODE_SV', 'El Salvador' );
define( 'COUNTRYCODE_SY', 'Syrian Arab Republic' );
define( 'COUNTRYCODE_SZ', 'Swaziland' );
define( 'COUNTRYCODE_TC', 'Turks and Caicos Islands' );
define( 'COUNTRYCODE_TD', 'Chad' );
define( 'COUNTRYCODE_TF', 'French Southern Territories' );
define( 'COUNTRYCODE_TG', 'Togo' );
define( 'COUNTRYCODE_TH', 'Thailand' );
define( 'COUNTRYCODE_TJ', 'Tajikistan' );
define( 'COUNTRYCODE_TK', 'Tokelau' );
define( 'COUNTRYCODE_TL', 'Timor-Leste' );
define( 'COUNTRYCODE_TM', 'Turkmenistan' );
define( 'COUNTRYCODE_TN', 'Tunisia' );
define( 'COUNTRYCODE_TO', 'Tonga' );
define( 'COUNTRYCODE_TR', 'Turkey' );
define( 'COUNTRYCODE_TT', 'Trinidad and Tobago' );
define( 'COUNTRYCODE_TV', 'Tuvalu' );
define( 'COUNTRYCODE_TW', 'Taiwan, Province of Republic of China' );
define( 'COUNTRYCODE_TZ', 'Tanzania, United Republic of' );
define( 'COUNTRYCODE_UA', 'Ukraine' );
define( 'COUNTRYCODE_UG', 'Uganda' );
define( 'COUNTRYCODE_UM', 'United States Minor Outlying Islands' );
define( 'COUNTRYCODE_US', 'United States' );
define( 'COUNTRYCODE_UY', 'Uruguay' );
define( 'COUNTRYCODE_UZ', 'Uzbekistan' );
define( 'COUNTRYCODE_VA', 'Holy See (Vatican City State)' );
define( 'COUNTRYCODE_VC', 'Saint Vincent and the Grenadines' );
define( 'COUNTRYCODE_VE', 'Venezuela, Bolivarian Republic of' );
define( 'COUNTRYCODE_VG', 'Virgin Islands, British' );
define( 'COUNTRYCODE_VI', 'Virgin Islands, U.S.' );
define( 'COUNTRYCODE_VN', 'Viet Nam' );
define( 'COUNTRYCODE_VU', 'Vanuatu' );
define( 'COUNTRYCODE_WF', 'Wallis and Futuna' );
define( 'COUNTRYCODE_WS', 'Samoa' );
define( 'COUNTRYCODE_YE', 'Yemen' );
define( 'COUNTRYCODE_YT', 'Mayotte' );
define( 'COUNTRYCODE_ZA', 'South Africa' );
define( 'COUNTRYCODE_ZM', 'Zambia' );
define( 'COUNTRYCODE_ZW', 'Zimbabwe' );

?>
