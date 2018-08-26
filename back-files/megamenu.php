<?php
use app\base\Util;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\Empresa;

$_colaboradorCargo=Yii::$app->user->identity->colaborador_cargo;
$_colaboradorDados = Yii::$app->user->identity->colaborador_dados;

if(empty($_colaboradorDados))
{
    $_colaboradorDados=[];
}else{
    $_colaboradorDados=Json::decode($_colaboradorDados);
}
$colaboradorDados = Yii::$app->user->isGuest ? [] : $_colaboradorDados;

?>

<div  class="collapse navbar-collapse pull-left">
	<ul id="navbar-collapse" class="nav navbar-nav">
		<li class="dropdown menu-large">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-barcode font18"></i> Produtos</a>
			<ul class="dropdown-menu megamenu row">
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header"><i class="fa fa-edit"></i> Cadastro</li>
						<li><a href="<?php echo Url::to(['/produto/create'])?>"><i class="fa fa-plus"></i> Incluir Novo Produto</a></li>
						<li><a href="<?php echo Url::to(['/produto'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li><a href="<?php echo Url::to(['/produto/atualizar-produtos'])?>"><i class="fa fa-pencil-square-o"></i> Atualizar Produtos</a></li>
						<li><a href="<?php echo Url::to(['/produto/reajuste-precos'])?>"><i class="fa fa-usd"></i> Reajuste de Preços</a></li>
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header"><i class="fa fa-line-chart"></i> Relatório</li>
						<li><a href="<?php echo Url::to(['/produto/curva-abc'])?>"><i class="fa fa-search"></i> Curva ABC </a></li>
						<li><a href="<?php echo Url::to(['/produto/relatorio-vendidos'])?>"><i class="fa fa-search"></i> Produtos Vendidos</a></li>
						<li><a href="<?php echo Url::to(['/produto/relatorio-precos'])?>"><i class="fa fa-search"></i> Relatório de Precos</a></li>
					</ul>
				</li>
			</ul>
		</li>

		<li class="dropdown menu-large">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-shopping-cart font18"></i> Frente de Caixa</a>
			<ul class="dropdown-menu megamenu row">
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">PDV</li>
						<li><a href="<?php echo Url::to(['/pdv'])?>"><i class="fa fa-plus"></i> PDV</a></li>

						<li class="dropdown-header">Caixa</li>
						<li><a href="<?php echo Url::to(['/caixa-diario'])?>"><i class="fa fa-money"></i> Ir para o Caixa</a></li>
						<li><a href="<?php echo Url::to(['/caixa-diario/visualiza-todos-caixas'])?>"><i class="fa fa-search"></i> Consultar Caixas</a></li>
						<li><a href="<?php echo Url::to(['/caixa-diario/conferencia-caixa'])?>"><i class="fa fa-list-ul"></i> Conferência de Caixa</a></li>
						<!-- <li><a href="#"><i class="fa fa-sign-out"></i> Fechar Caixa</a></li> -->
					</ul>

				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Pedido / Orçamento</li>
						<li><a href="<?php echo Url::to(['/pedido/novo'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/pedido'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li><a href="<?php echo Url::to(['/pedido/cancela-pedidos'])?>"><i class="fa fa-times"></i> Cancelar Pedidos</a></li>
						<li><a href="<?php echo Url::to(['/pedido/agrupar-pedidos'])?>"><i class="fa fa-search"></i> Agrupar Pedidos a Faturar</a></li>
						<li class="dropdown-header">Pedido Especial</li>
						<li><a href="<?php echo Url::to(['/pedido/novo-especial'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Gerenciamento de Nota Fiscal</li>
						<li><a href="<?php echo Url::to(['/nfe-enviadas/index'])?>"><i class="fa fa-file-o"></i> Listar</a></li>
						<li><a href="<?php echo Url::to(['/nfe-envio'])?>"><i class="fa fa-exchange"></i> Notas Fiscais Emitidas</a></li>
						<li><a href="<?php echo Url::to(['/nfe-enviadas/inutiliza'])?>"><i class="fa fa-file-excel-o"></i> Inutilizar</a></li>
						<li><a href="<?php echo Url::to(['/nfe-enviadas/contingencias'])?>"><i class="fa fa-chain-broken"></i> Contingências</a></li>
						<!-- <li><a href="<?php echo Url::to(['/nfe/default/index'])?>"><i class="fa fa-file-text-o"></i> Nfe Distribuidas</a></li> -->
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Relatório de Caixa</li>
						<li><a href="<?php echo Url::to(['/caixa-diario/relatorio'])?>"><i class="fa fa-search"></i> Consultar Relatório</a></li>
						<li class="dropdown-header">Relatório de Vendas</li>
						<li><a href="<?php echo Url::to(['/pedido/relatorio'])?>"><i class="fa fa-search"></i> Consultar Relatório</a></li>
						<li class="dropdown-header">Relatório de Notas Fiscais</li>
						<li><a href="<?php echo Url::to(['/nfe-exportar'])?>"><i class="fa fa-search"></i> Consultar Relatório</a></li>
						<li class="dropdown-header">Relatório Pedidos a Faturar</li>
						<li><a href="<?php echo Url::to(['/pedido/relatorio-pedidos-faturar'])?>"><i class="fa fa-search"></i> Consultar Relatório</a></li>
					</ul>
				</li>
			</ul>
		</li>

		<li class="dropdown menu-large">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-calculator font18"></i> Financeiro</a>
			<ul class="dropdown-menu megamenu row">
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Painel de controle</li>
						<li><a href="<?php echo Url::to(['/financeiro'])?>"><i class="fa fa-bar-chart"></i> Resumo</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo Url::to(['/financeiro/fluxo-caixa/index'])?>"><i class="fa fa-line-chart"></i> Fluxo de Caixa</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/extrato-bancario/index'])?>"><i class="fa fa-sticky-note-o"></i> Extrato Banco</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/extrato-caixa/index'])?>"><i class="fa fa-sticky-note-o"></i> Extrato Caixa</a></li>
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Contas a Receber</li>
						<li><a href="<?php echo Url::to(['/financeiro/receita/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/receita/index'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/relatorio-conta-receber'])?>"><i class="fa fa-bar-chart"></i> Relatório</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/conta-receber/index'])?>"><i class="fa fa-arrow-up"></i> Receber Conta</a></li>
						<li class="dropdown-header">Contas a Pagar</li>
						<li><a href="<?php echo Url::to(['/financeiro/despesa/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/despesa/index'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/relatorio-conta-pagar'])?>"><i class="fa fa-bar-chart"></i> Relatório</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/conta-pagar/index'])?>"><i class="fa fa-arrow-down"></i> Pagar Conta</a></li>
					</ul>

				</li>
				
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Boletos</li>
						<li><a href="<?php echo Url::to(['/boleto/create'])?>"><i class="fa fa-plus"></i> Emitir</a></li>						
						<li><a href="<?php echo Url::to(['/boleto'])?>"><i class="fa fa-search"></i> Consultar / 2&ordf; Via</a></li>
					</ul>
					<ul>
						<li class="dropdown-header">Remessa & Retorno</li>
						<li><a href="<?php echo Url::to(['/remessa'])?>"><i class="fa fa-search"></i> Remessas Bancárias</a></li>
						<li><a href="<?php echo Url::to(['/retorno'])?>"><i class="fa fa-search"></i> Retornos Bancários</a></li>
					</ul>
					<ul>
						<li class="dropdown-header">Códigos CNAB</li>
						<li><a href="<?php echo Url::to(['/codigos-conciliacao-bancaria'])?>"><i class="fa fa-search"></i> Consultar</a></li>
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Movimentos financeiros</li>
						<li><a href="<?php echo Url::to(['/financeiro/movimento-banco'])?>"><i class="fa fa-exchange"></i> Movimentação Bancária</a></li>
						<li><a href="<?php echo Url::to(['/financeiro/movimento-carteira'])?>"><i class="fa fa-exchange"></i> Movimentação Caixa Interno</a></li>
					</ul>
				</li>
			</ul>
		</li>

		<li class="dropdown menu-large">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-th-large font18"></i> Almoxarifado</a>
			<ul class="dropdown-menu megamenu row">
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Ordem de Compra</li>
						<li><a href="<?php echo Url::to(['/ordem-compra/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/ordem-compra'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li><a href="<?php echo Url::to(['/ordem-compra/fluxo-compra'])?>"><i class="fa fa-search"></i> Fluxo de Compra (Ordens de Compra Aprovadas)</a></li>
						<li><a href="<?php echo Url::to(['/ordem-compra/approval'])?>"><i class="fa fa-check"></i> Aprovar</a></li>
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Nota Entrada</li>
						<li><a href="<?php echo Url::to(['/nfe-entrada/index'])?>"><i class="fa fa-search"></i> Consulta</a></li>
					</ul>
				
					<ul>
						<li class="dropdown-header">Nota Distribuída</li>
						<li><a href="<?php echo Url::to(['/nfe-distribuida/index'])?>"><i class="fa fa-search"></i> Consulta</a></li>
					</ul>

					<ul>
						<li class="dropdown-header">Devoluções</li>
						<li><a href="<?php echo Url::to(['/devolucao/devolucao-compra'])?>"><i class="fa fa-plus"></i> Incluir Devolução de Compra</a></li>
						<li><a href="<?php echo Url::to(['/devolucao/devolucao-venda'])?>"><i class="fa fa-plus"></i> Incluir Devolução de Venda</a></li>
						<li><a href="<?php echo Url::to(['/devolucao/index'])?>"><i class="fa fa-search"></i> Consultar</a></li>
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Movimento de Estoque</li>
						<li><a href="<?php echo Url::to(['/movimento-estoque/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/movimento-estoque'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li class="dropdown-header">Inventário de Estoque</li>
						<li><a href="<?php echo Url::to(['/estoque-inventario'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li class="dropdown-header">Transferências de Estoque</li>
                        <li><a href="<?php echo Url::to(['/transferencia/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
                        <li><a href="<?php echo Url::to(['/transferencia/solicitar'])?>"><i class="fa fa-plus"></i> Solicitar (Solicite produtos a outras empresas do grupo)</a></li>
                        <li><a href="<?php echo Url::to(['/transferencia'])?>"><i class="fa fa-search"></i> Consultar Emitidas</a></li>
                        <li><a href="<?php echo Url::to(['/transferencia/entrada'])?>"><i class="fa fa-search"></i> Consultar Recebidas</a></li>
					</ul>
				</li>

				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Relatórios</li>
						<li><a href="<?php echo Url::to(['/produto-estoque'])?>"><i class="fa fa-edit"></i> Consulta de Estoque</a></li>
						<li><a href="<?php echo Url::to(['/estoque-inventario/relatorio'])?>"><i class="fa fa-edit"></i> Inventário de Estoque</a></li>
						<li><a href="<?php echo Url::to(['/produto/relatorio-vendidos'])?>"><i class="fa fa-edit"></i> Produtos Vendidos</a></li>
						<li><a href="<?php echo Url::to(['/ordem-compra/sugestao-compra'])?>"><i class="fa fa-edit"></i> Sugestão de Compra</a></li>
					</ul>
				</li>
			</ul>
		</li>

		<li class="dropdown menu-large">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-briefcase font18" aria-hidden="true"></i> Especiais</a>
			<ul class="dropdown-menu megamenu row">
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Ordem de Produção</li>
						<li><a href="<?php echo Url::to(['/ordem-producao/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/ordem-producao/index'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li><a href="<?php echo Url::to(['/ordem-producao/relatorio'])?>"><i class="fa fa-search"></i> Relatório</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">E-mail</li>
						<li><a href="<?php echo Url::to(['/email'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li><a href="<?php echo Url::to(['/email/create'])?>"><i class="fa fa-plus"></i> Novo E-mail</a></li>
					</ul>
				</li>
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Contratos de Serviços</li>
						<li><a href="<?php echo Url::to(['/contrato/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/contrato'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">Serviços</li>
						<li><a href="<?php echo Url::to(['/servicos/create'])?>"><i class="fa fa-plus"></i> Incluir</a></li>
						<li><a href="<?php echo Url::to(['/servicos'])?>"><i class="fa fa-search"></i> Consultar</a></li>
						<li class="divider"></li>
						<li class="dropdown-header">Nota Fiscal de Serviços</li>
						<li><a href="<?php echo Url::to(['/nfservicos/create'])?>"><i class="fa fa-plus"></i> Emitir</a></li>
						<li><a href="<?php echo Url::to(['/nfservicos'])?>"><i class="fa fa-search"></i> Consultar</a></li>
					</ul>
				</li>
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Pedidos</li>
						<li><a href="<?php echo Url::to(['/pedido/gerenciar-pedidos'])?>"><i class="fa fa-list-alt"></i> Gerenciar Pedidos</a></li>
						<li><a href="<?php echo Url::to(['/pedido/preparar-pedidos'])?>"><i class="fa fa-th-list"></i> Preparar Pedidos</a></li>
						<li><a href="<?php echo Url::to(['/pedido/acompanhar-pedidos'])?>"><i class="fa fa-list-ol"></i> Acompanhar Pedidos</a></li>
					</ul>
				</li>
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header">Módulo de Relatórios</li>
						<li><a target="_new" href="<?php echo Url::to(['/relatorio/index'])?>"><i class="fa fa-sign-in"></i> Acesso</a></li>
					</ul>
				</li>
			</ul>
		</li>

		<li class="dropdown menu-large">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-edit font18"></i> Cadastros</a>
			<ul class="dropdown-menu megamenu row">
				<li class="col-sm-3">
					<ul>
						<li class="dropdown-header"><i class="fa fa-star"></i> Principais</li>
						<li><a href="<?php echo Url::to(['/empresa'])?>"><i class="fa fa-building-o"></i> Empresa</a></li>
						<li><a href="<?php echo Url::to(['/colaborador'])?>"><i class="fa fa-users"></i> Colaborador</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo Url::to(['/clientes'])?>"><i class="fa fa-user"></i> Cliente</a></li>
						<li><a href="<?php echo Url::to(['/fornecedores'])?>"><i class="fa fa-industry"></i> Fornecedor</a></li>
						<li><a href="<?php echo Url::to(['/transportadora'])?>"><i class="fa fa-truck"></i> Transportadora</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo Url::to(['/contador'])?>"><i class="fa fa-briefcase"></i> Contador</a></li>

						<li class="dropdown-header top15"><i class="fa fa-wrench"></i>  Administração</li>
						<li><a href="<?php echo Url::to(['/empresa/update/'.\Yii::$app->user->identity->empresa_id])?>"><i class="fa fa-cog"></i> Configurações da Empresa</a></li>
						<li><a href="<?php echo Url::to(['/auth/permissao'])?>"><i class="fa fa-lock"></i> Permissões de Usuários</a></li>
						<li><a href="<?php echo Url::to(['/user-config'])?>"><i class="fa fa-star"></i> Configurações de Preferência</a></li>
					</ul>
				</li>
				<li class="col-sm-9">
					<div class="col-md-4">
						<ul>
							<li class="dropdown-header"><i class="fa fa-barcode"></i> Produto</li>
							<li><a href="<?php echo Url::to(['/produto-categoria'])?>">Categoria</a></li>
							<li><a href="<?php echo Url::to(['/cor'])?>">Cor</a></li>
							<li><a href="<?php echo Url::to(['/fabricante'])?>">Fabricante</a></li>
							<li><a href="<?php echo Url::to(['/produto-grupo'])?>">Grupo</a></li>
							<li><a href="<?php echo Url::to(['/produto-sub-grupo'])?>">Subgrupo</a></li>
							<li><a href="<?php echo Url::to(['/produto-unidade'])?>">Unidade</a></li>

							<li class="dropdown-header top15"><i class="fa fa-th-large"></i> Estoque</li>
							<li><a href="<?php echo Url::to(['/tipo-movimento-produto'])?>">Tipo do Movimento</a></li>

							<li class="dropdown-header top15"><i class="fa fa-shopping-basket"></i> Caixa</li>
							<li><a href="<?php echo Url::to(['/caixa-estacao'])?>">Caixa <em>(Estações de Trabalho)</em></a></li>
						</ul>
					</div>

					<div class="col-md-4">
						<ul>
							<li class="dropdown-header"><i class="fa fa-money"></i> Financeiro</li>
							<li><a href="<?php echo Url::to(['/financeiro/banco'])?>">Banco</a></li>
							<li><a href="<?php echo Url::to(['/financeiro/agencia'])?>">Agência</a></li>
							<li><a href="<?php echo Url::to(['/financeiro/conta'])?>">Conta</a></li>
							<li><a href="<?php echo Url::to(['/financeiro/carteira'])?>">Caixa Interno</a></li>
							<li class="divider"></li>
							<!--							<li><a href="--><?php //echo Url::to(['/administradora-cartao-credito'])?><!--">Adm. Cartão de Crédito</a></li>-->
							<li><a href="<?php echo Url::to(['/financeiro/forma-pagamento'])?>">Forma de Pagamento</a></li>
							<li><a href="<?php echo Url::to(['/condicao-pagamento'])?>">Condição de Pagamento</a></li>
							<!--							<li><a href="--><?php //echo Url::to(['/condicao-pagamento'])?><!--">Condição de Pagamento</a></li>-->
							<li class="divider"></li>
							<li><a href="<?php echo Url::to(['/financeiro/centro-custo'])?>">Centro de Custo</a></li>
							<li><a href="<?php echo Url::to(['/financeiro/conta-orcamentaria'])?>">Conta Orçamentária</a></li>
							<li><a href="<?php echo Url::to(['/financeiro/historico-movimento-financeiro'])?>">Histórico Mov. Financeiro</a></li>
							<!--							<li><a href="--><?php //echo Url::to(['/plano-contas/create'])?><!--">Previsão Orçamentária</a></li>-->
							<!--							<li><a href="--><?php //echo Url::to(['/ocorrencia-cnab'])?><!--">Ocorrência CNAB</a></li>-->
							<li><a href="<?php echo Url::to(['/administradora-cartao-credito'])?>">Adm. Cartão de Crédito</a></li>
						</ul>
					</div>

					<div class="col-md-4">
						<ul>
							<li class="dropdown-header"><i class="fa fa-building-o"></i> Empresa</li>
							<li><a href="<?php echo Url::to(['/empresa-grupo'])?>">Grupo de Empresa</a></li>
							<li><a href="<?php echo Url::to(['/cargo'])?>">Cargo</a></li>
							<li><a href="<?php echo Url::to(['/setor'])?>">Setor</a></li>

							<li class="dropdown-header top15"><i class="fa fa-briefcase"></i> Fiscal</li>
							<li><a href="<?php echo Url::to(['/aliquota-reducao-icms'])?>">Aliquota de Redução do ICMS</a></li>
							<li><a href="<?php echo Url::to(['/tipo-tributacao-fiscal'])?>">Tipo de Tributação Fiscal</a></li>
							<li><a href="<?php echo Url::to(['/situacao-tributaria'])?>">Situação Tributária</a></li>

							<li class="dropdown-header top15"><i class="fa fa-gears"></i> Gerais</li>
							<li><a href="<?php echo Url::to(['/estado-federacao'])?>">Estado</a></li>
							<li><a href="<?php echo Url::to(['/cidade'])?>">Cidade</a></li>
						</ul>
					</div>
				</li>
			</ul>
		</li>
	</ul>
