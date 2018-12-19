<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use yii\bootstrap\BootstrapAsset;

use app\base\Util;
use app\models\Setor;
use app\models\Cargo;
use app\models\Cidade;
use app\models\EstadoFederacao;
use app\modules\financeiro\models\CentroCusto;

use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\money\MaskMoney;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;
?>
<div class="box box-<?= $class; ?>">
    <div class="box-body">
        <div class="row">
            <!-- Painel Esquerdo -->
            <div class="col-md-2">
                <div class="box box-default hidden-overflow">
                    <div class="box-body box-profile tour8">
                        <img class="profile-user-img img-responsive img-circle" id="img-colaborador" src="<?= $model->getFoto(); ?>" alt="Imagem do Colaborador">
						<!-- ./img -->
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <button id="btnAlterarFoto" class="<?= Util::BTN_COLOR_PRIMARY; ?> btn-xs" data-toggle="tooltip" title="Alterar Imagem"><i class="fa fa-camera fa-fw"></i></button>
                            </div>
                            <!-- ./button para add img -->
                            <?php if (!$model->isNewRecord): ?>
                                <div class="col-md-6 text-center">
                                    <button id="btnRemoverFoto" class="<?= Util::BTN_COLOR_DANGER; ?> btn-xs" data-toggle="tooltip" title="Remover Imagem"><i class="fa fa-times fa-fw"></i></button>
                                </div>
                            <?php endif; ?>
                            <!-- ./button para remover img -->
                        </div>
						<!-- ./row -->
                        <h3 class="profile-username text-center"><?= $model->nome; ?></h3>
						<!-- ./nome -->
                        <p class="text-muted text-center"><?= !empty($model->cargo) ? $model->cargo->cargo : '-'; ?></p>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        <b><i class="fa fa-book margin-r-5"></i> Contato</b>
                        <p class="text-muted"><?= Util::maskBackend($model->fone1, Util::MASK_TELEFONE); ?></p>
                        <p class="text-muted small"><a href="mailto:<?= $model->email_pessoal ?>"><?= $model->email_pessoal; ?></a></p>
                        <hr>
						<!-- ./contato -->
                        <b><i class="fa fa-map-marker margin-r-5"></i> Localização</b>
                        <p class="text-muted break-word">
                        	<?= $model->estado ? $model->estado->unidade_federacao : '-'; ?>
                        	/
                        	<?= $model->cidade ? $model->cidade->descricao : '-'; ?>
                    	</p>
                        <hr>
                        <!-- ./localizacao -->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- ./box -->
            </div>
            <!-- Painel Direito -->
            <div class="col-md-10">
                <?php
                    $form = ActiveForm::begin([
                        'id' => $model->formName(),
                        /* 'enableClientValidation' => false, */
                    ]);
    
                    // Foto do colaborador
                    echo $form->field($model, 'foto')->hiddenInput()->label(false);
                ?>
    				<div class="row">
    					<div class="col-md-10 tour-nav-menu">
                            <div class="nav-tabs-custom">
                                <!-- Custom Tabs -->
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab_DadosPrincipais" data-toggle="tab">Dados Principais</a></li>
                                        <li><a href="#tab_DadosEmpresa" data-toggle="tab">Dados Empresa</a></li>
                                        <li><a href="#tab_LoginPermissoes" data-toggle="tab">Login e Senha</a></li>
                                        <li><a href="#tab_Notificacoes" data-toggle="tab">Notificações</a></li>
                                        <li><a href="#tab_Widgets" data-toggle="tab">Preferências da Dashboard</a></li>
                                    </ul>
                                </div>
                            </div>
                    	</div>
            			<div class="col-md-2">
                        	<button type="button" class="btn btn-purple pull-right btn-sm btn-bootstrap-tour tour-btn" data-pjax="0" data-toggle="tooltip" title="Ajuda sobre o cadastro de colaborador" data-tour="cadastro">
                        		<i class="fa fa-question"></i>
                        	</button>
                      	</div>
                      	<!-- ./bootstrap tour button -->
                  	</div>
                  	<!-- ./row -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_DadosPrincipais">
                            <fieldset class="tour1">
                                <legend>Dados Pessoais</legend>
                                <div class="row">
                                    <div class="col-md-9 tour2">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'readonly' => $updatePerfil]); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'apelido')->textInput(['maxlength' => true])->hint('Apelido ou nome resumido'); ?>
                                                </div>
                                            </div>
                                    	</div>
        							    <!-- ./row -->
                        			</div>
                        			<!-- ./ col tour -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <?= $form->field($model, 'data_nascimento')->widget(DatePicker::classname(), [
                                					'options' => ['placeholder' => 'dd/mm/yyyy'],
                                				 	'pickerButton' => ['title' => 'Selecione a Data',],
                                				 	'removeButton' => ['title' => 'Limpar Data Selecionada',],
                                                    'disabled' => $updatePerfil,
                                    		    	'pluginOptions' => [
                                        		         'autoclose' => true,
                                        	    	     'removeButton' => false,
                                    		    	     'todayHighlight' => true,
                                	       	    	     'format' => 'dd/mm/yyyy',
                                    		    	     'endDate' => date('d/m/Y'),
                                		           	],   
                                                ]);
        		                            ?>
                                        </div>
                                    </div>
                                </div>
    							<!-- ./row -->
                                <div class="row">
                                    <div class="col-md-2 tour3">
                                        <div class="form-group">
                                            <?= $form->field($model, 'cpf')->widget(MaskedInput::className(), [
                                                    'mask' => '999.999.999-99',
                                                    'clientOptions' => ['placeholder' => '', 'removeMaskOnSubmit' => true]
                                            ])->textInput(['maxlength' => false, 'readonly' => $updatePerfil])->hint($updatePerfil ? '' : 'Exemplo: 000.000.000-00');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <?= $form->field($model, 'rg')->textInput(['maxlength' => true, 'readonly' => $updatePerfil]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <?= $form->field($model, 'fone1')->widget(MaskedInput::className(), [
                                                    'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
                                                    'clientOptions' => ['placeholder' => '', 'removeMaskOnSubmit' => true]
                                                ])->textInput(['maxlength' => false]);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <?= $form->field($model, 'fone2')->widget(MaskedInput::className(), [
                                                    'mask' => ['(99) 9999-9999', '(99) 99999-9999'],
                                                    'clientOptions' => ['placeholder' => '', 'removeMaskOnSubmit' => true]
                                                ])->textInput(['maxlength' => true]);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= $form->field($model, 'email_pessoal')->textInput(['maxlength' => true, 'placeholder' => 'email@email.com']); ?>
                                        </div>
                                    </div>
                                </div>
    							<!-- ./row -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= $form->field($model, 'cnh_numero')->textInput(['maxlength' => true, 'readonly' => $updatePerfil]); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <?= $form->field($model, 'cnh_categoria')->textInput(['maxlength' => true, 'readonly' => $updatePerfil])->hint($updatePerfil ? '' : 'A, B, C, AB, AC etc...'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        	 <?= $form->field($model, 'cnh_data_expedicao')->widget(DatePicker::classname(), [
                            					    'options' => ['placeholder' => 'dd/mm/yyyy'],
                                	    		    'removeButton' => false,
                            				 	    'pickerButton' => ['title' => 'Selecione a Data',],
                            				 	    'removeButton' => ['title' => 'Limpar Data Selecionada',],
                                        	        'disabled' => $updatePerfil,
                                		    	    'pluginOptions' => [
                                  		                'autoclose' => true,
                                		    		    'todayHighlight' => true,
                            	       	    		    'format' => 'dd/mm/yyyy',
                                		    	        'endDate' => date('d/m/Y'),
                            		           	    ],
                                                ]);
        		                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                             <?= $form->field($model, 'cnh_data_vencimento')->widget(DatePicker::classname(), [
                                					'options' => ['placeholder' => 'dd/mm/yyyy'],
                                    	    		'removeButton' => false,
                                				 	'pickerButton' => ['title' => 'Selecione a Data',],
                                				 	'removeButton' => ['title' => 'Limpar Data Selecionada',],
                                                    'disabled' => $updatePerfil,
                                    		    	'pluginOptions' => [
                                        		        'autoclose' => true,
                                        		    	'todayHighlight' => true,
                                    	       	    	'format' => 'dd/mm/yyyy',
                                        		    	'startDate' => date('d/m/Y'),
                                		           	],
                                                ]);
        		                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./row -->
                            </fieldset>
    						<!-- ./fieldset dados pessoais -->
                            <fieldset class="tour4">
                                <legend>Endereço</legend>
                                <div class="row">
                                	<div class="col-md-5 tour5">
                                		<div class="form-group">
                                			<div class="row">
                                            	<div class="col-md-6">
                                					<?=$form->field($model, 'cep')->widget(\yii\widgets\MaskedInput::className (), [
                                            	   		    'mask' => '99999-999',
                                    					    'clientOptions' => ['placeholder' => ' ', 'removeMaskOnSubmit' => true]
                                            	   		])->textInput(['maxlength' => false]); 
                                					?>
                                				</div>
                                				<div class="col-md-6">
                                					<button id="btn-busca-cep" type="button" class="btn btn-success btn-flat label-margin" data-loading-text="">
                                						<i class="fa fa-search"></i>&nbsp; Busque pelo CEP
                            						</button>
                                				</div>
                            				</div>
                                        </div>
                                	</div>
                                	<div class="col-md-5">
                                		<div class="form-group">
                                            <?= $form->field($model, 'logradouro')->textInput(['maxlength' => true]); ?>
                                        </div>
                                	</div>
                                	<div class="col-md-2">
                                		<div class="form-group">
                                            <?= $form->field($model, 'numero')->textInput(['maxlength' => true]); ?>
                                        </div>
                                	</div>
                                </div>
								<!-- ./row -->
                                <div class="row">
                                	<div class="col-md-4">
                                        <div class="form-group">
                                            <?= $form->field($model, 'bairro')->textInput(['maxlength' => true]); ?>
                                        </div>
                                    </div>
                                	<div class="col-md-4">
                                      	<div class="form-group">
                                            <?= $form->field($model, 'estado_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(EstadoFederacao::find()->orderBy('nome_estado')->all(), 'id', 'nome_estado'),
                                                    'pluginOptions' => ['allowClear' => true],
                                                    'hideSearch' => false,
                                                    'options' => [
                                                        'placeholder' => 'Selecione o Estado',
                                                        'onchange' => '
                                                            if ($(this).val() > 0) {
                                                                 $.post(BASE_PATH + "cidade/lists/'.'"+$(this).val(), function(data) {
                                                                    $("#select2-colaborador-cidade_id-container" ).html(\'\');
                                                                    $("#colaborador-cidade_id").html(data);
                                                                 }).done(function() {
                                                                     $("#colaborador-cidade_id").val($("#colaborador-cidade_id").data("cidade-id"));
                                                                 });
                                                            } else {
                                                                 $("#colaborador-cidade_id").html("");
                                                                 $("#colaborador-cidade_id").val(null).trigger("change.select2");
                                                            }
                                    					'
                                                    ],
                                                ]);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    	<div class="form-group">
                                            <?= $form->field($model, 'cidade_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(Cidade::findAll([
                                                        'estado_federacao_id' => $model->estado_id
                                                    ]), 'id', 'descricao'),
                                                    'pluginOptions' => ['allowClear' => true],
                                                    'hideSearch' => false,
                                                    'options' => [
                                                        'placeholder' => 'Selecione a cidade',
                                                        'data-cidade-id' => $model->cidade_id,
                                                    ]
                                                ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./row -->
                            </fieldset>
    						<!-- ./fieldset endereco -->
    						<?php if (!$updatePerfil): ?>
                                <fieldset>
                                    <legend>Dados do Cadastro</legend>
                                    <div class="row">
                                        <div class="col-md-6 tour6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'data_cadastro')->textInput(['disabled' => true]); ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'data_alteracao')->textInput(['disabled' => true]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ./col tour -->   
                                        <div class="col-md-2 tour7">
                                            <div class="form-group">
                                                <?= $form->field($model, 'ativo')->widget(SwitchInput::classname(), [
                                                        'pluginOptions' => [                                             
                                                            'onText' => 'Sim',
                                                            'offText' => 'Não',
                                                        ],
                                                    ]);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <!-- ./fieldset dados de cadastro -->
                            <?php endif; ?>
                        </div> 
                        <!-- /tab DadosPrincipais -->
                        <div class="tab-pane" id="tab_DadosEmpresa">
                            <fieldset>
                                <legend>Dados Empresa</legend>
                                <div class="row">
                                    <div class="col-md-12 tour9">
                                    	<div class="row">
                                    		<?php if (!$updatePerfil): ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'empresa_id')->widget(Select2::classname(), [
                                                                'data' => $empresas,
                                                                'options' => ['placeholder' => 'Selecione a Empresa'],
                                                                'pluginOptions' => ['allowClear' => true],
                                                                'hideSearch' => false,
                                                            ]); 
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'cargo_id')->widget(Select2::classname(), [
                                                            'data' => ArrayHelper::map(Cargo::findCargosAtivos(), 'id', 'cargo'),
                                                            'options' => ['placeholder' => 'Selecione o Cargo'],
                                                            'pluginOptions' => ['allowClear' => true],
                                                            'hideSearch' => false,
                                                            'disabled' => $updatePerfil,
                                                            'addon' => [
                                                                'append' => [
                                                                    'content' => Html::button('<i class="fa fa-plus font10"></i>', [
                                                                        'class' => Util::BTN_COLOR_PRIMARY,
                                                                        'title' => 'Cadastrar Novo Cargo',
                                                                        'data-toggle' => 'tooltip',
                                                                        'id' => 'btnNovoCargo',
                                                                        'disabled' => $updatePerfil,
                                                                    ]),
                                                                    'asButton' => true,
                                                                ],
                                                            ],
                                                        ]); 
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'setor_id')->widget(Select2::classname(), [
                                                            'data' => ArrayHelper::map(Setor::findSetores(), 'id', 'setor'),
                                                            'pluginOptions' => ['allowClear' => true],
                                                            'hideSearch' => false,
                                                            'disabled' => $updatePerfil,
                                                            'options' => ['placeholder' => 'Selecione o Setor',],
                                                            'addon' => [
                                                                'append' => [
                                                                    'content' => Html::button('<i class="fa fa-plus font10"></i>', [
                                                                        'class' => Util::BTN_COLOR_PRIMARY,
                                                                        'title' => 'Cadastrar Novo Setor',
                                                                        'data-toggle' => 'tooltip',
                                                                        'id' => 'btnNovoSetor',
                                                                        'disabled' => $updatePerfil,
                                                                    ]),
                                                                    'asButton' => true,
                                                                ],
                                                            ],
                                                        ]); 
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if (!$updatePerfil): ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'centro_custo_id')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(CentroCusto::findCentroCustoAtivo(), 'id', 'descricao'),
                                                                'options' => ['placeholder' => 'Selecione o Centro de Custo'],
                                                                'pluginOptions' => ['allowClear' => true],
                                                                'hideSearch' => false,
                                                                'addon' => [
                                                                    'append' => [
                                                                        'content' => Html::button('<i class="fa fa-plus font10"></i>', [
                                                                            'class' => Util::BTN_COLOR_PRIMARY,
                                                                            'title' => 'Cadastrar Novo Centro de Custo',
                                                                            'data-toggle' => 'tooltip',
                                                                            'id' => 'btnNovoCentroCusto',
                                                                        ]),
                                                                        'asButton' => true,
                                                                    ],
                                                                ],
                                                            ]); 
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'data_admissao')->widget(DatePicker::classname(), [
                                                            'options' => ['placeholder' => 'dd/mm/yyyy'],
                                                            'removeButton' => false,
                                                            'disabled' => $updatePerfil,
                                                            'pickerButton' => ['title' => 'Selecione a Data',],
                                                            'removeButton' => ['title' => 'Limpar Data Selecionada',],
                                                            'pluginOptions' => [
                                                                'autoclose' => true,
                                                                'todayHighlight' => true,
                                                                'format' => 'dd/mm/yyyy',
                                                            ]
                                                        ]);
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if (!$updatePerfil): ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'data_demissao')->widget(DatePicker::classname(), [
                                                                'options' => ['placeholder' => 'dd/mm/yyyy'],
                                                                'removeButton' => false,
                                                                'pickerButton' => ['title' => 'Selecione a Data',],
                                                                'removeButton' => ['title' => 'Limpar Data Selecionada',],
                                                                'pluginOptions' => [
                                                                    'autoclose' => true,
                                                                    'todayHighlight' => true,
                                                                    'format' => 'dd/mm/yyyy',
                                                                ]
                                                            ]);
                                                        ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'email_empresa')->textInput(['maxlength' => true, 'placeholder' => 'email@email.com', 'readonly' => $updatePerfil]); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <?= $form->field($model, 'ramal')->textInput(['maxlength' => true, 'readonly' => $updatePerfil]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ./row -->
                                        <?php if (!$updatePerfil): ?>
                                        	<div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'dados_complementares')->textarea(['rows' => 6, 'style' =>['resize' => 'none']]); ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'descricao_ocorrencias')->textarea(['rows' => 6, 'style' =>['resize' => 'none']]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ./row -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- ./row tour -->
                            </fieldset>
    						<!-- ./fieldset dados da empresa -->
    						<?php if (!$updatePerfil): ?>
                                <fieldset>
                                    <legend>Informações Financeiras</legend>
                                    <div class="row">
                                        <div class="col-md-12 tour10">
                                        	<div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'fator_calculo_comissao')->widget(Select2::classname(), [
                                                                'data' => ['P' => 'Produto', 'G' => 'Grupo', 'F' => 'Fixo'],
                                                                'pluginOptions' => ['allowClear' => true],
                                                                'hideSearch' => true,
                                                                'options' => [
                                                                    'placeholder' => 'Selecione'
                                                                ]
                                                            ]);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'percentual_comissao')->widget(MaskMoney::className(), []); ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'forma_credito')->widget(Select2::classname(), [
                                                                'data' => ['FT' => 'Faturamento', 'PC' => 'Pagamento do Cliente'],
                                                                'pluginOptions' => ['allowClear' => true],
                                                                'hideSearch' => true,
                                                                'options' => [
                                                                    'placeholder' => 'Selecione'
                                                                ]
                                                            ]);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'valor_hora')->widget(MaskMoney::className(), [
                                                                'name' => 'currency',
                                                                'pluginOptions' => [
                                                                    'prefix' => 'R$ ',
                                                                ]
                                                            ]);
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <?= $form->field($model, 'saldo_viagens')->textInput(['disabled' => 'disabled']); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ./row -->
                                        </div>
                                    </div>
                                    <!-- ./row tour -->
                                </fieldset>
                                <!-- ./fieldset informações financeiras -->
                            <?php endif; ?>
                        </div>
                        <!-- ./tab pane -->
                        <div class="tab-pane" id="tab_LoginPermissoes">
            				<fieldset>
            					<legend>Dados de Login</legend>
                        		<div class="row">
                        			<div class="col-md-12 tour11">
                                    	<div class="row">
                                        	<div class="col-md-4">
                                                <div class="form-group">
                                        		    <?= $form->field($modelUser, 'username', ['enableClientValidation' => false])->textInput(['autocomplete' => 'off']); ?>
                                        		</div>
                                        	</div>
                                        	<div class="col-md-4">
                                                <div class="form-group">
                                        		    <?= $form->field($modelUser, 'password', ['enableClientValidation' => false])->passwordInput(['value' => '', 'autocomplete' => 'off', 'id' => 'senha']); ?>
                                        			<small><i>Força da Senha:</i>&nbsp; </small><meter id="mtSenhaUsuario" value="0" max="20"></meter>
                                        		</div>
                                        	</div>
                                        	<div class="col-md-4">
                                                <div class="form-group">
                                        		    <?= $form->field($modelUser, 'password_repeat', ['enableClientValidation' => false])->passwordInput(['autocomplete' => 'off']); ?>
                                        		</div>
                                        	</div>
                                    	</div>
                        			</div>
                    			</div>
                			</fieldset>
                        </div>
                        <div class="tab-pane" id="tab_Notificacoes">
                        	<fieldset>
                        		<?= $this->render('_tab-notificacoes', ['model' => $model]); ?>
                        	</fieldset>
                        </div>
                        <!-- ./tab de notificacoes -->
                        <div class="tab-pane" id="tab_Widgets">
                			<fieldset>
                        		<?= $this->render('/user-config/index',['widgets' => $widgets]);?>
                			</fieldset>
                		</div>
						<!-- ./tab preferencias da dashboard -->
                    </div>
                </div>
            </div>
        </div>
        <!-- ./box-body -->
        <br><br>
        <div class="box-footer">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-6">
                	<div class="form-group">
                    	<?= Html::submitButton($model->isNewRecord ? Util::BTN_CREATE : Util::BTN_UPDATE, [
                        	    'class' => $model->isNewRecord 
                        	    ? Util::BTN_COLOR_SUCCESS.' btn-block' 
                        	    : Util::BTN_COLOR_PRIMARY.' btn-block'    
                        	]);
                    	?>
                    </div>
           		</div>
           		<div class="col-md-4 col-lg-4 col-sm-4 col-xs-6 pull-right">
                   	<div class="form-group">
                   		<?= Html::a(Util::BTN_CANCEL, ['index'], ['class' => Util::BTN_CANCEL_CLASS.' btn-block']); ?>
               		</div>
				</div>
            </div>
            <!-- ./row -->
        </div>
    </div>
    <!-- ./box -->
