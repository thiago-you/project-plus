<?php
use app\base\Helper;
use kartik\helpers\Html;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use kartik\date\DatePicker;
?>
<input id="negociacao-id" class="hidden" value="<?= $negociacao->isNewRecord ? null : $negociacao->id; ?>"/>
<input id="negociacao-contrato" class="hidden" value="<?= $negociacao->id_contrato; ?>"/>
<input id="negociacao-credor" class="hidden" value="<?= $negociacao->id_credor; ?>"/>
<input id="negociacao-campanha" class="hidden" value="<?= $negociacao->id_campanha; ?>"/>
<!-- ./hidden values da negociacao -->
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
        			    'hideSearch' => true,
        			    'data' => [
                            0 => 'À Vista', 
                            1 => 'Parcela'
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
        		<table class="table table-stripped table-bordered table-parcela">
        			<thead>
        				<tr>
        					<th colspan="8">Parcelas</th>
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
        				<?php endif; ?>
        			</tbody>
        			<!-- ./tbody -->
        		</table>
        		<!-- ./table -->
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
		<?= Html::label('Observação:', 'negociacao-observacao'); ?>
		<?= Html::textarea('Negociacao[Observacao]', '', [
    		    'id' => 'negociacao-observacao',                
                'class' => 'form-control',
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
    		]);
		?>
	</div>
	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 pull-right">	
		<?= Html::button(helper::BTN_CANCEL, [
                'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
    		]);
		?>
	</div> 
</div>
<!-- ./row -->





