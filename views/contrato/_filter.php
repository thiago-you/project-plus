<?php
use app\base\Helper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\Typeahead;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Credor;
?>
<div class="box">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
            <?php $form = ActiveForm::begin(['id' => 'form-filtro', 'method' => 'post']); ?>
            	<div class="box-header">
            		<h4><i class="fa fa-filter"></i>&nbsp; Opções de Filtro</h4>
        		</div>
            	<!-- ./box-header -->
               	<div class="box-body">       	
                	<div class="row">
            			<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
                			<?= $form->field($model, 'nome')->widget(Typeahead::classname(),[
                                    'pluginOptions' => ['highlight' => true],
        			                'options' => [
		                                'autocomplete' => 'off',
        			                ],
                                    'dataset' => [
                                       [
                                          'display'=> 'value',
                                          'notFound' => '<span class="alert alert-danger"><i class="fa fa-ban"></i>&nbsp; Nehum cliente foi encontrado ...</div>',
                                          'remote'=>[
                                              'url' => Url::to(['/cliente/search-list']).'?q[nome]=%QUERY',
                                              'wildcard' => '%QUERY'
                                           ],
                                       ],
                                    ],
                                ]);
                            ?>	
                		</div>
            		</div>
            		<!-- ./row -->
            		<div class="row">
                    	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                			<?= $form->field($model, 'telefone')->widget(Typeahead::classname(), [
                                    'pluginOptions' => ['highlight' => true],
        			                'options' => [
        			                    'value' => Helper::mask($model->telefone, Helper::MASK_TELEFONE),
		                                'autocomplete' => 'off',
        			                ],
                                    'dataset' => [
                                       [
                                          'display'=> 'value',
                                          'notFound' => '<span class="alert alert-danger"><i class="fa fa-ban"></i>&nbsp; Nehum cliente foi encontrado ...</div>',
                                          'remote'=>[
                                              'url' => Url::to(['search-list']).'?q[telefone]=%QUERY',
                                              'wildcard' => '%QUERY'
                                           ]
                                       ]
                                    ]
                                ]);
                            ?>	
                		</div>
                		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                			<?= $form->field($model, 'documento')->widget(Typeahead::classname(),[
                                    'pluginOptions' => ['highlight' => true],
        			                'options' => [
		                                'value' => strlen($model->documento) == 11 ? Helper::mask($model->documento, Helper::MASK_CPF) : (strlen($model->documento) == 14 ? Helper::mask($model->documento, Helper::MASK_CNPJ): $model->documento),
		                                'autocomplete' => 'off',
        			                ],
                                    'dataset' => [
                                       [
                                          'display'=> 'value',
                                          'notFound' => '<span class="alert alert-danger"><i class="fa fa-ban"></i>&nbsp; Nehum cliente foi encontrado ...</div>',
                                          'remote'=>[
                                              'url' => Url::to(['search-list']).'?q[documento]=%QUERY',
                                              'wildcard' => '%QUERY'
                                           ]
                                       ]
                                    ]
                                ]);
                            ?>	
                		</div>
                		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                			<?= $form->field($model, 'id_credor')->widget(Select2::className(), [
                    			   'data' => ArrayHelper::map(Credor::find()->all(), 'id', 'nome'),
        			                'options' => [
		                                'placeholder' => 'Selecione ...',
        			                ],
                    			]);
                            ?>	
                		</div>
            		</div>
            		<!-- ./row -->
            	</div>
            	<!-- ./box-body -->
            	<br>
            	<div class="box-footer">
            		<div class="row">
                		<div class="col-md-6 col-lg-6 col-xs-12 col-sm-6">
            				<?= Html::submitButton('<i class="fa fa-search"></i>&nbsp; Pesquisar', [
            				        'class' => Helper::BTN_COLOR_PRIMARY.' btn-block',
            				    ]);
            				?>
                		</div>
                	</div>
            	</div>
            	<!-- ./box-footer -->
                <!-- ./box -->
            <?php ActiveForm::end(); ?>
            <!-- ./form -->
        </div>
    </div>
</div>
<!-- ./box -->