<?php ActiveForm::end(); ?>
<!-- ./form -->
<?php
// FileInput inside a modal dialog
// Modal para realizar o upload da foto do colaborador
Modal::begin([
    'id' => 'mbFoto',
    'header' => '<h4 class="modal-title text-primary"><i class="fa fa-camera"></i>&nbsp; Foto do Colaborador</h4>',
]);
    $formFoto = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]);
        echo FileInput::widget([
            'id' => 'colaborador-imagem',
            'name' => 'file',
            'options' => [
                'multiple' => false,
            ],
            'pluginOptions' => [
                'language' => 'pt-BR',
                'allowedFileTypes' => ['image'],
                'showCaption' => false,
                'showRemove' => false,
                'resizeImages' => true,
                'maxImageWidth' => 1024,
                'maxImageHeight' => 1024,
                'resizePreference' => 'width',
                'browseLabel' => '&nbsp; Procurar Imagem',
                'uploadLabel' => '&nbsp; Enviar Imagem',
                'browseClass' => Util::BTN_COLOR_DEFAULT,
                'uploadClass' => Util::BTN_COLOR_PRIMARY,
                'uploadUrl' => Url::to(['enviar-foto', 'id' => $model->id]),
            ],
        ]);
    ActiveForm::end();
Modal::end();
// modal foto
Modal::begin([
    'header' => '<h4 class="modal-title text-primary"><i class="fa fa-tags"></i>&nbsp; Cargo</h4>',
    'id' => 'mbCargo',
    'size' => Modal::SIZE_DEFAULT,
    'options' => [
        'class' => 'body-no-padding',
    ],
]);
    echo "<div class='modalContent'></div>";
