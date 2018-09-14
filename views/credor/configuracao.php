<?php
use app\base\Helper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CredorCampanha;

$this->title = 'Configuração de Cálculo';
$this->params['breadcrumbs'][] = ['label' => 'Credores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary panel-box">
		<div class="panel-body">
      		<?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
        	<!-- ./hidden id_credor -->
      		<div class="row">
      			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                	<?= $form->field($model, 'id_campanha')->dropDownList(ArrayHelper::map($model->credorCampanhas, 'id', 'nome'), [
        	                'prompt' => 'Selecione a Campanha ...',
                    	]); 
                	?>
    			</div>
    			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <?= Html::label('Ações'); ?>
                    <div class="form-group">
                    	<?= Html::button('<i class="fa fa-eye fa-fw"></i>', [
            	                'id' => 'btn-view-campanha',
            	                'class' => Helper::BTN_COLOR_INFO.' campanha-action-button',
            	                'disabled' => $model->id_campanha ? false : true,
            	                'title' => 'Visualizar Campanha',
            	                'data-toggle' => 'tooltip',
                        	]); 
                    	?>
                    	<?= Html::button('<i class="fa fa-plus fa-fw"></i>', [
            	                'id' => 'btn-nova-campanha',
            	                'class' => Helper::BTN_COLOR_EMERALD,
            	                'title' => 'Cadastrar Nova Campanha',
            	                'data-toggle' => 'tooltip',
                        	]); 
                    	?>
                    	<?= Html::button('<i class="fa fa-edit fa-fw"></i>', [
            	                'id' => 'btn-editar-campanha',
            	                'class' => Helper::BTN_COLOR_WARNING.' campanha-action-button',
    	                       'disabled' => $model->id_campanha ? false : true,
            	                'title' => 'Alterar Campanha',
            	                'data-toggle' => 'tooltip',
                        	]); 
                    	?>
                    	<?= Html::button('<i class="fa fa-times fa-fw"></i>', [
            	                'id' => 'btn-deletar-campanha',
            	                'class' => Helper::BTN_COLOR_DANGER.' campanha-action-button',
            	                'disabled' => $model->id_campanha ? false : true,
            	                'title' => 'Excluír Campanha',
            	                'data-toggle' => 'tooltip',
                    	   ]);
                    	?>
                    </div>
    			</div>
    			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
    				<?= Html::label('Cálculo'); ?>
        			<?= Html::dropDownList('CredorCampanha[tipo]', null, [
                            CredorCampanha::CALCULO_A_VISTA => 'À Vista',
    		                CredorCampanha::CALCULO_PARCELADO => 'Parcelado',
        			    ], [
        	                'id' => 'credor-campanha-tipo',
                	        'class' => 'form-control', 
                        ]); 
                	?>
            	</div>
      		</div>
      		<!-- ./row -->
      		<div class="row">
      			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
      				<?= Html::button('<i class="fa fa-plus"></i>&nbsp; Nova Faixa', [
    		                'id' => 'nova-faixa', 
    		                'class' => Helper::BTN_COLOR_EMERALD.' btn-block campanha-action-button',
                            'disabled' => $model->id_campanha ? false : true,
      				   ]); 
      				?>
      			</div>
      		</div>
      		<div class="row">
      			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
      				<div id="lista-faixas">
						<?= $this->render('/credor-calculo/index', [
				                'model' => $model->id_campanha ? $model->credorCampanha->credorCalculos : [],        
                            ]); 
						?>
      				</div>
      				<!-- ./lista de faixas -->
      			</div>
      		</div>
      		<!-- ./row -->
        </div>
        <!-- ./painel-body -->
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; Salvar', [
                                'class' => Helper::BTN_COLOR_SUCCESS.' btn-block'
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/credor'], [
                                'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    		</div>
    		<!-- ./row -->
        </div>
        <!-- ./panel-footer -->
	</div>
	<!-- ./box -->
<?php ActiveForm::end(); ?>
<!-- ./form -->
<div id="modal-campanha" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			<h4 class="modal-title">Campanha</h4>
  			</div>
  			<!-- ./modal-header -->
  			<div class="modal-body">
    			<br><br><h1 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando</h1><br><br>
  			</div>
  			<!-- ./modal-body -->
		</div>
	</div>
</div>
<!-- /.modal campanha -->
<div id="modal-faixa-calculo" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			<h4 class="modal-title">Faixa de Cálculo</h4>
  			</div>
  			<!-- ./modal-header -->
  			<div class="modal-body">
    			<br><br><h1 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando</h1><br><br>
  			</div>
  			<!-- ./modal-body -->
		</div>
	</div>
</div>
<!-- /.modal faixa de calculo -->
<?php 
$this->registerJsFile(Url::home().'/app/js/credor-config.js', ['depends' => [JqueryAsset::className()]]);
?>




