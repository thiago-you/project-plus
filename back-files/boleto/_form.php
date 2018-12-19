<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use kartik\grid\GridView;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use kartik\typeahead\Typeahead;

use app\base\Util;
use app\modules\financeiro\models\Conta;
?>

<?php $form = ActiveForm::begin([
        'enableAjaxValidation'=>false,
        'enableClientValidation' => true,
        'validateOnSubmit' => true,
    ]);
?>
<div class="box box-default">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<?= Html::hiddenInput('Boleto[cliente_id]', $model->cliente_id, ['id' => 'boleto-cliente_id']); ?>
				<label class="control-label" for="cliente">Cliente</label>
                <?= Typeahead::widget( [
                        'options' => [
                            'placeholder' => 'Digite nome, email ou cpf/cnpj para buscar um cliente...',
                            'maxlength' => 50,   
                            'id' => 'cliente_nome',
                        ],
                        'name' => 'cliente',
                        'pluginOptions' => ['highlight' => false],
                        'pluginEvents' => [                            
                            "typeahead:select" => 'function(a, b) {
                                $("#boleto-cliente_id").val(b[0].id);
                                $("#boleto-cliente-nome").text(b[0].nome);
                                $("#boleto-cliente-email").text(b[0].email ? b[0].email : "Email Não Informado");
                                
                                if (b[0].cpf != undefined && b[0].cpf.length > 0) {
                                    $("#boleto-cliente-identificacao").text(b[0].cpf);
                                } else {
                                    $("#boleto-cliente-identificacao").text(b[0].cnpj);
                                }
                            }',
                        ],
                        'dataset' => [
                            [
                                'local' => [],
                                'limit' => 10,
                                'identify' => (new \yii\web\JsExpression("function(data){}")),
                                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace",
                                'prefetch' => Url::base().'/clientes/list-especial',
                                'remote' => [
                                    'url' => Url::to(['/clientes/list-especial']) . '?q=%QUERY',
                                    'wildcard' => '%QUERY'
                                ],
                                'display' => (new \yii\web\JsExpression("processaObjetoNome")),
                                'templates' => [
                                    'notFound' => '<div class="text-danger" style="padding:0 8px">Não foi possível encontrar nenhum cliente com essa expressão. Digite o nome ou CPF/CNPJ de um cliente cadastrado.</div>',
                                ],
                            ]
                        ]
                    ]);
                ?>
                <table class="table table-condensed table-bordered">
                	<tr class="warning">
                		<td width="5%"><i class="fa fa-user"></i></td>
                		<td>
                			<b class="text-success" id="boleto-cliente-nome">
                				<?= $model->cliente->nome; ?>
                			</b>
                		</td>
                	</tr>
                	<tr class="danger">
                		<td><i class="fa fa-asterisk"></i></td>
                		<td>
                			<b class="text-success" id="boleto-cliente-identificacao">
                				<?= empty($model->cliente->cpf) ? Util::maskBackend($model->cliente->cnpj, Util::MASK_CNPJ) : Util::maskBackend($model->cliente->cpf, Util::MASK_CPF) ?>
                			</b>                		
                		</td>
                	</tr>
                	<tr class="warning">
                		<td><i class="fa fa-at"></i></td>
                		<td>
                			<b class="text-success" id="boleto-cliente-email">
                				<?= $model->cliente->email; ?>
                			</b>
                		</td>
                	</tr>
                </table>
			</div>
			<div class="col-md-3">
				<div class="form-group">
                	<?= $form->field($model, 'conta_id')->widget(Select2::className(), [
                    	    'name' => 'Boleto[conta_id]',
                    	    'id' => 'boleto-conta_id',
                    	    'hideSearch' => true,
                    	    'data' => ArrayHelper::map(Conta::find()->habilitada()->all(), 'id', 'nome', 'conta'),
                    	])->hint('São listadas apenas contas habilitadas a emissão de boleto');	
                	?>
                </div>
			</div>
			<div class="col-md-3">
				<div class="box box-solid box-success">
                	<div class="box-header with-border"><h3 class="box-title text-center">Situação</h3></div>
                	<div class="box-body">
                		<div class="row">
                			<div class="col-md-6">
                				<b class="text-info text-center"><?= empty($model->situacao) ? 'NOVO' : $model->situacao ?></b>                			
                			</div>
                		</div>
                	</div>
            	</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
    			<fieldset>
    				<legend class="font18">
    					<b class="text-info"><i class="fa fa-info-circle"></i> Informações do Boleto</b>
					</legend>
					<!-- ./legend do fieldset -->
    				<div class="row">
    					<div class="col-md-9">
            				<div class="row">
                				<div class="col-md-4">
                					<div class="form-group">
                						<?= $form->field($model, 'valor')->widget(MaskMoney::className(), [
                                            'pluginOptions' => ['prefix' => 'R$ ', 'precision' => 2],
                                        ]);?>
                                    </div>
                				</div>
                				<div class="col-md-4">
                					<div class="form-group">
                                    	<?= $form->field($model, 'data_vencimento')->widget(DatePicker::classname(), [
                                                'options' => [
                                                    'placeholder' => 'dd/mm/yyyy',
                                                    'maxlength' => 15
                                                ],
                                                'removeButton' => false,
                                                'pickerButton' => ['title' => 'Selecione a Data',],
                                                'removeButton' => ['title' => 'Limpar Data Selecionada',],
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'todayHighlight' => true,
                                                	'startDate' => '1d',	
                                                    'format' => 'dd/mm/yyyy',
                                                ]
                                            ]); 
                                        ?>
                                    </div>
                				</div>
                				<div class="col-md-4">
                					<div class="form-group">
                                    	<?= $form->field($model, 'data_emissao')->widget(DatePicker::classname(), [
                                                'options' => [
                                                    'placeholder' => 'dd/mm/yyyy',
                                                    'maxlength' => 15,
                                                    'value' => date('d/m/Y')
                                                ],
                                                'removeButton' => false,
                                        	    'pickerButton' => ['title' => 'Selecione a Data',],
                                        	    'removeButton' => ['title' => 'Limpar Data Selecionada',],
                                                'pluginOptions' => [
                                                    'autoclose' => true,
                                                    'todayHighlight' => true,
                                                	'startDate' => '0d',	
                                                    'format' => 'dd/mm/yyyy',
                                                ]
                                            ]); 
                                        ?>
                                    </div>	
                				</div>
                			</div>	
                			<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
                                		<?= $form->field($model, 'instrucao')->textarea([
                                                'placeholder' => 'Descreva aqui as instruções que devem aparecer no corpo do boleto.',
                                		        'maxlength' => 100,
                                		        'style' => ['resize' => 'none'],
                                            ]); 
                                		?>
                                    </div>
                				</div>        			
                			</div>
                			<div class="row">
                				<div class="col-md-3">
                					<div class="form-group">
                                		<?= $form->field($model, 'documento')->textInput(['maxlength' => true, 'placeholder' => 'Nº Documento Auxiliar']); ?>
                                    </div>
                				</div>
                			</div>
                		</div>	
                		<div class="col-md-3">
                			<div class="box box-solid box-info">
                            	<div class="box-header with-border"><h3 class="box-title text-center">Financeiro</h3></div>
                            	<div class="box-body">
                            		<div class="row">
                            			<div class="col-md-12">
                            				<div class="form-group text-center">
                                                <?= $form->field($model, 'financeiro')->widget(CheckboxX::className(), [
                                                        'options'=>['style' => 'margin-bottom: 15px', 'value' => ($model->isNewRecord) ? 1 : $model->financeiro],
                                    				    'pluginOptions'=>['threeState'=>false]                                                        
                                                    ])->label('Gerar Contas a Receber');
                                                ?>
                                            </div>
                            			</div>
                            		</div>
                            		<div class="row">
                        				<div class="col-md-12 financeiroDepend" style="display: <?= $model->financeiro || $model->isNewRecord ? 'block' :  'none' ?>;">
                                        	<?= $form->field($model, 'centro_custo')->widget(\kartik\widgets\Select2::className(),[
                                                    'data' => \yii\helpers\ArrayHelper::map(\app\modules\financeiro\models\CentroCusto::find()->all(), 'id', 'descricao'),
                                                    'options' => ['placeholder' => 'Centro de Custo ...'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'id' => 'centro_custo'
                                                ])->label('Centro de Custo <i class="text-danger">*</i>');
                                            ?>
                                        </div>
                            		</div>
                            		<div class="row">
                            			<div class="col-md-12 financeiroDepend" style="display: <?= $model->financeiro || $model->isNewRecord ? 'block' :  'none' ?>;">
                                            <?= $form->field($model, 'conta_orcamentaria')->widget(\kartik\widgets\Select2::className(), [
                                                    'data'=>\yii\helpers\ArrayHelper::map(\app\modules\financeiro\models\ContaOrcamentaria::find()->all(), 'id', 'nome_conta'),
                                                    'options' => ['placeholder' => 'Conta Orçamentária ...'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'id'=>'conta_orcamentaria'
                                                ])->label('Conta Orcamentária <i class="text-danger">*</i>');
                                            ?>
                                        </div>	
                            		</div>
                            	</div>
                        	</div>	
                		</div>
    				</div>
    			</fieldset>
    		</div>	
		</div>
		<?php if($dataProvider) { ?>
    		<div class="row">
    			<div class="col-md-12">
    				<?php 
    				    $gridColumns = [
    				        'codigo',
    				        'descricao',
    				        'data_ocorrencia'
    				    ];
    				
    				    // grid
    				    echo GridView::widget([
    				        'dataProvider' => $dataProvider,
    				        'columns' => $gridColumns,
    				        'containerOptions' => ['style' => 'overflow: auto'], // only set when responsive = false
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
    				            'type' => GridView::TYPE_WARNING,
    				            'heading' => '<i class="fa fa-list"></i> Ocorrências Pendentes do Boleto',
    				            'footer' => false
    				        ],
    				        'persistResize' => false,
    				    ]);
    				
    				?>		
    			</div>
    		</div>
		<?php } ?>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
				<?= Html::submitButton(($model->isNewRecord) ? Util::BTN_CREATE : Util::BTN_UPDATE, [
				        'class'=> ($model->isNewRecord) 
    				    ? Util::BTN_COLOR_SUCCESS.' btn-block' 
    				    : Util::BTN_COLOR_WARNING.' btn-block',
    				]); 
				?>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4 pull-right">
				<?= Html::a(Util::BTN_RETURN, ['index'], [
    				    'class' => Util::BTN_COLOR_DEFAULT.' btn-block',
    				]);
				?>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
<!-- ./form -->
<?php 
$this->registerJsFile(Url::home().'app/js/boleto.js?d=201802141435', ['depends' => [JqueryAsset::className()]]);
?>
