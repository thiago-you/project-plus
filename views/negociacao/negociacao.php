<?php
use app\base\Helper;
use kartik\helpers\Html;
use app\models\Negociacao;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\NegociacaoParcela;
?>
<input id="negociacao-id" class="hidden" value="<?= $negociacao->isNewRecord ? null : $negociacao->id; ?>"/>
<input id="negociacao-contrato" class="hidden" value="<?= $negociacao->id_contrato; ?>"/>
<input id="negociacao-credor" class="hidden" value="<?= $negociacao->id_credor; ?>"/>
<input id="negociacao-campanha" class="hidden" value="<?= $negociacao->id_campanha; ?>"/>
<!-- ./hidden values da negociacao -->
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-negociacao" data-toggle="tab">Negociação</a></li>
		<li><a href="#tab-calculo" data-toggle="tab">Cálculo</a></li>
	</ul>
</div>
<!-- ./tabs -->
<div class="tab-content">
    <div class="tab-pane active" id="tab-negociacao">
    	<?php if ($negociacao->isNewRecord): ?>
    		<div class="row">
    			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 text-center">
    				<br><br><br>
    				<h2><i class="fa fa-briefcase "></i>&nbsp; Nenhuma Negociação em Andamento</h2>
    				<br><br><br>
    			</div>
    		</div>
    	<?php else: ?>
        	<div class="row">
            	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 text-center">
            		<?= Html::label('Situação'); ?>
            		<br>
            		<?php if ($negociacao->status == Negociacao::STATUS_FECHADA) {
                            echo '<span class="negociacao-status label label-emerald">Negociação Fechada</span>';
                		} else {
                		    echo '<span class="negociacao-status label label-info">Em Negociação</span>';
                		}
            		?>
            	</div>
            	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 text-center pull-right">
            		<?= Html::label('Forma de Pagamento'); ?>
            		<br>
            		<?php if ($negociacao->tipo == Negociacao::A_VISTA) {
                            echo '<span class="negociacao-status label label-emerald">A Vista</span>';
                		} else {
                		    echo '<span class="negociacao-status label label-info">Parcelado</span>';
                		}
            		?>
            	</div>
        	</div>
        	<!-- ./row -->
        	<br><br>
        	<div class="row">
    			<div class="col-totais col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-lg-10 col-lg-offset-1 col-xs-12">
            		<div class="row">
                		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
            				<b>Subtotal:</b>&nbsp; <?= Helper::mask($negociacao->subtotal, Helper::MASK_MONEY); ?>			
                		</div>
                		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
                			<b>Desconto:</b>&nbsp; <?= Helper::mask($negociacao->desconto, Helper::MASK_MONEY); ?>
                		</div>
                		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
                			<b>Honorários:</b>&nbsp; <?= Helper::mask($negociacao->receita, Helper::MASK_MONEY); ?>
                		</div>
                		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
                			<b>Total:</b>&nbsp; <?= Helper::mask($negociacao->total, Helper::MASK_MONEY); ?>
                		</div>
                    	<!-- ./totais -->
                	</div>
                	<!-- ./row -->
            	</div>
        	</div>
        	<!-- ./row -->
        	<?php if($negociacao->tipo == Negociacao::PARCELADO): ?>
        		<div class="row">
        			<div class="col-totais col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-lg-10 col-lg-offset-1 col-xs-12">
        				<br><hr><br>
        			</div>
    			</div>
    			<!-- ./row -->
        		<div class="row">
					<div class="col-md-4 col-md-offset-1 col-sm-4 col-sm-offset-1 col-lg-4 col-lg-offset-1 col-xs-12">
						<p><b>Valor de Entrada:</b> <?= Helper::mask($negociacao->valor_entrada, Helper::MASK_MONEY); ?></p>
    					<p><b>Taxa:</b> 15,00% am</p>
    					<p><b>Valor da Taxa:</b> <?= Helper::mask($negociacao->taxa_parcelado, Helper::MASK_MONEY); ?></p>
    				</div>
        			<div class="negociacao-view-parcelas-table col-md-6 col-sm-6 col-lg-6 col-xs-12">
            			<table class="table table-stripped table-bordered">
            				<thead>
            					<tr>
            						<th class="text-center">Núm.</th>
            						<th>Vencimento</th>
            						<th>Valor</th>
            						<th class="text-center">Status</th>
            						<td></td>
            					</tr>
            				</thead>
            				<!-- ./thead -->
            				<tbody>
            					<?php if($negociacao->parcelas): ?>
            						<?php foreach($negociacao->parcelas as $negociacaoParcela): ?>
            							<tr>
            								<td class="text-center"><?= $negociacaoParcela->num_parcela; ?></td>
            								<td><?= Helper::formatDateToDisplay($negociacaoParcela->data_vencimento, Helper::DATE_DEFAULT); ?></td>
            								<td><?= Helper::mask($negociacaoParcela->valor, Helper::MASK_MONEY); ?></td>
        									<td>
            									<?php if($negociacaoParcela->status == NegociacaoParcela::STATUS_ABERTA): ?>
            										<span class="label label-warning">ABERTA</span>
            									<?php else: ?>
            										<span class="label label-emerald">FATURADA</span>
            									<?php endif; ?>
            								</td>
            								<td class="text-center">
            									<?= Html::button('<i class="fa fa-dollar-sign"></i>', [
                                                        'class' => Helper::BTN_COLOR_INFO.' faturar-parcela btn-sm',
    									                'title' => $negociacaoParcela->status == NegociacaoParcela::STATUS_ABERTA ? 'Faturar parcela' : 'Estornar parcela',
    									                'data-id' => $negociacaoParcela->id,
    									                'data-status' => $negociacaoParcela->status,
    									                'disabled' => $negociacao->status == Negociacao::STATUS_ABERTA ? true : false,
                                                    ]);
            									?>
        									</td>
            							</tr>
            						<?php endforeach; ?>
            					<?php endif; ?>
            				</tbody>
            				<!-- ./tbody -->
            			</table>
                		<!-- ./table das parcelas -->
        			</div>
        		</div>
        	<?php endif; ?>
        	<!-- ./parcelas -->
        	<div class="row">
        		<div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-lg-10 col-lg-offset-1 col-xs-12">
        			<div class="box">
        				<div class="box-heading">
							<b>Observação:</b>
        				</div>
        				<div class="box-content">
            				<p><?= $negociacao->observacao; ?></p>
        				</div>
        			</div>
        		</div>
        	</div>
        	<!-- ./row -->
        	<br><br><br>
        	<div class="row">
        		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">	
            		<?= Html::button('<i class="fa fa-briefcase"></i>&nbsp; '.($negociacao->status == Negociacao::STATUS_FECHADA ? 'Abrir Negociação' : 'Fechar Negociação'), [
                            'class' => Helper::BTN_COLOR_PRIMARY.' btn-block',
                            'id' => 'alterar-negociacao',
    		                'disabled' => $negociacao->status == Negociacao::STATUS_FATURADA ? true : false,
                		]);
            		?>
            	</div>
            	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 pull-right">	
            		<?= Html::button('<i class="fa fa-hand-holding-usd"></i>&nbsp; ' .($negociacao->status == Negociacao::STATUS_FECHADA ? 'Faturar Contrato' : 'Estornar Contrato'), [
    		                'id' => 'faturar-contrato',
    		                'data-status' => $negociacao->status,
                            'class' => Helper::BTN_COLOR_EMERALD.' btn-block',
    		                'disabled' => $negociacao->status == Negociacao::STATUS_ABERTA ? true : false,
                		]);
            		?>
            	</div> 
        	</div>
        	<!-- ./row -->
		<?php endif; ?>
    </div>
    <!-- ./tab negociacao -->
	<div class="tab-pane" id="tab-calculo">
        <div class="row">
        	<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                <div class="row">
                	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                		<?= Html::label('Data da Negociação', 'negociacao-data'); ?>
                		<?= DatePicker::widget([
                                'name' => 'Negociacao[data]',
                                'id' => 'negociacao-data',
        		                'value' => $negociacao->data_negociacao ? $negociacao->data_negociacao : date('Y-m-d'),
                                'removeButton' => false,
        		                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy'
                                ],
                		   ]); 
                		?>
                	</div>
                	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                		<?= Html::label('Pagamento', 'negociacao-tipo'); ?>
                		<?= Select2::widget([
                			    'name' => 'Negociacao[tipo]',
                			    'id' => 'negociacao-tipo',
        		                'value' => $negociacao->tipo,
                			    'hideSearch' => true,
        		                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
                			    'data' => [
                                    Negociacao::A_VISTA => 'À Vista', 
                                    Negociacao::PARCELADO => 'Parcela'
                			    ]
                            ]);
                		?>
                	</div>
                	<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12 pull-right text-center">
                		<?= Html::label('Situação'); ?>
                		<br>
                		<?php 
                    		if ($negociacao->isNewRecord) {
                    		    echo '<span class="negociacao-status label label-warning">Sem Negociação</span>';
                    		} elseif ($negociacao->status == Negociacao::STATUS_FECHADA) {
                    		    echo '<span class="negociacao-status label label-emerald">Negociação Fechada</span>';
                    		} else {
                    		    echo '<span class="negociacao-status label label-info">Em Negociação</span>';
                    		}
                		?>
                	</div>
                </div>
            </div>
        </div>
        <!-- ./row -->
        <br>
        <div class="row">
        	<div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
        		<div class="row">
        			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        				<div class="negociacao-a-vista" style="<?= $negociacao->tipo == Negociacao::A_VISTA ? '' : 'display: none;'; ?>">
                    		<table class="table table-stripped table-bordered table-parcela">
                    			<thead>
                    				<tr>
                    					<th colspan="8">A Vista</th>
                    				</tr>
                    				<tr>
                    					<th class="text-center">Núm.</th>
                    					<th>Vencimento</th>
                    					<th>Atraso</th>
                    					<th>Valor</th>
                    					<th>Multa</th>
                    					<th>Juros</th>
                    					<th>Honorário</th>
                    					<th>Total</th>
                    				</tr>
                    			</thead>
                    			<!-- ./thead -->
                    			<tbody>
                    				<?php if (!empty($contrato->contratoParcelas) && is_array($contrato->contratoParcelas)): ?>
                    					<?php 
                        					$descEncargosMax = '100.0000';
                        					$descHonorariosMax = '100.0000';
                        					$descPrincipalMax = '100.0000';
                    					?>
                    					<?php foreach ($contrato->contratoParcelas as $parcela): ?>
                							<?php $parcela->calcularValores($negociacao->id_campanha); ?>
                							<!-- ./calcula os valores totais -->
                    						<tr class="contrato-parcela">
                            					<td class="text-center"><?= $parcela->num_parcela; ?></td>
                            					<td><?= Helper::formatDateToDisplay($parcela->data_vencimento, Helper::DATE_DEFAULT); ?></td>
                            					<td><?= $parcela->atraso; ?></td>
                            					<td class="principal" data-value="<?= $parcela->valor; ?>"><?= Helper::mask($parcela->valor, Helper::MASK_MONEY); ?></td>
                            					<td class="multa" data-value="<?= $parcela->multa; ?>"><?= Helper::mask($parcela->multa, Helper::MASK_MONEY); ?></td>
                            					<td class="juros" data-value="<?= $parcela->juros; ?>"><?= Helper::mask($parcela->juros, Helper::MASK_MONEY); ?></td>
                            					<td class="honorario" data-value="<?= $parcela->honorariosCalculo; ?>"><?= Helper::mask($parcela->honorarios, Helper::MASK_MONEY); ?></td>
                            					<td class="total"><?= Helper::mask($parcela->total, Helper::MASK_MONEY); ?></td>
                            					<?php 
                            					    // soma os valores da parcela
                                                    $valor += $parcela->valor;
                                                    $atraso += $parcela->atraso;
                                                    $multa += $parcela->multa;
                                                    $juros += $parcela->juros;
                                                    $honorarios += $parcela->honorarios;
                                                    $total += $parcela->total;
                                                    $taxa = $parcela->honorariosCalculo;
                                                    
                                                    // seta o menor desconto máximo como limite
                                                    if ($descEncargosMax > $parcela->faixaCalculo->desc_encargos_max && $parcela->faixaCalculo->desc_encargos_max > 0) {                                                    
                                                        $descEncargosMax = $parcela->faixaCalculo->desc_encargos_max;
                                                    }
                                                    if ($descHonorariosMax > $parcela->faixaCalculo->desc_honorario_max && $parcela->faixaCalculo->desc_honorario_max > 0) {                                                    
                                                        $descHonorariosMax = $parcela->faixaCalculo->desc_honorario_max;
                                                    }
                                                    if ($descPrincipalMax > $parcela->faixaCalculo->desc_principal_max && $parcela->faixaCalculo->desc_principal_max > 0) {                                                    
                                                        $descPrincipalMax = $parcela->faixaCalculo->desc_principal_max;
                                                    }
                            					?>
                            				</tr>
                    					<?php endforeach; ?>
                    					<!-- ./dados da parcela -->
                    					<tr>
                    						<td colspan="7"></td>
                    					</tr>
                    					<tr>
                        					<td colspan="2" class="text-center"><b>Totais:</b></td>
                        					<td><b><?= $atraso; ?></b></td>
                        					<td class="parcelas-total-principal" data-value="<?= $valor; ?>"><b><?= Helper::mask($valor, Helper::MASK_MONEY); ?></b></td>
                        					<td class="parcelas-total-multa" data-value="<?= $multa; ?>"><b><?= Helper::mask($multa, Helper::MASK_MONEY); ?></b></td>
                        					<td class="parcelas-total-juros" data-value="<?= $juros; ?>"><b><?= Helper::mask($juros, Helper::MASK_MONEY); ?></b></td>
                        					<td class="parcelas-total-honorarios" data-value="<?= $honorarios; ?>" data-taxa="<?= $taxa; ?>"><b><?= Helper::mask($honorarios, Helper::MASK_MONEY); ?></b></td>
                        					<td class="parcelas-total-total" data-value="<?= $total; ?>"><b><?= Helper::mask($total, Helper::MASK_MONEY); ?></b></td>
                        				</tr>
                        				<!-- ./totais -->
                        				<tr class="hidden">
                        					<td>
                        						<input id="desc-encargos-max" value="<?= $descEncargosMax; ?>"/>
                        						<input id="desc-honorario-max" value="<?= $descHonorariosMax; ?>"/>
                        						<input id="desc-principal-max" value="<?= $descPrincipalMax; ?>"/>
                        					</td>
                        				</tr>
                    				<?php endif; ?>
                    			</tbody>
                    			<!-- ./tbody -->
                    		</table>
                		</div>
                		<!-- ./negociacao a vista -->
                		<div class="panel panel-default negociacao-parcelado" style="<?= $negociacao->tipo == Negociacao::PARCELADO ? '' : 'display: none;'; ?>">
                			<div class="panel-heading">
                				Parcelado
                			</div>
                			<!-- ./panel-heading -->
                			<div class="panel-body">
                				<div class="row">
                					<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                						<div class="row">
                							<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        										<?= Html::label('Valor de Entrada', 'valor-entrada'); ?>
                                				<?= MaskMoney::widget([
                                    				    'name' => 'valor-entrada',    
                        				                'value' => $negociacao->valor_entrada,
                        				                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
                        				                'options' => [
                        	                                'id' => 'valor-entrada',
                        	                                'maxlength' => 16,
                        	                                'class' => 'form-control input-sm',
                        				                ],
                        				                'pluginOptions' => [
                        	                                'prefix' => 'R$ ',
                        	                                'precision' => 2,
                        				                ],
                                    				]); 
                                				?>      
                            				</div>
                            				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                                				<br>
                            					<p><b>Taxa:</b> 15,00% am</p>
                            					<p><b>Valor da Taxa:</b> <span id="parcelado-valor-taxa" data-value="<?= $negociacao->taxa_parcelado; ?>"><?= Helper::mask($negociacao->taxa_parcelado, Helper::MASK_MONEY); ?></span></p>
                            				</div>
                        				</div>
                        				<!-- ./row -->       					
            						</div>
                					<div class="col-md-8 col-sm-8 col-lg-8s col-xs-12">
                						<?= Html::label('Quantidade de Parcelas', 'quant-parcelas'); ?>
                        				<?= Select2::widget([
                                			    'name' => 'quant-parcelas',
                                			    'id' => 'quant-parcelas',
                        		                'value' => count($negociacao->parcelas),
                                			    'hideSearch' => true,
                        		                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
                                			    'data' => Negociacao::getQuantidadeParcelas(),
                				                'pluginOptions' => [
                                                    'allowClear' => true,
                				                ],
                				                'options' => [
    				                                'placeholder' => 'Selecione ...',
                				                ],
                                            ]);
                                		?>
                                		<!-- ./quantidade de parcelas -->
                                		<div id="negociacao-lista-parcelas">
                                			<table class="table table-stripped table-bordered">
                                				<thead>
                                					<tr>
                                						<th class="text-center">Núm.</th>
                                						<th>Vencimento</th>
                                						<th>Valor</th>
                                					</tr>
                                				</thead>
                                				<!-- ./thead -->
                                				<tbody id="tbody-lista-parcelas">
                                					<?php if($negociacao->parcelas): ?>
                                						<?php foreach($negociacao->parcelas as $negociacaoParcela): ?>
                                							<tr>
                                								<td class="text-center"><?= $negociacaoParcela->num_parcela; ?></td>
                                								<td><?= Helper::formatDateToDisplay($negociacaoParcela->data_vencimento, Helper::DATE_DEFAULT); ?></td>
                                								<td><?= Helper::mask($negociacaoParcela->valor, Helper::MASK_MONEY); ?></td>
                                							</tr>
                                						<?php endforeach; ?>
                                					<?php endif; ?>
                                				</tbody>
                                				<!-- ./tbody -->
                                			</table>
                                		</div>
                                		<!-- ./lista das parcelas -->
                					</div>
                				</div>
                				<!-- ./row -->
                			</div>
                			<!-- ./panel-body -->
                		</div>
                		<!-- ./negociacao parcelada -->
            		</div>
        		</div>
        		<!-- ./table -->
        		<br>
        		<div class="row">
            		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
        				<b>Subtotal:</b> <span id="negociacao-subtotal" data-value="<?= $negociacao->subtotal; ?>"><?= Helper::mask($negociacao->subtotal, Helper::MASK_MONEY); ?></span>			
            		</div>
            		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
            			<b>Desconto:</b> <span id="negociacao-desconto" data-value="<?= $negociacao->desconto; ?>"><?= Helper::mask($negociacao->desconto, Helper::MASK_MONEY); ?></span>
            		</div>
            		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
            			<b>Honorários:</b> <span id="negociacao-receita" data-value="<?= $negociacao->receita; ?>"><?= Helper::mask($negociacao->receita, Helper::MASK_MONEY); ?></span>
            		</div>
            		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
            			<b>Total:</b> <span id="negociacao-total" data-value="<?= $negociacao->total; ?>"><?= Helper::mask($negociacao->total, Helper::MASK_MONEY); ?></span>
            		</div>
                	<!-- ./totais -->
            	</div>
            	<!-- ./row -->
        	</div>
        	<!-- ./tabela das parcelas -->
        	<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
        		<div class="panel panel-default panel-valores">
        			<div class="panel-heading">
        				Descontos
        			</div>
        			<!-- ./panel heading -->
        			<div class="panel-body">
        				<div class="row">
        					<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
        						<?= Html::label('Encargos', 'desconto_encargos'); ?>
                				<?= MaskMoney::widget([
                    				    'name' => 'Negociacao[desconto_encargos]',    
        				                'value' => $negociacao->desconto_encargos,
        				                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
        				                'options' => [
        	                                'id' => 'desconto_encargos',
        	                                'maxlength' => 9,
        	                                'class' => 'form-control input-sm negociacao-descontos',
        				                ],
        				                'pluginOptions' => [
        	                                'suffix' => '%',
        	                                'precision' => 4,
        				                ],
                    				]); 
                				?>
            				</div>
            				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            					<?= Html::label('Principal', 'desconto_principal'); ?>
                				<?= MaskMoney::widget([
                    				    'name' => 'Negociacao[desconto_principal]',
        			                    'value' => $negociacao->desconto_principal,
        				                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
        				                'options' => [
        	                                'id' => 'desconto_principal',
        	                                'maxlength' => 9,
        	                                'class' => 'form-control input-sm negociacao-descontos',
        				                ],
        				                'pluginOptions' => [
        	                                'suffix' => '%',
        	                                'precision' => 4,
        				                ],
                    				]); 
                				?>
            				</div>
            				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            					<?= Html::label('Honorários', 'desconto_honorarios'); ?>
                				<?= MaskMoney::widget([
                    				    'name' => 'Negociacao[desconto_honorarios]',   
        				                'value' => $negociacao->desconto_honorarios,
        				                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
        				                'options' => [
        	                                'id' => 'desconto_honorarios',
        	                                'maxlength' => 9,
        	                                'class' => 'form-control input-sm negociacao-descontos',
        				                ],
        				                'pluginOptions' => [
        	                                'suffix' => '%',
        	                                'precision' => 4,
        				                ],
                    				]); 
                				?>
            				</div>
            				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 hidden">
            					<?= Html::label('Total', 'desconto_total'); ?>
                				<?= MaskMoney::widget([
                                        'name' => 'Negociacao[desconto_total]',
        				                'value' => $negociacao->desconto_total,
        				                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
        				                'options' => [
        	                                'id' => 'desconto_total',
        	                                'maxlength' => 14,
        	                                'class' => 'form-control input-sm negociacao-descontos',  
        				                ],
        				                'pluginOptions' => [
        	                                'prefix' => 'R$ ',
        	                                'precision' => 2,
        				                ],
                    				]); 
                				?>
            				</div>
        				</div>
        			</div>
        			<!-- ./panel body -->
        		</div>
        	</div>
        	<!-- ./tabela de calculo -->
        	<div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
        		<br>
        		<?= Html::label('Observação:', 'negociacao-observacao'); ?>
        		<?= Html::textarea('Negociacao[Observacao]', str_replace(['<br/>', '<br />', '<br>'], "\n", $negociacao->observacao), [
            		    'id' => 'negociacao-observacao',     
                        'class' => 'form-control',
	                    'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
            		]); 
        		?>
        	</div>
        </div>
        <!-- ./row -->
        <br><br>
        <div class="row">
        	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">	
        		<?= Html::button('<i class="fa fa-save"></i>&nbsp; Salvar a Negociação', [
                        'class' => Helper::BTN_COLOR_EMERALD.' btn-block',
                        'id' => 'salvar-negociacao',
		                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
            		]);
        		?>
        	</div>
        	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 pull-right">	
        		<?= Html::button(helper::BTN_CANCEL, [
		                'id' => 'cancelar-alteracao',
                        'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
		                'disabled' => $negociacao->status == Negociacao::STATUS_FECHADA,
            		]);
        		?>
        	</div> 
        </div>
        <!-- ./row -->
    </div>
    <!-- ./tab calculo -->
</div>
<!-- ./tab content -->





