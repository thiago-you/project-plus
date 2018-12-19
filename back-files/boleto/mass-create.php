<?php
use app\base\Util;
use yii\helpers\Html;
use app\models\Boleto;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\financeiro\models\Conta;
use app\modules\financeiro\models\Receita;
use app\modules\financeiro\models\ReceitaParcela;

$this->title = 'Gerar Boletos Receita: '.$model->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-default">
	<div class="box-body">
		<div class="row">
			<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
				<table class="table table-condensed">
					<tbody>
            			<tr>
            				<td class="info" width="40%"><b class="text-info">Nº Documento: </b></td>
            				<td class="danger"><?= $model->numero_documento; ?></td>
            			</tr>    			
            			<tr>
            				<td class="info" width="40%"><b class="text-info">Nº Parcelas: </b></td>
            				<td class="warning"><?= $model->num_parcelas; ?></td>
            			</tr>
            			<tr>	
            				<td class="info" width="40%"><b class="text-info">Valor: </b></td>
            				<td class="danger"><?= \Yii::$app->formatter->asCurrency($model->valor, 'R$ '); ?></td>
            			</tr>
            			<tr>
            				<td class="info" width="40%"><b class="text-info">Centro de Custo: </b></td>
            				<td class="warning"><?= $model->centroCusto->descricao; ?></td>
            			</tr>
            			<tr>	
            				<td class="info" width="40%"><b class="text-info">Conta Orçamentária: </b></td>
            				<td class="danger"><?= $model->contaOrcamentaria->nome_conta; ?></td>
            			</tr>
            			<tr>
            				<td class="info" width="40%"><b class="text-info">Criada Em: </b></td>
            				<td class="warning"><?= date('d/m/Y H:i:s', $model->created_at); ?></td>
            			</tr>
            			<tr>	
            				<td class="info" width="40%"><b class="text-info">Alterada Em: </b></td>
            				<td class="danger"><?= date('d/m/Y H:i:s', $model->updated_at); ?></td>	
            			</tr>
        			</tbody>
        		</table>
        		<!-- ./table -->
			</div>
			<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
				<?php $form = ActiveForm::begin(['enableAjaxValidation'=>false, 'enableClientValidation' => true, 'validateOnSubmit' => true]); ?>
        			<?php $gridColumns = [
                	       [
                    	       'class' => '\kartik\grid\CheckboxColumn',
                    	       'name'  => 'parcelas',
                    	       'width' => '5%',
                    	       'options' => ['id' => 'selected-all'],
                    	       'checkboxOptions' => function($model) {
                    	           if (Boleto::findOne(['receita_parcela_id' => $model->id]) || 
                    	               $model->status != ReceitaParcela::SITUACAO_PAGAMENTO_ABERTO
                	               ) {
                        	           return ['disabled' => true, 'readonly' => true];
                        	       }
                    	       }
                	       ],
                	       [
                	           'attribute' => '',
                	           'hAlign' => GridView::ALIGN_CENTER,
                	           'header' => '<span class="text-primary">Possui Boleto?</span>',
                	           'format' => 'html',
                	           'width' => '15%',
                	           'value' => function($model) {
                	               if (Boleto::findOne(['receita_parcela_id' => $model->id])) {
                	                   return Html::tag('small','SIM', ['class'=>'label label-success']);
                	               } else {
                	                   return  Html::tag('small','NÃO', ['class'=>'label label-default']);
                	               }
                	           }
                	       ],    
                	       [
                	           'attribute' => 'parcela_num',
                	           'hAlign' => GridView::ALIGN_CENTER,
                	           'width' => '5%',
                	           'label' => 'N° Parcela',
                	       ],
                	       [
                	           'attribute' => 'valor',
                	           'hAlign' => GridView::ALIGN_CENTER,
                	           'width' => '15%',
                	           'format' => 'currency'
                	       ],
                	       [
                    	       'attribute' => 'vencimento',
                	           'hAlign' => GridView::ALIGN_CENTER,
                    	       'width' => '10%',
                	           'value' => function($model) {
                	               if (DateTime::createFromFormat('Y-m-d', $model->vencimento)) {
                	                   return DateTime::createFromFormat('Y-m-d', $model->vencimento)->format('d/m/Y');
                	               } else {
                	                   return $model->vencimento;
                	               }
                	           }
                	       ],
                	       [
                	           'attribute' => 'status',
                	           'width' => '15%', 
                	           'hAlign' => GridView::ALIGN_CENTER,
                	           'format' => 'html',
                	           'value' => function($model) {
                	               if ($model->status == Receita::STATUS_QUITADA) {
                	                   return Html::tag('small','Quitado', ['class' => 'label label-success', 'style' => 'width: 100%']);
                	               } elseif ($model->status == Receita::STATUS_EMABERTO) {
                	                   return  Html::tag('small','Aberto', ['class' => 'label label-warning', 'style' => 'width: 100%']);
                	               } elseif ($model->status == Receita::STATUS_PARCIAL) {
                	                   return Html::tag('small','Parcial', ['class' => 'label label-default', 'style' => 'width: 100%']);
                	               }
                	           }
                	       ],
                	   ];
                	   
        			   // grid
                	   echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => $gridColumns,
                            'containerOptions' => ['style' => 'overflow: auto'],
                            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                            'pjax' => false,
                            'toolbar' => false,
                            'bordered' => true,
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'hover' => true,
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<i class="fa fa-list"></i>&nbsp; Parcelas',
                                'footer' => '<div class="row">
                                    <div class="col-md-7 col-sm-7 col-lg-7 col-xs-12">'.
                                          Select2::widget([
                            	               'name' => 'conta_id',
                                               'id' => 'conta_id',
                                               'options' => ['required' => true],
                                               'hideSearch' => true,
                                               'data' => ArrayHelper::map(Conta::find()->habilitada()->all(), 'id', 'nome')
    	                                   ]).'
                                    </div>
                                    <div class="hidden-md hidden-sm hidden-lg"><hr></div>
                                    <div class="col-md-5 col-sm-5 col-lg-5 col-xs-12">'.
                                          Html::submitButton('<i class="fa fa-cogs"></i>&nbsp; GERAR BOLETOS', [
                                                'class' => Util::BTN_COLOR_SUCCESS,
                                                'style' => 'width: 100%; height: 100%'
                                           ]).
                                    '</div>
                                 </div>'
                            ],                            
                        ]);
                	?>		
            	<?php ActiveForm::end(); ?>		
            	<!-- ./form -->
			</div>
		</div>
		<!-- ./row -->
	</div>	
	<!-- ./box-body -->
	<div class="box-footer">
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-12 col-lg-4 pull-right">
				<?= Html::a(Util::BTN_RETURN, ['../../financeiro/receita/index'], ['class' => Util::BTN_COLOR_DEFAULT.' btn-block']); ?>
			</div>
		</div>
	</div>	
	<!-- ./box-footer -->
</div>
<!-- ./box -->


