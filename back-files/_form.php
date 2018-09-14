<?php
use app\base\Helper;
use kartik\grid\GridView;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\bootstrap\BootstrapAsset;
?>

<div class="row">
    <div class="col-md-12">
        <?php
            if (!empty($erros)) {
                echo '
                <div class="error callout callout-danger">
            	   <h4>As informações não puderam ser salvas!</h4>
            	   <p>' . $erros . '</p>
            	</div>';
            }
        ?>
    </div>
</div>

<?php
if (!empty($errotab)) {
    $tab = 0;

    if ($tab == 0) {
        if (array_key_exists('nome', $errotab)) {
            $tab = '#tabDadosPrincipais';
        }
        if (array_key_exists('cpf', $errotab)) {
            $tab = '#tabDadosPrincipais';
        }
        if (array_key_exists('cnpj', $errotab)) {
            $tab = '#tabDadosPrincipais';
        }

    }

    if ($tab !== '#tabDadosPrincipais') {
        
        if (array_key_exists('cep', $errotab)) {
            $tab = '#tabDadosEndereco';
        }
        if (array_key_exists('endereco', $errotab)) {
            $tab = '#tabDadosEndereco';
        }
        if (array_key_exists('numero', $errotab)) {
            $tab = '#tabDadosEndereco';
        }
        if (array_key_exists('bairro', $errotab)) {
            $tab = '#tabDadosEndereco';
        }
        if (array_key_exists('id_estado', $errotab)) {
            $tab = '#tabDadosEndereco';
        }
        if (array_key_exists('id_cidade', $errotab)) {
            $tab = '#tabDadosEndereco';
        }
        if (array_key_exists('fone', $errotab)) {
            $tab = '#tabDadosEndereco';
        }

    }

$script_tabx = <<< JS
	$(document).ready(function() {
	$('[href="{$tab}"]').trigger( 'click' );
	$('#dv_cpf').select();
	});
JS;
    $this->registerJs($script_tabx, \yii\web\View::POS_LOAD);
}
?>
<div class="box box-<?= $class ?>">
	<?php 
    	$form = ActiveForm::begin([
    	    'id'=>$model->formName(),
    	    'enableAjaxValidation' => false,
            'enableClientValidation' => false,
    	    'validateOnSubmit' => true,
    	]);
	?>
    	<div class="box-body">
    		<div class="col-md-10">
        		<div class="nav-tabs-custom no-margin">
    		    	<ul class="nav nav-tabs tour-menu">
        		    	<li id="tab-1" class="active cliente-tab"><a href="#tab_Cadastro" data-toggle="tab"><i class="fa fa-edit"></i>&nbsp; Cadastro</a></li>
        		    	<?php if($model->isNewRecord): //só exibe o histórico na alteração ?>
        		        	<li class="disabled"><a href="#"><i class="fa fa-history"></i>&nbsp; Histórico</a></li>
        		        	<li class="disabled"><a href="#"><i class="fa fa-credit-card"></i>&nbsp; Crédito</a></li>
        		        <?php else: ?>	
            		        <li id="tab-2" class="cliente-tab"><a href="#tab_Historico" data-toggle="tab"><i class="fa fa-history"></i>&nbsp; Histórico</a></li>
        		        	<li id="tab-3" class="cliente-tab"><a href="#tab_Credito" data-toggle="tab"><i class="fa fa-credit-card"></i>&nbsp; Crédito</a></li>
        		        <?php endif; ?>
        			</ul>
        		</div>
    		</div>
    		<!-- ./nav tabs -->    		
			<div class="col-md-2">
            	<button type="button" class="btn btn-purple pull-right btn-sm btn-bootstrap-tour tour-btn" data-pjax="0" data-toggle="tooltip" title="Ajuda sobre o cadastro de cliente" data-tour="cadastro">
            		<i class="fa fa-question"></i>
            	</button>
          	</div>
          	<!-- ./bootstrap tour button -->
    		<div class="tab-content">
    			<div class="tab-pane active" id="tab_Cadastro">
            		<div class="row">
            		    <!-- Navigation Buttons -->
            		    <div class="col-md-3">
            				<ul class="nav nav-pills nav-stacked" id="myTabs">
            		            <li class="active"><a href="#tabDadosPrincipais" id="ln_tabDadosPrincipais" data-toggle="tab">Dados Principais</a></li>
            		            <li><a href="#tabDadosEndereco" id="ln_tabDadosEndereco" data-toggle="tab">Endereço</a></li>
            		            <li><a href="#tabDadosFinanceiro" id="ln_tabDadosFinanceiro" data-toggle="tab">Financeiro</a></li>
            		            <li><a href="#tabDadosCobranca" id="ln_tabDadosCobranca" data-toggle="tab">Cobrança</a></li>
            		    	</ul>
            		    </div>
            		    <div class="col-md-9">
    				    	<div class="tab-content">
    				    		<div class="tab-pane active" id="tabDadosPrincipais">
    				    			<?= $this->render('/clientes/telas/_form_principal', [
                                			'model' => $model,
                                			'form'  => $form,
                                        ]);
                            		?>
    				    		</div>
    				    		<div class="tab-pane" id="tabDadosEndereco">
    				    			<?= $this->render('/clientes/telas/_form_endereco', [
                                			'model' => $model,
                                			'form'  => $form,
                                        ]);
                                	?>
    				    		</div>
    				    		<div class="tab-pane" id="tabDadosFinanceiro">
    				    			<?= $this->render('/clientes/telas/_form_financeiro', [
                            				'model' => $model,
                            				'form'  => $form,
                                        ]);
                            		?>
    				    		</div>
    				    		<div class="tab-pane" id="tabDadosCobranca">
    				    			<?= $this->render('/clientes/telas/_form_cobranca', [
                            				'model' => $model,
                            				'form'  => $form,
                                        ]);
                            		?>
    				    		</div>
    			    		</div>
    		    		</div>
            	    </div>
            	</div>
                <!-- ./tab cadastro -->
            	<div class="tab-pane" id="tab_Historico">
    				<div class="row">
            			<div class="col-md-12">					
            				<?= GridView::widget([
            			    	    'dataProvider' => $model->getHistoricoProvider(),
            			    	    'columns' => [
            				            [
            				                'attribute' => 'id',
            				                'label' => '#',
            				                'hAlign' => 'center',
            				            ],
            				            [
            				                'attribute' => 'data',
            				                'label' => 'Data do Pedido',
            				            ],
            			    	        [
                			    	        'attribute' => 'situacao',
                			    	        'label' => 'Situação',
            			    	        ],
            				            [
            				                'attribute' => 'total',
            				                'label' => 'Valor Total',
            				            ],
            				            [
            				                'attribute' => 'valor',
            				                'label' => 'Valor Recebido',
            				            ],
            				            [
            				                'attribute' => 'pagto',
            				                'format' => 'raw',
            				                'header' => '<span class="text-primary"> Tipo de Pagamento </span>',
            				            ],
            				            [
            				                'attribute' => 'empresa',
            				                'label' => 'Empresa',
            				            ],
            				            [
            				                'attribute' => 'vendedor',
            				                'label' => 'Vendedor',
            				            ],
            						],
            			    	    'containerOptions' => ['style' => 'overflow: auto'], 
            			    	    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            			    	    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            			    	    'pjax' => true,
            			    	    'toolbar' => '&nbsp',
            			    	    'bordered' => true,
            			    	    'striped' => true,
            			    	    'condensed' => true,
            			    	    'responsive' => false,
            			    	    'hover' => true,
            			    	    'panel' => [
            			    	        'type' => GridView::TYPE_PRIMARY,
            			    	        'heading' => 'Histórico de Pedidos',
            			    	    ],  	    
            			    	]);
            				?>
            				<!-- ./grid historico -->
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- ./row -->
    			</div>
    			<!-- ./tab historico -->
            	<div class="tab-pane" id="tab_Credito">
    				<div class="row">
                        <div class="col-md-4 credito-tour2">
                            <div class="info-box info-box-60 bg-teal">
                                <span class="info-box-icon" style=""><i class="fa fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Crédito Total</span>
                                    <span class="info-box-number"><?= Helper::maskBackend($model->getCreditoTotal(), Helper::MASK_MONEY); ?></span>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- ./col-md-4 -->
                        <div class="col-md-4 credito-tour3">
                            <div class="info-box info-box-60 bg-lava">
                                <span class="info-box-icon "><i class="fa fa-money-bill-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Crédito Usado</span>
                                    <span class="info-box-number"><?= Helper::maskBackend($model->getCreditoUsado(), Helper::MASK_MONEY); ?></span>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-md-4 credito-tour4">
                            <div class="info-box info-box-60 bg-emerald">
                                <span class="info-box-icon "><i class="fa fa-credit-card"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Crédito Disponível</span>
                                    <span class="info-box-number"><?= Helper::maskBackend($model->getCreditoSaldo(), Helper::MASK_MONEY); ?></span>
                                </div>
                            </div>
                        </div>
                        <!-- ./col-md-4 -->
    				</div>
    				<!-- ./resumo -->
    				<div class="row">
            			<div class="col-md-12 credito-tour5">						
            				<?= GridView::widget([
            				        'dataProvider' => $model->getCreditoHistorico(),
            			    	    'columns' => [
            			    	        [
            			    	            'class'=>'kartik\grid\SerialColumn',
            			    	            'contentOptions' => ['class'=>'kartik-sheet-style'],
            			    	            'width' => '36px',
            			    	            'format' => 'raw',
            			    	            'header' => '<span class="text-primary">#</span>',
            			    	            'hAlign' => 'center',
            			    	            'headerOptions' => ['class'=>'kartik-sheet-style'],
            			    	        ],
            				            [
            				                'attribute' => 'data',
            				                'hAlign' => 'center',
            				                'width' => '15%',
            				                'value' => function($model) {
            				                    return Helper::formatDateToDisplay($model['data'], Helper::DATE_DEFAULT, true);
            				                },
            				            ],
            			    	        [
                			    	        'attribute' => 'tipo',
            			    	            'hAlign' => 'center',
            			    	            'width' => '10%',
            			    	        ],
            				            [
            				                'attribute' => 'descricao',
            				                'label' => 'Descrição',
            				                'width' => '25%',
            				            ],
            				            [
            				                'attribute' => 'valor',
            				                'width' => '10%',
            				                'format' => 'currency',
            				                'hAlign' => 'center',
            				            ],
            				            [
            				                'attribute' => 'obs',
            				                'label' => 'Obs.',
            				                
            				            ],
            						],
            			    	    'containerOptions' => ['style' => 'overflow: auto'], 
            			    	    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            			    	    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            			    	    'pjax' => true,
            			    	    'toolbar' => '&nbsp',
            			    	    'bordered' => true,
            			    	    'striped' => true,
            			    	    'condensed' => true,
            			    	    'responsive' => false,
            			    	    'hover' => true,
            			    	    'panel' => [
            			    	        'type' => GridView::TYPE_PRIMARY,
            			    	        'heading' => 'Histórico de Crédito',
            			    	    ],    
            			    	]);
            				?>
        				</div>
        				<!-- ./col -->
    				</div>
    				<!-- ./row -->
    			</div>
    			<!-- ./tab credito -->
            </div>
            <!-- ./tab content -->
    	</div>
    	<!-- ./box body -->
    	<div class="box-footer">
    		<div class="row">
    			<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4">
            		<div class="form-group">
                    	<?= Html::submitButton($model->isNewRecord ? Helper::BTN_CREATE : Helper::BTN_UPDATE, [
                        	    'id' => 'btnSalvar',   
                        	    'class' => $model->isNewRecord 
                        	    ? Helper::BTN_COLOR_SUCCESS.' btn-block'
                        	    : Helper::BTN_COLOR_PRIMARY.' btn-block', 
                        	]); 
                    	?>
            		</div>
        		</div>
        		<div class="col-md-4 col-sm-4 col-xs-6 col-lg-4 pull-right">
            		<div class="form-group">
                    	<?= Html::a('<i class="fa fa-reply"></i>&nbsp; Cancelar', ['index'], [
                        	    'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
                        	]); 
                    	?>
            		</div>
        		</div>
    		</div>
        </div>
        <!-- ./box-footer -->
    <?php ActiveForm::end(); ?>
    <!-- ./form -->
</div>
<!-- ./box -->
<?php 
if($model->tipo == 2) {
// pessoa juridica
$js = <<< JS
   document.getElementById('dv_cpf').style.display = 'none';
   document.getElementById('dv_cnpj').style.display = 'block';
JS;
}else {
// pessoa fisica
$js = <<< JS
   document.getElementById('dv_cnpj').style.display = 'none';	
   document.getElementById('dv_cpf').style.display = 'block';
JS;
}
// TOUR
$this->registerCssFile(Url::home() . 'plugins/bootstrap-tour/css/bootstrap-tour.min.css', ['depends' => [BootstrapAsset::className()]]);
$this->registerJsFile(Url::home() . 'plugins/bootstrap-tour/js/bootstrap-tour.min.js', ['depends' => [JqueryAsset::className()]]);
// JS
$this->registerJs($js);
$this->registerJsFile(Url::home() . 'app/js/clientes.js?d=201804010937', ['depends' => [JqueryAsset::className()]]);
$this->registerJsFile(Url::home() . 'app/js/mascaras.js', ['depends' => [JqueryAsset::className()]]);
$this->registerJsFile(Url::home() . 'plugins/accounting/accounting.js?d=201806271354', ['depends' => [JqueryAsset::className()]]);
?>          

