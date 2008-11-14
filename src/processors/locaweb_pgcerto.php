<?php
/**
 * @version $Id: locaweb_pgcerto.php
 * @package AEC - Account Control Expiration - Membership Manager
 * @subpackage Processors - Pagamento Certo Locaweb - http://www.pagamentocerto.com.br
 * @copyright 2008 Copyright (C) Helder Garcia - http://sounerd.com.br - http://investidorlegal.com.br
 * @author Helder Garcia <helder.garcia@gmail.com> & Team AEC - http://www.globalnerd.org
 * @license GNU/GPL v.2 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html or, at your option, any later version
 */

// Dont allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class processor_locaweb_pgcerto extends XMLprocessor
{
	function info()
	{
		$info = array();
		$info['name']							= 'locaweb_pgcerto';
		$info['longname']						= _CFG_LOCAWEB_PGCERTO_LONGNAME;
		$info['statement']					= _CFG_LOCAWEB_PGCERTO_STATEMENT;
		$info['description'] 					= _CFG_LOCAWEB_PGCERTO_DESCRIPTION;
		$info['cc_list']							= 'visa,boleto';
		$info['notify_trail_thanks'] 		= true;

		return $info;
	}

	function settings()
	{
		$settings = array();
		$settings['chaveVendedor']		= 'Sua Chave de Vendedor';
		$settings['item_name']			= sprintf( _CFG_PROCESSOR_ITEM_NAME_DEFAULT, '[[cms_live_site]]', '[[user_name]]', '[[user_username]]' );
		$settings['customparams']		= "";

		return $settings;
	}

	function backend_settings()
	{
		$settings = array();

		$settings['chaveVendedor']		= array( 'inputC' );
		$settings['item_name']			= array( 'inputE' );
		$settings['customparams']		= array( 'inputD' );

		$settings = AECToolbox::rewriteEngineInfo( null, $settings );

		return $settings;
	}

	function createGatewayLink( $request )
	{
		global $mosConfig_live_site;

/*
			$var['start_url']	= 'https://www.pagamentocerto.com.br/vendedor/vendedor.asmx';					// URL para iniciar/criar uma transacao
			$var['proc_url']		= 	'https://www.pagamentocerto.com.br/pagamento/pagamento.aspx';				// URL para processar uma transacao
			$var['verif_url']		= 	'https://www.pagamentocerto.com.br/pagamento/pagamento.aspx';				// URL para consultar uma transacao
			$var['bolre_url']	= 	'https://www.pagamentocerto.com.br/pagamento/ReemissaoBoleto.aspx';	// URL para reemissao de boleto

		$var['start_url']	= 'https://www.pagamentocerto.com.br/vendedor/vendedor.asmx';					// URL para iniciar/criar uma transacao
		$var['post_url']		= 'https://www.pagamentocerto.com.br/pagamento/pagamento.aspx';				// URL para postar uma transacao iniciada. Interface de pagamento
		$var['valorTotal']		= $request->int_var['valorTotal'];

		$var['chaveVendedor']		= $this->settings['chaveVendedor'];
		$var['tdi']							= $request->int_var['invoice'];

		$var['urlRetorno']	= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=pgcertonotification' );

		$var['item_number']		= $request->metaUser->userid;
		$var['item_name']		= AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );

		$var['no_shipping']		= $this->settings['no_shipping'];
		$var['no_note']			= '1';
		$var['rm']				= '2';

		$var['urlRetorno']			= $request->int_var['return_url'];


		// Customizations
		$customizations = array( 'cbt', 'cn', 'cpp_header_image', 'cpp_headerback_color', 'cpp_headerborder_color', 'cpp_payflow_color', 'image_url', 'page_style' );

		foreach ( $customizations as $cust ) {
			if ( !empty( $this->settings[$cust] ) ) {
					$var[$cust] = $this->settings[$cust];
			}
		}

		if ( isset( $this->settings['cs'] ) ) {
			if ( $this->settings['cs'] != 0 ) {
				$var['cs'] = $this->settings['cs'];
			}
		}

		return $var;
*/
	}
	
	function checkoutform( $request )
	{
		global $mosConfig_live_site;

		$name	= $request->metaUser->cmsUser->name;
		$email	= $request->metaUser->cmsUser->email;
		
		$var['params']['nome'] = array( 'inputC', _AEC_USERFORM_BILLFIRSTNAME_NAME, _AEC_USERFORM_BILLFIRSTNAME_NAME, $name);
		$var['params']['cpf'] = array( 'inputC', _CFG_LOCAWEB_PGCERTO_CPF_NAME, _CFG_LOCAWEB_PGCERTO_CPF_NAME, '');
		$var['params']['email'] = array( 'inputC', _CFG_LOCAWEB_PGCERTO_EMAIL_NAME, _CFG_LOCAWEB_PGCERTO_EMAIL_NAME, $email);
		
		$var['params']['endereco'] = array( 'inputC', _AEC_USERFORM_BILLADDRESS_NAME, _AEC_USERFORM_BILLADDRESS_NAME, '');
		$var['params']['complemento'] = array( 'inputC', _AEC_USERFORM_BILLADDRESS2_NAME, _AEC_USERFORM_BILLADDRESS2_NAME, '');
		$var['params']['bairro'] = array( 'inputC', _AEC_USERFORM_BILLSTATEPROV_NAME, _AEC_USERFORM_BILLSTATEPROV_NAME, '');
		$var['params']['cidade'] = array( 'inputC', _AEC_USERFORM_BILLCITY_NAME, _AEC_USERFORM_BILLCITY_NAME, '');
		$var['params']['estado'] = array( 'inputC', _AEC_USERFORM_BILLSTATE_NAME, _AEC_USERFORM_BILLSTATE_NAME, '');
		$var['params']['cep'] = array( 'inputC', _AEC_USERFORM_BILLZIP_NAME, _AEC_USERFORM_BILLZIP_NAME, '');
		
		// Create a selection box with payment options
		$paymentOptions = array();
		$paymentOptions[] = mosHTML::makeOption( 'CartaoCredito', 'Cartão de Crédito VISA' );
		$paymentOptions[] = mosHTML::makeOption( 'Boleto', 'Boleto Bancário' );
		
		$var['params']['lists']['modulo'] = mosHTML::selectList($paymentOptions, 'modulo', 'size="2"', 'value', 'text', 0);
		$var['params']['modulo'] = array( 'list', _CFG_LOCAWEB_PGCERTO_MODULE_NAME, _CFG_LOCAWEB_PGCERTO_MODULE_DESC);

		// Create a selection box with type of buyer
		$tipoPessoa = array();
		$tipoPessoa[] = mosHTML::makeOption( 'Fisica', 'Pessoa Física' );
		$tipoPessoa[] = mosHTML::makeOption( 'Juridica', 'Pessoa Jurídica' );
		
		$var['params']['lists']['tipoPessoa'] = mosHTML::selectList($tipoPessoa, 'tipoPessoa', 'size="2"', 'value', 'text', 0);
		$var['params']['tipoPessoa'] = array( 'list', _CFG_LOCAWEB_PGCERTO_TIPOPESSOA_NAME, _CFG_LOCAWEB_PGCERTO_TIPOPESSOA_DESC);

		$var['params']['cnpj'] = array( 'inputC', _CFG_LOCAWEB_PGCERTO_CNPJ_NAME, _CFG_LOCAWEB_PGCERTO_CNPJ_NAME, '');
		$var['params']['razaoSocial'] = array( 'inputC', _CFG_LOCAWEB_PGCERTO_RAZAOSOCIAL_NAME, _CFG_LOCAWEB_PGCERTO_RAZAOSOCIAL_NAME, '');
		
		return $var;
	}

	function createRequestXML( $request )
	{
		global $mosConfig_live_site;
		$subDesc				= AECToolbox::rewriteEngine( $this->settings['item_name'], $request->metaUser, $request->new_subscription, $request->invoice );
		$separators			= array(",", ".");			// We want them removed
		$valorTotal			= str_replace($separators, "", trim( $request->int_var['amount'] ));
		$separators			= array("-", "/");			// We want them removed
		$cep						= str_replace($separators, "", trim( $request->int_var['params']['cep'] ));
		$cnpj					= str_replace($separators, "", trim( $request->int_var['params']['cnpj'] ));
		$cpf						= str_replace($separators, "", trim( $request->int_var['params']['cpf'] ));
		
		// Start xml, add login and transaction key, as well as invoice number
		$content =	'<?xml version="1.0" encoding="utf-8"?>'
					. '<LocaWeb>'
					. '<Comprador>'
					. '<Nome>'					. trim( $request->int_var['params']['nome'] )						. '</Nome>'
					. '<Email>'					. trim( $request->int_var['params']['email'] )						. '</Email>'
					. '<Cpf>'						. $cpf																					. '</Cpf>';

		if (trim( $request->int_var['params']['tipoPessoa']) == 'Juridica') {
					$content .= '<TipoPessoa>Juridica</TipoPessoa>'
					. '<RazaoSocial>'		. trim( $request->int_var['params']['razaoSocial'] )			. '</RazaoSocial>'
					. '<Cnpj>'					. $cnpj																					. '</Cnpj>';
		} else {
					$content .= '<TipoPessoa>Fisica</TipoPessoa>';
		}

		$content .= '</Comprador>'
					. '<Pagamento>'
					. '<Modulo>'				. trim( $request->int_var['params']['modulo'] )					. '</Modulo>';
					
		if (trim( $request->int_var['params']['modulo']) == 'CartaoCredito') {
					$content .= '<Tipo>Visa</Tipo>';
		}					
		
		$content .= '</Pagamento>'
					. '<Pedido>'
					. '<Numero>'				. trim( $request->int_var['invoice'] )									. '</Numero>'
					. '<ValorSubTotal>'		. $valorTotal																			. '</ValorSubTotal>'
					. '<ValorFrete>000</ValorFrete>'
					. '<ValorAcrescimo>000</ValorAcrescimo>'
					. '<ValorDesconto>000</ValorDesconto>'
					. '<ValorTotal>'			. $valorTotal																			. '</ValorTotal>'
					. '<Itens>'
					. '<Item>'
					. '<CodProduto>1</CodProduto>'
					. '<DescProduto>'		.  $subDesc																			. '</DescProduto>'
					. '<Quantidade>1</Quantidade>'
					. '<ValorUnitario>'		. $valorTotal																			. '</ValorUnitario>'
					. '<ValorTotal>'			. $valorTotal																			. '</ValorTotal>'
					. '</Item>'
					. '</Itens>'
					. '<Cobranca>'
					. '<Endereco>'			. trim( $request->int_var['params']['endereco'] )				. '</Endereco>'
					. '<Numero>'				. trim( $request->int_var['params']['complemento'] )		. '</Numero>'
					. '<Bairro>'					. trim( $request->int_var['params']['bairro'] )						. '</Bairro>'
					. '<Cidade>'				. trim( $request->int_var['params']['cidade'] )					. '</Cidade>'
					. '<Cep>'						. $cep																					. '</Cep>'
					. '<Estado>'				. trim( $request->int_var['params']['estado'] )					. '</Estado>'
					. '</Cobranca>'
					. '</Pedido>'
					. '</LocaWeb>';
					
		return $content;
	}

	function transmitRequestXML( $xmlTransacao, $request )
	{

		// ############# Inicio do registro da transação #############
		$wsPagamentoCertoLocaweb	= 'https://www.pagamentocerto.com.br/vendedor/vendedor.asmx?WSDL';			// Web Service para registro da transação
		$urlPagamentoCertoLocaweb	= 'https://www.pagamentocerto.com.br/pagamento/pagamento.aspx';					// URL para inicio da transação

		// Montagem dos dados da transação
		
		// Define os valores inicias de postagem
		$chaveVendedor						= $this->settings['chaveVendedor'];																														// Chave do vendedor
		$urlRetornoLoja						= AECToolbox::deadsureURL( '/index.php?option=com_acctexp&amp;task=locaweb_pgcertonotification' );		// URL de retorno
				
				
		$parms = new stdClass();
		
		// Inicializa o cliente SOAP
		$soap = @new SoapClient($wsPagamentoCertoLocaweb, array(
		        'trace' => true,
		        'exceptions' => true,
		        'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
		        'connection_timeout' => 1000
		));
		
		// Postagem dos parâmetros
		$parms->chaveVendedor		= utf8_encode($chaveVendedor);
		$parms->urlRetorno				= utf8_encode($urlRetornoLoja);
		$parms->xml							= utf8_encode($xmlTransacao);
		
		
		// Resgata o XML de retorno do processo
		$XMLresposta = $soap->IniciaTransacao($parms);
		$XMLresposta = $XMLresposta->IniciaTransacaoResult;
		
		// Carrega o XML
		$objDom = new DomDocument();
		$loadDom = $objDom->loadXML($XMLresposta);
		
		// Resgata os dados iniciais do retorno da transação
		$nodeCodRetornoInicio = $objDom->getElementsByTagName('CodRetorno')->item(0);
		$CodRetornoInicio = $nodeCodRetornoInicio->nodeValue;
		
		$nodeMensagemRetornoInicio = $objDom->getElementsByTagName('MensagemRetorno')->item(0);
		$MensagemRetorno = $nodeMensagemRetornoInicio->nodeValue;
		
		// Verifica se o registro da transação foi feito com sucesso
		if ($CodRetornoInicio == '0') {
		
			// Resgata o id e a mensagem da transação
			$nodeIdTransacao = $objDom->getElementsByTagName('IdTransacao')->item(0);
			$IdTransacao = $nodeIdTransacao->nodeValue;
		
			$nodeCodigoRef = $objDom->getElementsByTagName('Codigo')->item(0);
			$Codigo = $nodeCodigoRef->nodeValue;
		
			// Inicia a transação
			header('location: ' . $urlPagamentoCertoLocaweb . '?tdi=' . $IdTransacao);
			exit();
		
			// Em caso de erro no proceesso
		} else {
		
		    // Exibe a mensagem de erro
		    $return['error'] =	'<b>Erro: (' . utf8_decode($CodRetornoInicio) . ') ' . utf8_decode($MensagemRetorno) . '</b>';
		
		}
		
		// ############# Fim do registro da transação #############		

		return $return;
	
	}

	function parseNotification( $post )
	{

		$response = array();
		$response['invoice']						= '';
		$response['responsestring']		= '';
		
		// Endereços do Pagamento Certo
		$wsPagamentoCertoLocaweb		= "https://www.pagamentocerto.com.br/vendedor/vendedor.asmx?WSDL";		// Web Service para consulta da transação
		
		// Define os valores de retorno
		$chaveVendedor							= $this->settings['chaveVendedor'];																	// Chave do vendedor
		$idTransacao								= $post['tdi'];																										// ID da transação 
		
		// Verifica se o ID da transação foi postado
		if (trim($idTransacao) != '') {
		
			// Inicializa o cliente SOAP
			$soap = @new SoapClient($wsPagamentoCertoLocaweb, array(
					'trace' => true,
					'exceptions' => true,
					'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
					'connection_timeout' => 1000
			));
		
			// Postagem dos parâmetros
			$parms = new stdClass();
			$parms->chaveVendedor = utf8_encode($chaveVendedor);
			$parms->idTransacao = utf8_encode($idTransacao);
		
			// Resgata o XML de retorno do processo
			$XMLresposta = $soap->ConsultaTransacao($parms);
			$XMLresposta = $XMLresposta->ConsultaTransacaoResult;
		
			// Carrega o XML
			$objDom = new DomDocument();
			$loadDom = $objDom->loadXML($XMLresposta);
		
			// Resgata os dados iniciais do retorno da transação
			$nodeCodRetornoConsulta = $objDom->getElementsByTagName('CodRetorno')->item(0);
			$CodRetornoConsulta = $nodeCodRetornoConsulta->nodeValue;
		
			$nodeMensagemRetornoConsulta = $objDom->getElementsByTagName('MensagemRetorno')->item(0);
			$MensagemRetornoConsulta = $nodeMensagemRetornoConsulta->nodeValue;
		
			if ($CodRetornoConsulta == '15') {
		
				// Resgata os dados da transação
				$nodeIdTransacao = $objDom->getElementsByTagName('IdTransacao')->item(0);
				$IdTransacao = $nodeIdTransacao->nodeValue;
		
				$nodeCodigoTransacao = $objDom->getElementsByTagName('Codigo')->item(0);
				$Codigo = $nodeCodigoTransacao->nodeValue;
		
				$nodeDataTransacao = $objDom->getElementsByTagName('Data')->item(0);
				$Data = $nodeDataTransacao->nodeValue;
		
				// Resgata os dados do comprador no Pagamento Certo
				$nodeCompradorNome = $objDom->getElementsByTagName('Nome')->item(0);
				$Nome = $nodeCompradorNome->nodeValue;
		
				$nodeCompradorEmail = $objDom->getElementsByTagName('Email')->item(0);
				$Email = $nodeCompradorEmail->nodeValue;
		
				$nodeCompradorCpf = $objDom->getElementsByTagName('Cpf')->item(0);
				$Cpf = $nodeCompradorCpf->nodeValue;
		
				$nodeCompradorTipoPessoa = $objDom->getElementsByTagName('TipoPessoa')->item(0);
				$TipoPessoa = $nodeCompradorTipoPessoa->nodeValue;
		
				$nodeCompradorRazaoSocial = $objDom->getElementsByTagName('RazaoSocial')->item(0);
				$RazaoSocial = $nodeCompradorRazaoSocial->nodeValue;
		
				$nodeCompradorCNPJ = $objDom->getElementsByTagName('Cnpj')->item(0);
				$Cnpj = $nodeCompradorCNPJ->nodeValue;
		
		
				// Resgata os dados do pagamento
				$nodeMensagemModuloPagamento = $objDom->getElementsByTagName('Modulo')->item(0);
				$Modulo = $nodeMensagemModuloPagamento->nodeValue;
		
				$nodeMensagemTipoModuloPagamento = $objDom->getElementsByTagName('Tipo')->item(0);
				$Tipo = $nodeMensagemTipoModuloPagamento->nodeValue;
		
				$nodeProcessadoPagamento = $objDom->getElementsByTagName('Processado')->item(0);
				$Processado = $nodeProcessadoPagamento->nodeValue;
		
				$nodeMensagemRetornoPagamento = $objDom->getElementsByTagName('MensagemRetorno')->item(0);
				$MensagemRetorno = $nodeMensagemRetornoPagamento->nodeValue;
		
		
				// Resgata os dados do pedido
				$nodeCodigoPedido = $objDom->getElementsByTagName('Numero')->item(0);
				$Numero = $nodeCodigoPedido->nodeValue;
		
				$nodeValorTotal = $objDom->getElementsByTagName('ValorTotal')->item(0);
				$ValorTotal = $nodeValorTotal->nodeValue;
		
				/*
				// Exibe os dados de retorno da transação
				echo '<b>Dados da transação</b><br>';
				echo 'ID da transação: ' . utf8_decode($IdTransacao) . '<br>';
				echo 'Código da transação: ' . utf8_decode($Codigo) . '<br>';
				echo 'Data da transação: ' . utf8_decode($Data) . '<br>';
				echo 'Código de retorno da transação: ' . utf8_decode($CodRetornoConsulta) . '<br>';
				echo 'Mensagem de retorno da transação: ' . utf8_decode($MensagemRetornoConsulta) . '<br>';
		
				echo '<br>';
		
				// Exibe os dados de retorno do comprador no Pagamento Certo
				echo '<b>Dados do comprador no Pagamento Certo</b><br>';
				echo 'Nome: ' . utf8_decode($Nome) . '<br>';
				echo 'E-mail: ' . utf8_decode($Email) . '<br>';
				echo 'CPF: ' . utf8_decode($Cpf) . '<br>';
				echo 'Tipo de pessoa: ' . utf8_decode($TipoPessoa) . '<br>';
				if (trim(nodeCompradorRazaoSocial) != '') { 
					echo 'Razão Social: ' . utf8_decode($RazaoSocial) . '<br>';
				}
				if (trim(nodeCompradorCNPJ) != '') {
					echo 'CNPJ: ' . $Cnpj . '<br>';
				}
		
				echo '<br>';
		
				// Exibe os dados de retorno do pagamento
				echo '<b>Dados do pagamento</b><br>';
				echo 'Módulo do pagamento: ' . utf8_decode($Modulo) . '<br>';
				echo 'Tipo de pagamento: ' . utf8_decode($Tipo) . '<br>';
				echo 'Processado: ' . utf8_decode($Processado) . '<br>';
				echo 'Mensagem de retorno: ' . utf8_decode($MensagemRetorno) . '<br>';
		
				echo '<br>';
		
				// Exibe os dados de retorno do pedido
				echo '<b>Dados do pedido</b><br>';
				echo 'Número do pedido: ' . utf8_decode($Numero) . '<br>';
				echo 'Total do pedido: ' . utf8_decode($ValorTotal) . '<br>';
				*/
				$response = array();
				$response['invoice']						= utf8_decode($Numero);
				$response['valid']						= true;
				$response['responsestring']		= 'Processado: ' . utf8_decode($Processado) .  'Mensagem de retorno: ' . utf8_decode($MensagemRetorno);
				$response['amount_paid'] 			= $ValorTotal;
			} else {
		
				// Exibe a mensagem de erro
				$response['responsestring']		= 	'Erro: (' . utf8_decode($CodRetornoConsulta) . ') ' . utf8_decode($MensagemRetornoConsulta);
				$response['valid']						= false;
				$response['pending']					= true;
				if ($CodRetornoConsulta == '12' || $CodRetornoConsulta == '13') {
						$response['pending_reason']	=utf8_decode($MensagemRetornoConsulta);
				}
			}
		
		} else {
		
		    // Exibe a mensagem de erro
		    $response['responsestring']			= 	'Erro: ID da transação não informado.';		
			$response['valid']							= false;
			$response['pending']						= true;
			$response['pending_reason']			= $response['responsestring'];
		}		
		/*	
		// Just to verify the content. Should be commented on production
		$myFile = '/var/www/investidorlegal/log/pgcerto.xml';
		$fh = fopen($myFile, 'w') or die("can't open file");
		fwrite($fh, $XMLresposta);
		fclose($fh);
		*/

		return $response;
	}

	function validateNotification( $response, $post, $invoice )
	{
	/*
		if ( $post['x_subscription_paynum'] > 1 ) {
			$x_response_code		= $post['x_response_code'];
			$x_response_reason_text	= $post['x_response_reason_text'];

			$response['valid'] = ( $x_response_code == '1' );
		} else {
			$response['valid'] = 0;
			$response['duplicate'] = true;
		}
	*/
		return $response;

	}

}
?>
