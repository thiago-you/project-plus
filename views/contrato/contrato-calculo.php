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
	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
		<?= Html::label('Data do Cálculo', 'calculo-data'); ?>
		<?= DatePicker::widget([
                'name' => 'Calculo[data]',
                'id' => 'calculo-data',
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ],
		   ]); 
		?>
	</div>
	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
		<?= Html::label('Pagamento', 'calculo-pagamento'); ?>
		<?= Select2::widget([
			    'name' => 'Calculo[pagamento]',
			    'id' => 'calculo-pagamento',
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
<!-- ./row -->
<br>
<div class="row">
	<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
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
	<!-- ./tabela das parcelas -->
	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
		<div class="panel panel-default panel-valores">
			<div class="panel-heading">
				Descontos
			</div>
			<!-- ./panel heading -->
			<div class="panel-body">
				<br>
			</div>
			<!-- ./panel body -->
		</div>
	</div>
	<!-- ./tabela de calculo -->
</div>