</div>
<!-- ./mega-menu padrao -->
<div id="wrapper">
		<div class="overlay"></div>

		<!-- Sidebar -->
		<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<ul id="sidebar" class="nav sidebar-nav">
			<?php
    	         if (array_key_exists('empresa_id', $colaboradorDados)){
    	            $empresaConfig = Empresa::findOne($colaboradorDados['empresa_id']);
    	         }
	        ?>
			<li>
            <a id="logo" class="navbar-brand font20" style="text-align: center;" href="/"><b>GER</b>CPN
               <p class="navbar-fluid font11" title="<?= $empresaConfig['razao_social'] ?>"><?= Util::shortName($empresaConfig['razao_social'], 30); ?></p>
            </a>
					</li>
						<li class="dropdown menu-large">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-barcode font18"></i> Produtos</a>
							<ul class="dropdown-menu megamenu row">
								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff"><i class="fa fa-edit"></i> Cadastro</li>
										<li><a href="<?php echo Url::to(['/produto/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir Novo Produto</a></li>
										<li><a href="<?php echo Url::to(['/produto'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li><a href="<?php echo Url::to(['/produto/atualizar-produtos'])?>"style="color: #fff"><i class="fa fa-pencil-square-o"></i> Atualizar Produtos</a></li>
										<li><a href="<?php echo Url::to(['/produto/reajuste-precos'])?>"style="color: #fff"><i class="fa fa-usd"></i> Reajuste de Preços</a></li>
										<li class="divider"></li>
									</ul>
								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff"><i class="fa fa-line-chart"></i> Relatório</li>
										<li><a href="<?php echo Url::to(['/produto/curva-abc'])?>"style="color: #fff"><i class="fa fa-search"></i> Curva ABC </a></li>
										<li><a href="<?php echo Url::to(['/produto/relatorio-vendidos'])?>"style="color: #fff"><i class="fa fa-search"></i> Produtos Vendidos</a></li>
										<li><a href="<?php echo Url::to(['/produto/relatorio-precos'])?>"style="color: #fff"><i class="fa fa-search"></i> Relatório de Precos</a></li>
										<li class="divider"></li>
									</ul>
								</li>
							</ul>
						</li>

						<li class="dropdown menu-large">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-shopping-cart font18"></i> Frente de Caixa</a>
							<ul class="dropdown-menu megamenu row">
								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">PDV</li>
										<li><a href="<?php echo Url::to(['/pdv'])?>"style="color: #fff"><i class="fa fa-plus"></i> PDV</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Caixa</li>
										<li><a href="<?php echo Url::to(['/caixa-diario'])?>"style="color: #fff"><i class="fa fa-money"></i> Ir para o Caixa</a></li>
										<li><a href="<?php echo Url::to(['/caixa-diario/visualiza-todos-caixas'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Caixas</a></li>
										<li class="divider"></li>
										<!-- <li><a href="#"><i class="fa fa-sign-out"></i> Fechar Caixa</a></li> -->
									</ul>

								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header"  style="color: #fff">Pedido / Orçamento</li>
										<li><a href="<?php echo Url::to(['/pedido/novo'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li><a href="<?php echo Url::to(['/pedido'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li><a href="<?php echo Url::to(['/pedido/cancela-pedidos'])?>"style="color: #fff"><i class="fa fa-times"></i> Cancelar Pedidos</a></li>
										<li><a href="<?php echo Url::to(['/pedido/agrupar-pedidos'])?>"style="color: #fff"><i class="fa fa-search"></i> Agrupar Pedidos a Faturar</a></li>
										<li class="divider"></li>
										<li class="dropdown-header"  style="color: #fff">Pedido Especial</li>
										<li><a href="<?php echo Url::to(['/pedido/novo-especial'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li class="divider"></li>
									</ul>
								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header"  style="color: #fff">Gerenciamento de Nota Fiscal</li>
										<li><a href="<?php echo Url::to(['/nfe-enviadas/index'])?>"style="color: #fff"><i class="fa fa-file-o"></i> Listar</a></li>
										<li><a href="<?php echo Url::to(['/nfe-envio'])?>"style="color: #fff"><i class="fa fa-exchange"></i> Notas Fiscais Emitidas</a></li>
										<li><a href="<?php echo Url::to(['/nfe-enviadas/inutiliza'])?>"style="color: #fff"><i class="fa fa-file-excel-o"></i> Inutilizar</a></li>
										<li><a href="<?php echo Url::to(['/nfe-enviadas/contingencias'])?>"style="color: #fff"><i class="fa fa-chain-broken"></i> Contingências</a></li>
										<!-- <li><a href="<?php echo Url::to(['/nfe/default/index'])?>"><i class="fa fa-file-text-o"></i> Nfe Distribuidas</a></li> -->
										<li class="divider"></li>
									</ul>
								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Relatório de Caixa</li>
										<li><a href="<?php echo Url::to(['/caixa-diario/relatorio'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Relatório</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Relatório de Vendas</li>
										<li><a href="<?php echo Url::to(['/pedido/relatorio'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Relatório</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Relatório de Notas Fiscais</li>
										<li><a href="<?php echo Url::to(['/nfe-exportar'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Relatório</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Relatório Pedidos a Faturar</li>
										<li><a href="<?php echo Url::to(['/pedido/relatorio-pedidos-faturar'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Relatório</a></li>
										<li class="divider"></li>
									</ul>
								</li>
							</ul>
						</li>

						<li class="dropdown menu-large">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-calculator font18"></i> Financeiro</a>
							<ul class="dropdown-menu megamenu row">
								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header"  style="color: #fff">Painel de controle</li>
										<li><a href="<?php echo Url::to(['/financeiro'])?>"style="color: #fff"><i class="fa fa-bar-chart"></i> Resumo</a></li>
										<li class="divider"></li>
										<li><a href="<?php echo Url::to(['/financeiro/fluxo-caixa/index'])?>"style="color: #fff"><i class="fa fa-line-chart"></i> Fluxo de Caixa</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/extrato-bancario/index'])?>"style="color: #fff"><i class="fa fa-sticky-note-o"></i> Extrato Banco</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/extrato-caixa/index'])?>"style="color: #fff"><i class="fa fa-sticky-note-o"></i> Extrato Caixa</a></li>
										<li class="divider"></li>
									</ul>
									<ul>
										<li class="dropdown-header" style="color: #fff">Boletos</li>
										<li><a href="<?php echo Url::to(['/boleto'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar / 2&ordf; Via</a></li>
										<li><a href="<?php echo Url::to(['/boleto/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Emitir</a></li>
										<li><a href="<?php echo Url::to(['/remessa/upload-arquivo-retorno'])?>"style="color: #fff"><i class="fa fa-upload"></i> Enviar Arquivo de Retorno</a></li>
										<li><a href="<?php echo Url::to(['/remessa'])?>"style="color: #fff"><i class="fa fa-search"></i> Remessa e Retorno</a></li>
										<li class="divider"></li>
									</ul>

								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Contas a Receber</li>
										<li><a href="<?php echo Url::to(['/financeiro/receita/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/receita/index'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/relatorio-conta-receber'])?>"style="color: #fff"><i class="fa fa-bar-chart"></i> Relatório</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/conta-receber/index'])?>"style="color: #fff"><i class="fa fa-arrow-up"></i> Receber Conta</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Contas a Pagar</li>
										<li><a href="<?php echo Url::to(['/financeiro/despesa/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/despesa/index'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/conta-pagar/index'])?>"style="color: #fff"><i class="fa fa-arrow-down"></i> Pagar Conta</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/relatorio-conta-pagar'])?>"style="color: #fff"><i class="fa fa-bar-chart"></i> Relatório</a></li>
										<li class="divider"></li>
				<!--
						<li><a href="--><?php //echo Url::to(['/faturas-pagar/estorno'])?><!--"><i class="fa fa-undo"></i> Estornar Conta Paga</a></li>-->
									</ul>

								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Movimentos financeiros</li>
										<li><a href="<?php echo Url::to(['/financeiro/movimento-banco'])?>"style="color: #fff"><i class="fa fa-exchange"></i> Movimentação Bancária</a></li>
										<li><a href="<?php echo Url::to(['/financeiro/movimento-carteira'])?>"style="color: #fff"><i class="fa fa-exchange"></i> Movimentação Caixa Interno</a></li>
										<li class="divider"></li>
									</ul>
								</li>
							</ul>
						</li>

						<li class="dropdown menu-large">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-th-large font18"></i> Almoxarifado</a>
							<ul class="dropdown-menu megamenu row">
								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Ordem de Compra</li>
										<li><a href="<?php echo Url::to(['/ordem-compra/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li><a href="<?php echo Url::to(['/ordem-compra'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li><a href="<?php echo Url::to(['/ordem-compra/fluxo-compra'])?>"style="color: #fff"><i class="fa fa-search"></i> Fluxo de Compra (Ordens de Compra Aprovadas)</a></li>
										<li><a href="<?php echo Url::to(['/ordem-compra/approval'])?>"style="color: #fff"><i class="fa fa-check"></i> Aprovar</a></li>
										<li class="divider"></li>
									</ul>
								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Nota Fiscal de Entrada</li>
										<li><a href="<?php echo Url::to(['/nfe-entrada/create'])?>"style="color: #fff"><i class="fa fa-file-o"></i> Criar</a></li>
										<li><a href="<?php echo Url::to(['/nfe-distribuida/index'])?>"style="color: #fff"><i class="fa fa-search"></i> Consulta</a></li>
										<li><a href="<?php echo Url::to(['/nfe-distribuida/index'])?>"style="color: #fff"><i class="fa fa-download"></i> Download</a></li>
										<li><a href="<?php echo Url::to(['/nfe-distribuida/index'])?>"style="color: #fff"><i class="fa fa-upload"></i> Importação</a></li>
										<li class="divider"></li>
									</ul>

									<ul>
										<li class="dropdown-header" style="color: #fff">Devoluções</li>
										<li><a href="<?php echo Url::to(['/devolucao/devolucao-compra'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir Devolução de Compra</a></li>
										<li><a href="<?php echo Url::to(['/devolucao/devolucao-venda'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir Devolução de Venda</a></li>
										<li><a href="<?php echo Url::to(['/devolucao/index'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li class="divider"></li>
									</ul>
								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Movimento de Estoque</li>
										<li><a href="<?php echo Url::to(['/movimento-estoque/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li><a href="<?php echo Url::to(['/movimento-estoque'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Inventário de Estoque</li>
										<li><a href="<?php echo Url::to(['/estoque-inventario'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Transferências de Estoque</li>
										<li><a href="<?php echo Url::to(['/transferencia/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li><a href="<?php echo Url::to(['/transferencia/solicitar'])?>"style="color: #fff"><i class="fa fa-plus"></i> Solicitar (Solicite produtos a outras empresas do grupo)</a></li>
										<li><a href="<?php echo Url::to(['/transferencia'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Emitidas</a></li>
										<li><a href="<?php echo Url::to(['/transferencia/entrada'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Recebidas</a></li>
										<li class="divider"></li>
									</ul>
								</li>

								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Relatórios</li>
										<li><a href="<?php echo Url::to(['/produto-estoque'])?>"style="color: #fff"><i class="fa fa-edit"></i> Consulta de Estoque</a></li>
										<li><a href="<?php echo Url::to(['/estoque-inventario/relatorio'])?>"style="color: #fff"><i class="fa fa-edit"></i> Inventário de Estoque</a></li>
										<li><a href="<?php echo Url::to(['/produto/relatorio-vendidos'])?>"style="color: #fff"><i class="fa fa-edit"></i> Produtos Vendidos</a></li>
										<li><a href="<?php echo Url::to(['/ordem-compra/sugestao-compra'])?>"style="color: #fff"><i class="fa fa-edit"></i> Sugestão de Compra</a></li>
										<li class="divider"></li>
									</ul>
								</li>
							</ul>
						</li>

						<li class="dropdown menu-large">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-briefcase font18" aria-hidden="true"></i> Especiais</a>
							<ul class="dropdown-menu megamenu row">
								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Ordem de Produção</li>
										<li><a href="<?php echo Url::to(['/ordem-producao/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li><a href="<?php echo Url::to(['/ordem-producao/index'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Relatório</li>
										<li><a href="<?php echo Url::to(['/ordem-producao/relatorio'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar Relatório</a></li>
									</ul>
								</li>
								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff">Contratos de Serviços</li>
										<li><a href="<?php echo Url::to(['/contrato'])?>"style="color: #fff"><i class="fa fa-search"></i> Contratos de prestação de serviços</a></li>
										<li><a href="<?php echo Url::to(['/contrato/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir contrato</a></li>
										<li class="divider"></li>
										<li class="dropdown-header" style="color: #fff">Serviços</li>
										<li><a href="<?php echo Url::to(['/servicos'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li><a href="<?php echo Url::to(['/servicos/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Incluir</a></li>
										<li class="divider"></li>
										<li class="dropdown-header"  style="color: #fff">Nota Fiscal de Serviços</li>
										<li><a href="<?php echo Url::to(['/nfservicos'])?>"style="color: #fff"><i class="fa fa-search"></i> Consultar</a></li>
										<li><a href="<?php echo Url::to(['/nfservicos/create'])?>"style="color: #fff"><i class="fa fa-plus"></i> Emitir NFSe</a></li>
										<li class="divider"></li>
									</ul>
								</li>
								<li class="col-sm-3">
                					<ul>
                						<li class="dropdown-header" style="color: #fff">Pedidos</li>
                						<li><a href="<?php echo Url::to(['/pedido/gerenciar-pedidos'])?>"><i class="fa fa-list-alt"></i> Gerenciar Pedidos</a></li>
                						<li><a href="<?php echo Url::to(['/pedido/preparar-pedidos'])?>"><i class="fa fa-th-list"></i> Preparar Pedidos</a></li>
                						<li><a href="<?php echo Url::to(['/pedido/acompanhar-pedidos'])?>"><i class="fa fa-list-ol"></i> Acompanhar Pedidos</a></li>
                						<li class="divider"></li>
                					</ul>
                				</li>
								<li class="col-sm-3">
                					<ul>
                						<li class="dropdown-header" style="color: #fff">E-mail</li>
                						<li><a href="<?php echo Url::to(['/email'])?>"><i class="fa fa-search"></i> Consultar</a></li>
                						<li><a href="<?php echo Url::to(['/email/create'])?>"><i class="fa fa-plus"></i> Novo E-mail</a></li>
                						<li class="divider"></li>
                					</ul>
                				</li>
							</ul>
						</li>

						<li class="dropdown menu-large">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-edit font18"></i> Cadastros</a>
							<ul class="dropdown-menu megamenu row">
								<li class="col-sm-3">
									<ul>
										<li class="dropdown-header" style="color: #fff"style="color: #fff"><i class="fa fa-star"></i> Principais</li>
										<li><a href="<?php echo Url::to(['/empresa'])?>"style="color: #fff"><i class="fa fa-building-o"></i> Empresa</a></li>
										<li><a href="<?php echo Url::to(['/colaborador'])?>"style="color: #fff"><i class="fa fa-users"></i> Colaborador</a></li>
										<li class="divider"></li>
										<li><a href="<?php echo Url::to(['/clientes'])?>"style="color: #fff"><i class="fa fa-user"></i> Cliente</a></li>
										<li><a href="<?php echo Url::to(['/fornecedores'])?>"style="color: #fff"><i class="fa fa-industry"></i> Fornecedor</a></li>
										<li><a href="<?php echo Url::to(['/transportadora'])?>"style="color: #fff"><i class="fa fa-truck"></i> Transportadora</a></li>
										<li class="divider"></li>
										<li><a href="<?php echo Url::to(['/contador'])?>"style="color: #fff"><i class="fa fa-briefcase"></i> Contador</a></li>
										<li class="divider"></li>
										<li class="dropdown-header top15" style="color: #fff"><i class="fa fa-wrench"></i>  Administração</li>
										<li><a href="<?php echo Url::to(['/empresa/update/'.\Yii::$app->user->identity->empresa_id])?>"style="color: #fff"><i class="fa fa-cog"></i> Configurações da Empresa</a></li>
										<li><a href="<?php echo Url::to(['/auth/permissao'])?>"style="color: #fff"><i class="fa fa-lock"></i> Permissões de Usuários</a></li>
										<li class="divider"></li>
									</ul>
								</li>
								<li class="col-sm-9">
									<div class="col-md-4">
										<ul>
											<li class="dropdown-header" style="color: #fff"><i class="fa fa-barcode"></i> Produto</li>
											<li><a href="<?php echo Url::to(['/produto-categoria'])?>"style="color: #fff">Categoria</a></li>
											<li><a href="<?php echo Url::to(['/cor'])?>"style="color: #fff">Cor</a></li>
											<li><a href="<?php echo Url::to(['/fabricante'])?>"style="color: #fff">Fabricante</a></li>
											<li><a href="<?php echo Url::to(['/produto-grupo'])?>"style="color: #fff">Grupo</a></li>
											<li><a href="<?php echo Url::to(['/produto-sub-grupo'])?>"style="color: #fff">Subgrupo</a></li>
											<li><a href="<?php echo Url::to(['/produto-unidade'])?>"style="color: #fff">Unidade</a></li>
											<li class="divider"></li>
											<li class="dropdown-header top15" style="color: #fff"><i class="fa fa-th-large"></i> Estoque</li>
											<li><a href="<?php echo Url::to(['/tipo-movimento-produto'])?>"style="color: #fff">Tipo do Movimento</a></li>
											<li class="divider"></li>
											<li class="dropdown-header top15" style="color: #fff"><i class="fa fa-shopping-basket"></i> Caixa</li>
											<li><a href="<?php echo Url::to(['/caixa-estacao'])?>"style="color: #fff">Caixa <em>(Estações de Trabalho)</em></a></li>
											<li class="divider"></li>
										</ul>
									</div>

									<div class="col-md-4">
										<ul>
											<li class="dropdown-header" style="color: #fff"><i class="fa fa-money"></i> Financeiro</li>
											<li><a href="<?php echo Url::to(['/financeiro/banco'])?>"style="color: #fff">Banco</a></li>
											<li><a href="<?php echo Url::to(['/financeiro/agencia'])?>"style="color: #fff">Agência</a></li>
											<li><a href="<?php echo Url::to(['/financeiro/conta'])?>"style="color: #fff">Conta</a></li>
											<li><a href="<?php echo Url::to(['/financeiro/carteira'])?>"style="color: #fff">Carteira</a></li>
											<li class="divider"></li>
											<!--							<li><a href="--><?php //echo Url::to(['/administradora-cartao-credito'])?><!--">Adm. Cartão de Crédito</a></li>-->
											<li><a href="<?php echo Url::to(['/financeiro/forma-pagamento'])?>"style="color: #fff">Forma de Pagamento</a></li>
											<li><a href="<?php echo Url::to(['/condicao-pagamento'])?>"style="color: #fff">Condição de Pagamento</a></li>
											<!--							<li><a href="--><?php //echo Url::to(['/condicao-pagamento'])?><!--">Condição de Pagamento</a></li>-->
											<li class="divider"></li>
											<li><a href="<?php echo Url::to(['/financeiro/centro-custo'])?>"style="color: #fff">Centro de Custo</a></li>
											<li><a href="<?php echo Url::to(['/financeiro/conta-orcamentaria'])?>"style="color: #fff">Conta Orçamentária</a></li>
											<li><a href="<?php echo Url::to(['/financeiro/historico-movimento-financeiro'])?>"style="color: #fff">Histórico Mov. Financeiro</a></li>
											<!--							<li><a href="--><?php //echo Url::to(['/plano-contas/create'])?><!--">Previsão Orçamentária</a></li>-->
											<!--							<li><a href="--><?php //echo Url::to(['/ocorrencia-cnab'])?><!--">Ocorrência CNAB</a></li>-->
											<li><a href="<?php echo Url::to(['/administradora-cartao-credito'])?>"style="color: #fff">Adm. Cartão de Crédito</a></li>
											<li class="divider"></li>
										</ul>
									</div>

									<div class="col-md-4">
										<ul>
											<li class="dropdown-header" style="color: #fff"><i class="fa fa-building-o"></i> Empresa</li>
											<li><a href="<?php echo Url::to(['/empresa-grupo'])?>"style="color: #fff">Grupo de Empresa</a></li>
											<li><a href="<?php echo Url::to(['/cargo'])?>"style="color: #fff">Cargo</a></li>
											<li><a href="<?php echo Url::to(['/setor'])?>"style="color: #fff">Setor</a></li>
											<li class="divider"></li>
											<li class="dropdown-header top15" style="color: #fff"><i class="fa fa-briefcase"></i> Fiscal</li>
											<li><a href="<?php echo Url::to(['/aliquota-reducao-icms'])?>"style="color: #fff">Aliquota de Redução do ICMS</a></li>
											<li><a href="<?php echo Url::to(['/tipo-tributacao-fiscal'])?>"style="color: #fff">Tipo de Tributação Fiscal</a></li>
											<li><a href="<?php echo Url::to(['/situacao-tributaria'])?>"style="color: #fff">Situação Tributária</a></li>
											<li class="divider"></li>
											<li class="dropdown-header top15" style="color: #fff"><i class="fa fa-gears"></i> Gerais</li>
											<li><a href="<?php echo Url::to(['/estado-federacao'])?>" style="color: #fff">Estado</a></li>
											<li><a href="<?php echo Url::to(['/cidade'])?>" style="color: #fff">Cidade</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</li>
				</ul>
		</nav>
		<!-- /#sidebar-wrapper -->

		<!-- Page Content -->
		<div id="page-content-wrapper">
				<button id="btnSide" type="button" class="hamburger is-closed" data-toggle="offcanvas" style = "margin-top : -10px;" >
						<span class="hamb-top"></span>
			<span class="hamb-middle"></span>
		<span class="hamb-bottom"></span>
				</button>
		</div>
		<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->
