<?php
use app\base\Util;
use yii\helpers\Html;
use app\models\Credor;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Estado;
use app\models\Cidade;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary panel-box">
		<div class="panel-body">
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
                    <?= $form->field($model, 'cidade_id')->widget(Select2::classname(), [
                            'data' => $model->cidade_id ? ArrayHelper::map(Cidade::find()->all(), 'id', 'nome') : [],
                            'options' => ['placeholder' => 'Selecione a cidade ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
    			</div>
    			<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
                    <?= $form->field($model, 'estado_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(Estado::find()->all(), 'id', 'nome'),
                            'options' => ['placeholder' => 'Selecione o estado ...'],
                            'pluginOptions' => [
                                'allowClear' => false,
                            ],
                            'pluginEvents' => [
                                "change" => "function() { 
                                    // envia a requisicao para buscar as cidades do estado
                                    $.get(BASE_PATH + 'site/cidades?ufId='+$(this).find('option:selected').val(), function(response) {
                                        // seta as cidades encontradas para o estado
                                        $('#credor-cidade_id').html(response);
                                    });
                                }",
                            ],
                        ]);
                    ?>
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
        <!-- ./painel-body -->
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; '. ($model->isNewRecord ? 'Cadastrar' : 'Alterar'), [
                                'class' => $model->isNewRecord 
                                ? Util::BTN_COLOR_SUCCESS.' btn-block' 
                                : Util::BTN_COLOR_PRIMARY.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/credor'], [
                                'class' => Util::BTN_COLOR_DEFAULT.' btn-block',
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




