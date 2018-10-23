<?php
use app\base\Util;
use app\models\Boleto;
?>
<div class="box">
	<div class="box-body">	
        <div class="row">
        	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
        		<table class="table">
        			<thead>
        				<tr>
        					<th class="info text-primary font16">
        						<i class="fa fa-user"></i>&nbsp; Cliente
        					</th>
        				</tr>
        			</thead>
        			<tbody>
                    	<tr class="danger">
                    		<td>
                        		<b>Nome:</b>&nbsp;	
                        		<small><?= $model->cliente->nome; ?></small>
                    		</td>
                    	</tr>
                    	<tr class="success">
                    		<td>
                    			<b><?= empty($model->cliente->cpf) ? 'CNPJ' : 'CPF'; ?>:</b>&nbsp;
                    			<small>
                    				<?= empty($model->cliente->cpf) 
                        				? Util::maskBackend($model->cliente->cnpj, Util::MASK_CNPJ) 
                        				: Util::maskBackend($model->cliente->cpf, Util::MASK_CPF); 
                    				?>     
                				</small>           		
                    		</td>
                    	</tr>
                    	<tr class="danger">
                    		<td>
                    			<b>E-mail:</b>&nbsp;
                				<small><?= $model->cliente->email; ?></small>
                    		</td>
                    	</tr>
                	</tbody>
                	<!-- ./tbody -->
                </table>
                <!-- ./table -->   
        	</div>
        	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
        		<table class="table">
        			<thead>
        				<tr>
        					<th class="info text-primary font16">
        						<i class="fa fa-university"></i>&nbsp; Conta
        					</th>
        				</tr>
        			</thead>
        			<tbody>
            			<tr class="danger">
                    		<td>
                        		<b>Nome:</b>&nbsp;
                        		<small><?= $model->conta->nome; ?></small>
                    		</td>
                    	</tr>
                    	<tr class="success">
                    		<td>
                    			<b>Banco:</b>&nbsp;
                        		<small>
                    				<?= "{$model->conta->banco->nome} - {$model->conta->banco->codigo_banco}"; ?>
                        		</small>
                    		</td>
                    	</tr>
                    	<tr class="danger">
                    		<td>
                    			<b>Agência / Conta:</b>&nbsp;
                    			<small>
                					<?= "{$model->conta->agencia->codigo_agencia}-{$model->conta->agencia->dv} / {$model->conta->conta}-{$model->conta->dv}"; ?>
                				</small>                		
                    		</td>
                    	</tr>
                	</tbody>
                	<!-- ./tbody -->            	
                </table> 
                <!-- ./table -->   
        	</div>
        </div>
        <!-- ./row -->
        <div class="row">
        	<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        		<fieldset>
        			<legend class="font16">
        				<b class="text-info"><i class="fa fa-info-circle"></i>&nbsp; Informações</b>
    				</legend>
    				<!-- ./legend do fieldset -->
        			<table class="table">
        				<tbody>
            				<tr>
            					<td>
            						<div class="row">
                        				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4">
                        					<b class="text-info">Valor:</b> <?= \Yii::$app->formatter->asCurrency($model->valor, 'R$ '); ?>
                        				</div>	
                        				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4">
                        					<b class="text-info">Emissão:</b> <?= $model->data_emissao; ?>
                        				</div>
                        				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4">
                        					<b class="text-info">Vencimento:</b> <?= $model->data_vencimento; ?>
                        				</div>
                        			</div>
            					</td>
            				</tr>
            				<tr>
            					<td>
            						<div class="row">
                        				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4">
                        					<b class="text-info">Nosso Número:</b> <?= $model->nosso_numero; ?>
                        				</div>	
                        				<div class="col-md-8 col-sm-8 col-lg-8 col-xs-8">
                        					<b class="text-info">Token:</b> <?= $model->token; ?>
                        				</div>
                        			</div>
                    			</td>
                			</tr>
            				<tr>
            					<td>
            						<div class="row">
                        				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-4">
                        					<b class="text-info">Documento:</b> <?= $model->documento; ?>
                        				</div>
                        				<div class="col-md-8 col-sm-8 col-lg-8 col-xs-8">
                        					<b class="text-info">Instrução:</b> <?= $model->instrucao; ?>
                        				</div>
                        			</div>
            					</td>
            				</tr>
                			<tr>
                				<td>
                        			<div class="row">
                        				<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 text-center">
                        					<b class="text-info">Situação: </b>
                        					<?php 
                        					    if ($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API) {
                        					        echo '<span style="width: 100%; display: block;" class="label label-warning">PENDENTE</span>';
                                                } elseif ($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_GERADO_API) {
                        					        echo '<span style="width: 100%; display: block;" class="label label-primary">EMITIDO</span>';
                                                } elseif ($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_DE_REMESSA) {
                        					        echo '<span style="width: 100%; display: block;" class="label label-info">EM REMESSA</span>';
                                                } elseif ($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_RETORNO) {
                        					        echo '<span style="width: 100%; display: block;" class="label label-emerald">ENVIADO</span>';
                                                }
                        					?>
                        				</div>
                        				<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 text-center">
                        					<b class="text-info">Pagamento: </b>
                        					<?php 
                                                switch($model->situacao_pagamento) {
                                                    case Boleto::SITUACAO_PAGAMENTO_LIQUIDADO:
                                                        echo '<span style="width: 100%; display: block;" class="label label-success">LIQUIDADO</span>';
                                                        break;
                                                    case Boleto::SITUACAO_PAGAMENTO_ATRASADO:
                                                        echo '<span style="width: 100%; display: block;" class="label label-danger">&nbsp; ATRASADO &nbsp;</span>';
                                                        break;
                                                    case Boleto::SITUACAO_PAGAMENTO_PROTESTADO:
                                                        echo '<span style="width: 100%; display: block;" class="label label-danger">PROTESTADO</span>';
                                                        break;
                                                    case Boleto::SITUACAO_PAGAMENTO_PARCIAL:
                                                        echo '<span style="width: 100%; display: block;" class="label label-info">&nbsp;&nbsp;&nbsp; PARCIAL &nbsp;&nbsp;&nbsp;</span>';
                                                        break;
                                                    case Boleto::SITUACAO_PAGAMENTO_DEVOLVIDO:
                                                        echo '<span style="width: 100%; display: block;" class="label label-primary">DEVOLVIDO</span>';
                                                        break;
                                                    default:
                                                        echo '<span style="width: 100%; display: block;" class="label label-warning">&nbsp; PENDENTE &nbsp;</span>';
                                                        break;
                                                }
                        					?>
                        				</div>
                        			</div>
            					</td>
            				</tr>
        				</tbody>
        				<!-- ./tbody -->
        			</table>
        			<!-- ./table -->
        		</fieldset>
        		<!-- ./fieldset -->
        	</div>
        </div>
        <!-- ./row -->
	</div>  
	<!-- ./box-body -->
</div>        
<!-- ./box -->