Modal::end();
// modal cargo
Modal::begin([
    'header' => '<h4 class="modal-title text-primary"><i class="fa fa-tags"></i>&nbsp; Setor</h4>',
    'id' => 'mbSetor',
    'size' => Modal::SIZE_DEFAULT,
    'options' => [
        'class' => 'body-no-padding',
    ],
]);
    echo "<div class='modalContent'></div>";
Modal::end();
// modal setor

Modal::begin([
    'header' => '<h4 class="modal-title text-primary"><i class="fa fa-tags"></i>&nbsp; Centro de Custo</h4>',
    'id' => 'modalCentro',
    'size' => Modal::SIZE_DEFAULT,
    'options' => [
        'class' => 'body-no-padding',
    ],
]);
    echo "<div class='modalContent'></div>";
Modal::end();
// ./MODAL - CENTRO CUSTO
// TOUR
$this->registerCssFile(Url::home() . 'plugins/bootstrap-tour/css/bootstrap-tour.min.css', ['depends' => [BootstrapAsset::className()]]);
$this->registerJsFile(Url::home() . 'plugins/bootstrap-tour/js/bootstrap-tour.min.js', ['depends' => [JqueryAsset::className()]]);
//JS
$this->registerJsFile(Url::home() . 'app/js/colaborador.js?d=201817091753', ['depends' => [JqueryAsset::className()]]);
$this->registerJsFile(Url::home() . 'plugins/complexify/complexify.js', ['depends' => [JqueryAsset::className()]]);
?>
