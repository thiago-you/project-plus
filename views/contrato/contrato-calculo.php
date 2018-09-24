<?php
use app\base\Helper;
use kartik\helpers\Html;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use kartik\date\DatePicker;
?>
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
        		<?= Html::label('Pagamento', 'negociacao-pagamento'); ?>
        		<?= Select2::widget([
        			    'name' => 'Negociacao[pagamento]',
        			    'id' => 'negociacao-pagamento',
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
                        echo '<span class="label label-warning">Sem Negociação</span>';
            		} else {
            		    echo '<span class="label label-success">Em Negociação</span>';
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
        					<th>Núm.</th>
        					<th>Vencimento</th>
        					<th>Valor</th>
        					<th>Atraso</th>
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
        						<tr>
        							<?php 
        							 /* @var $parcela app\models\ContratoParcela */
        							?>
                					<td><?= $parcela->num_parcela; ?></td>
                					<td><?= Helper::formatDateToDisplay($parcela->data_vencimento, Helper::DATE_DEFAULT); ?></td>
                					<td><?= Helper::mask($parcela->valor, Helper::MASK_MONEY); ?></td>
                					<td><?= $parcela->getAtraso(); ?></td>
                					<td><?= Helper::mask($parcela->multa, Helper::MASK_MONEY); ?></td>
                					<td><?= Helper::mask($parcela->juros, Helper::MASK_MONEY); ?></td>
                					<td><?= Helper::mask($parcela->honorarios, Helper::MASK_MONEY); ?></td>
                					<td><?= Helper::mask($parcela->total, Helper::MASK_MONEY); ?></td>
                				</tr>
            					<!-- ./dados da parcela -->
        					<?php endforeach; ?>
        				<?php endif; ?>
        			</tbody>
        			<!-- ./tbody -->
        		</table>
        		<!-- ./table -->
    		</div>
    		<!-- ./table -->
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
				<b>Subtotal:</b> <?= Helper::mask($negociacao->subtotal, Helper::MASK_MONEY); ?>			
    		</div>
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
    			<b>Desconto:</b> <?= Helper::mask($negociacao->desconto, Helper::MASK_MONEY); ?>
    		</div>
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
    			<b>Honorários:</b> <?= Helper::mask($negociacao->receita, Helper::MASK_MONEY); ?>
    		</div>
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
    			<b>Total:</b> <?= Helper::mask($negociacao->total, Helper::MASK_MONEY); ?>
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
				                'options' => [
	                                'id' => 'desconto_encargos',
	                                'maxlength' => 9,
	                                'class' => 'form-control input-sm',
				                ],
				                'pluginOptions' => [
	                                'suffix' => '%',
	                                'precision' => 4,
	                                'decimal' => ',',
				                ],
            				]); 
        				?>
    				</div>
    				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    					<?= Html::label('Principal', 'desconto_principal'); ?>
        				<?= MaskMoney::widget([
            				   'name' => 'Negociacao[desconto_principal]',
				                'options' => [
	                                'id' => 'desconto_principal',
	                                'maxlength' => 9,
	                                'class' => 'form-control input-sm',
				                ],
				                'pluginOptions' => [
	                                'suffix' => '%',
	                                'precision' => 4,
	                                'decimal' => ',',
				                ],
            				]); 
        				?>
    				</div>
    				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    					<?= Html::label('Honorários', 'desconto_honorarios'); ?>
        				<?= MaskMoney::widget([
            				   'name' => 'Negociacao[desconto_honorarios]',       
				                'options' => [
	                                'id' => 'desconto_honorarios',
	                                'maxlength' => 9,
	                                'class' => 'form-control input-sm',
				                ],
				                'pluginOptions' => [
	                                'suffix' => '%',
	                                'precision' => 4,
	                                'decimal' => ',',
				                ],
            				]); 
        				?>
    				</div>
    				<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
    					<?= Html::label('Total', 'desconto_total'); ?>
        				<?= MaskMoney::widget([
            				   'name' => 'Negociacao[desconto_total]',				                
				                'options' => [
	                                'id' => 'desconto_total',
	                                'maxlength' => 14,
	                                'class' => 'form-control input-sm',  
				                ],
				                'pluginOptions' => [
	                                'prefix' => 'R$ ',
	                                'precision' => 2,
	                                'decimal' => ',',
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
</div>
<!-- ./row -->
<div class="row">
	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">	
		<?= Html::button('<i class="fa fa-save"></i>&nbsp; Salvar a Negociação', [
                'class' => Helper::BTN_COLOR_EMERALD.' btn-block',
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





