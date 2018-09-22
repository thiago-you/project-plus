<?php
use app\base\Helper;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use kartik\helpers\Html;
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
                        'removeButton' => false,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
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
        			    'options' => ['placeholder' => 'Selecione'],
        			    'data' => [
                            0 => 'À Vista', 
                            1 => 'Parcela'
        			    ]
                    ]);
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
        					<th colspan="9">Parcelas</th>
        				</tr>
        				<tr>
        					<th>Contrato</th>
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
        				
        			</tbody>
        			<!-- ./tbody -->
        		</table>
        		<!-- ./table -->
    		</div>
    		<!-- ./table -->
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
				<b>Subtotal:</b> R$ 0,00    			
    		</div>
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
    			<b>Desconto:</b> R$ 0,00
    		</div>
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
    			<b>Honorários:</b> R$ 0,00
    		</div>
    		<div class="col-md-3 col-sm-3 col-lg-3 col-xs-6">
    			<b>Total:</b> R$ 0,00
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






