<?php
use app\base\Util;
use yii\helpers\Html;
use app\models\Credor;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\CredorCampanha;
use yii\web\View;
?>
<?php $form = ActiveForm::begin(); ?>

    <div class="panel panel-primary panel-box">
		<div class="panel-body">
			<div class="nav-tabs-custom">
        		<ul class="nav nav-tabs">
					<li><a href="#tab-principal" data-toggle="tab">Principal</a></li>
					<li class="active"><a href="#tab-configuracao" data-toggle="tab">Configurações de Cálculo</a></li>
				</ul>
			</div>
			<!-- ./tabs -->
			<div class="tab-content">
				<div class="tab-pane" id="tab-principal">
					<div class="row">
	        			<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
	                        <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>
	        			</div>
	        			<div class="col-md-2 col-sm-2 col-xs-12 col-lg-2">
	                        <?= $form->field($model, 'tipo')->dropDownList([
		                        	Credor::TIPO_PADRAO => 'Padrão',
		                        ]); 
	                        ?>
	        			</div>
	        			<div class="col-md-2 col-sm-2 col-xs-12 col-lg-2">
	                        <?= $form->field($model, 'tipo_cobranca')->dropDownList([
		                        	Credor::TIPO_COBRANCA_ADM => 'Administrativa',
	                                Credor::TIPO_COBRANCA_JUR => 'Jurídica',
		                        ]); 
	                        ?>
	        			</div>
	        			<div class="col-md-2 col-sm-2 col-xs-12 col-lg-2">
	                        <?= $form->field($model, 'ativo')->dropDownList([
		                        	Credor::ATIVO => 'Ativo',
                                    Credor::NAO_ATIVO => 'Não Ativo',
		                        ]); 
	                        ?>
	        			</div>
	        		</div>
	        		<!-- ./row -->
	    			<div class="row">
	        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
                        	<?= $form->field($model, 'cnpj')->widget(MaskedInput::className(), [
									'mask' => '99.999.999/9999-99',
	                        	]);
	                        ?>
						</div>	      
        				<div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
	                        <?= $form->field($model, 'razao_social')->textInput(['maxlength' => true]); ?>
	        			</div>
	        		</div>  
	        		<!-- ./row -->
	        		<div class="row">
	                    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
	                        <?= $form->field($model, 'cep')->widget(MaskedInput::className(), [
                    				'mask' => '99999-999',
	                        	]);
	                        ?>
	        			</div>
	        			<div class="col-md-7 col-sm-7 col-lg-7 col-xs-12">
	                        <?= $form->field($model, 'logradouro')->textInput(['maxlength' => true]); ?>
	        			</div>
	        			<div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
	                        <?= $form->field($model, 'numero')->textInput(['maxlength' => true]); ?>
	        			</div>
	        		</div>
	        		<!-- ./row -->
	        		<div class="row">
        				<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
	                        <?= $form->field($model, 'complemento')->textInput(['maxlength' => true]); ?>
	        			</div>
	                    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
	                        <?= $form->field($model, 'bairro')->textInput(['maxlength' => true]); ?>
	        			</div>
	        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
	                        <?= $form->field($model, 'cidade_id')->textInput(['maxlength' => true]); ?>
	        			</div>
	        			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
	                        <?= $form->field($model, 'estado_id')->textInput(['maxlength' => true]); ?>
	        			</div>
	        		</div>
	        		<!-- ./row -->
	        		<div class="row">
        				<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
	                        <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]); ?>
	        			</div>
	                    <div class="col-md-5 col-sm-5 col-lg-5 col-xs-12">
	                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>
	        			</div>
	        		</div>
	        		<!-- ./row -->     		
				</div>
				<!-- ./tab principal -->
			  	<div class="tab-pane active" id="tab-configuracao">
			  		<br>
			  		<div class="row">
			  			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                        	<?= $form->field($model, 'id_campanha')->dropDownList($model->credorCampanhas, [
                	                'prompt' => 'Selecione a Campanha ...',
                            	]); 
                        	?>
            			</div>
            			<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                            <?= Html::label('Ações'); ?>
                            <div class="form-group">
                            	<?= Html::button('<i class="fa fa-plus fa-fw"></i>', [
                    	                'id' => 'btn-nova-campanha',
                    	                'class' => Util::BTN_COLOR_EMERALD,
                                	]); 
                            	?>
                            	<?= Html::button('<i class="fa fa-edit fa-fw"></i>', [
                    	                'class' => Util::BTN_COLOR_WARNING.' campanha-action-button',
                    	                'disabled' => $model->id_campanha ? false : true,
                                	]); 
                            	?>
                            	<?= Html::button('<i class="fa fa-times fa-fw"></i>', [
                    	                'class' => Util::BTN_COLOR_DANGER.' campanha-action-button',
                    	                'disabled' => $model->id_campanha ? false : true,
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
	  				                'class' => Util::BTN_COLOR_EMERALD.' btn-block',
                                    'disabled' => $model->id_campanha ? false : true,
	  				                'data-toggle' => 'modal',
	  				                'data-target' => '#modal-faixa-calculo'
			  				   ]); 
			  				?>
			  			</div>
			  		</div>
			  		<div class="row">
			  			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
			  				<table class="table table-bordered table-hover">
			  					<thead>
			  						<tr>
        			  					<th>Atraso</th>
        			  					<th>Multa</th>
        			  					<th>Juros</th>
        			  					<th class="text-center"></th>
        			  				</tr>
			  					</thead>
			  					<!-- ./thead -->
			  					<tbody>
	        			  			<?php if($model->credorCampanha): ?>
    			  						<?php foreach($model->credorCampanhas->credorCalculos as $calculo): ?>
    			  							<tr id="<?= $calculo->id; ?>">
    			  								<td><?= $calculo->getAtraso(); ?></td>
    			  								<td><?= $calculo->multa; ?></td>
    			  								<td><?= $calculo->juros; ?></td>
    			  								<td class="text-center">
    			  									<?= Html::button('<i class="fa fa-edit"></i>', [
                        	  				                'class' => Util::BTN_COLOR_WARNING.' editar-faixa'
                        			  				   ]); 
                        			  				?>
    			  									<?= Html::button('<i class="fa fa-times"></i>', [ 
                        	  				                'class' => Util::BTN_COLOR_DANGER.' excluir-faixa'
                        			  				   ]); 
                        			  				?>
    			  								</td>
    			  							</tr>
    			  						<?php endforeach; ?>
            			  			<?php endif; ?>
			  					</tbody>
			  					<!-- ./tbody -->
			  				</table>
			  				<!-- ./table -->
			  			</div>
			  		</div>
			  	</div>
			  	<!-- ./tab configuracao -->
			</div>
        </div>
        <!-- ./painel-body -->
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; '. ($model->isNewRecord ? 'Cadastrar' : 'Alterar'), [
                                'class' => $model->isNewRecord 
                                ? Util::BTN_COLOR_SUCCESS.' btn-block' 
                                : Util::BTN_COLOR_PRIMARY.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/credor'], [
                                'class' => Util::BTN_COLOR_DEFAULT.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    		</div>
        </div>
        <!-- ./panel-footer -->
	</div>
	<!-- ./box -->
	<div id="campanhas-lista" class="hidden" data-id="0"></div>
	<!-- ./lista de novas campanhas -->
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
$script = <<<JS
$(document).ready(function() {
    // insere a animação quando a modal for dechada
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('.modal-body').html('<br><br><h1 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando</h1><br><br>');
    });

    // abre a modal de nova campanha
    $('body').on('click', '#btn-nova-campanha', function() {
        // seleciona a modal
        let modal = $('#modal-campanha');        

        // exibe a modal
        modal.modal('show');

        // envia o post para renderizar o form
        $.post(BASE_PATH + 'credor-campanha/create', function(result) {
            modal.find('.modal-body').html(result);
        });
    });

    // evento quando o select de campanha for alterado
    $('body').on('change', '#credor-id_campanha', function() {
        if (this.value != '') {
            $('.campanha-action-button').prop('disabled', false);
        } else {
            $('.campanha-action-button').prop('disabled', true);
        }        
    });
});
JS;
$this->registerJs($script, View::POS_LOAD);
?>




