<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

use app\base\Util;
use app\models\Cargo;
use kartik\grid\GridView;
use kartik\select2\Select2;

$this->title = 'Colaboradores';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Default box -->
<div class="box">
	<div class="box-body">
        <?php
            Modal::begin([
                'header' => '<h4 class="modal-title text-primary">Colaborador</h4>',
                'id' => 'modal',
                'size' => Modal::SIZE_LARGE,
                'options' => [
                    'class' => 'body-no-padding',
                ],
            ]);
                echo "<div id='modalContent'></div>";
            Modal::end();
        ?>
  		<?php
            $gridColumns = [
                [
                    'attribute' => 'id',
        	        'hAlign' => 'center',
                    'width' => '5%',
                    'filterType' => GridView::FILTER_TYPEAHEAD,
                    'filterWidgetOptions' => [
                        'options' => ['placeholder' => '...'],
                        'pluginOptions' => [
                            'highlight' => true
                        ],
                        'dataset' => [
                            [
                                'display' => 'value',
                                'remote' => [
                                    'url' => Url::to(['colaborador/search-list']).'?q[id]=%QUERY',
                                    'wildcard' => '%QUERY'
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'attribute' => 'nome',
                    'width' => '20%',
                    'filterType' => GridView::FILTER_TYPEAHEAD,
                    'filterWidgetOptions' => [
                        'options' => ['placeholder' => 'Selecione'],
                        'pluginOptions' => [
                            'highlight' => true
                        ],
                        'dataset' => [
                           [
                               'display' => 'value',
                               'remote' => [
                                   'url' => Url::to(['colaborador/search-list']).'?q[nome]=%QUERY',
                                   'wildcard' => '%QUERY'
                                ],
                               'templates' => [
                                   'suggestion' => new JsExpression("Handlebars.compile('<p style=\"white-space: normal; word-wrap: break-word;\">{{value}}</p>')"),
                               ],
                            ],
                        ],
                    ],
                ],
                [
                    'attribute' => 'cargo_id',
                    'value' => 'cargo.cargo',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['placeholder' => 'Selecione'],
                        'pluginOptions' => [
                            'allowClear' => false,
                        ],
                        'data' => ArrayHelper::map(Cargo::find()->all(), 'id', 'cargo'),
                    ],
                ],
                [
                    'attribute' => 'email_pessoal',
                    'filterType' => GridView::FILTER_TYPEAHEAD,
                    'filterWidgetOptions' => [
                        'options' => ['placeholder' => 'Selecione'],
                        'pluginOptions' => [
                            'highlight' => true,
                        ],
                        'dataset' => [
                            [
                                'display' => 'value',
                                'remote' => [
                                    'url' => Url::to(['colaborador/search-list']).'?q[email_pessoal]=%QUERY',
                                    'wildcard' => '%QUERY',
                                ],
                                'templates' => [
                                    'suggestion' => new JsExpression("Handlebars.compile('<p style=\"white-space: normal; word-wrap: break-word;\">{{value}}</p>')"),
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'attribute' => 'fone1',
                    'label' => 'Telefone',
                    'value' => function($data) {
                        return Util::maskBackend($data->fone1, Util::MASK_TELEFONE);
                    },
                    'filter' => false,
                ],
                [
                    'attribute' => 'ativo',
                	'format' => 'raw',
                    'hAlign' => 'center',
                    'width' => '10%',
                	'value' => function($data) {
                        return $data->ativo == 1 
                        ? '<small class="label label-success">&nbsp; Sim &nbsp;</span>' 
                        : '<small class="label label-danger">&nbsp; Não &nbsp;</span>';
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['placeholder' => '...'],
                        'hideSearch' => true,
                        'pluginOptions' => [
                            'allowClear' => false,
                        ],
                        'data' => [0 => 'Não', 1 => 'Sim'],
                    ],
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => ' ',
                    'contentOptions' => ['style' => 'min-width:90px'],
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<i class="fa fa-pencil-alt"></i>', ['/colaborador/update' ,'id'=>$model->id], [
                                'title' => 'Alterar',
                                'data-toggle' => 'tooltip',
                                'class' => Util::BTN_COLOR_WARNING.' btn-sm',
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="fa fa-trash-alt"></i>', $url, [
                                'class' => Util::BTN_COLOR_DANGER.' btnDeletaLista btn-sm',
                                'title' => 'Deletar',
                                'data-toggle' => 'tooltip',
                                'data-method' => 'post',
                            ]);
                        },
                    ],
                ],
            ];
        
            // sort
            $dataProvider->setSort(['defaultOrder' => ['id' => 'DESC']]);
        
            // grid
        	echo GridView::widget([
        		'dataProvider' => $dataProvider,
        		'columns' => $gridColumns,
        	    'filterModel' => $searchModel,
        		'containerOptions' => ['style' => 'overflow: auto'],
        		'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        		'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        		'pjax'=>true,
        	    'pjaxSettings' => [
        	        'options' => [
        	            'id' => 'grid-colaborador',
        	            'enablePushState'=>false
        	        ],
        	    ],
        		'toolbar' => [
        			['content' => Html::a('<i class="fa fa-plus"></i> Colaborador', ['create'], ['class' => Util::BTN_CREATE_CLASS, 'title' => 'Cadastrar Novo Colaborador', 'data-toggle' => 'tooltip'])],
        			['content' => Html::button('<i class="fa fa-file-pdf"></i> Relatório', ['id' => 'btnRelatorio', 'class' => Util::BTN_COLOR_PURPLE, 'data-toggle' => 'modal', 'data-target' => '#modal-relatorio', 'title' => 'Gerar Relatório de Colaboradores'])],
        		    ['content' => Html::a('<i class="fa fa-undo"></i>', ['index'], ['id' => '_LimparFiltro', 'class' => Util::BTN_COLOR_DEFAULT, 'data-toggle' => 'tooltip', 'title' => 'Limpar Filtros'])],
        			'{toggleData}',
        		],
        		'bordered' => true,
        		'striped' => true,
        		'condensed' => true,
        		'responsive' => false,
        		'hover' => true,
        		'panel' => [
        			'type' => GridView::TYPE_PRIMARY,
        		],
        		'persistResize' => false,
        	]);
        ?>
	</div>
	<!-- ./box-body -->
</div>
<!-- ./box -->
<?php 
    Modal::begin([
        'header' => '<h4 class="modal-title text-primary"><i class="fa fa-list"></i>&nbsp; Relatório de Colaboradores</h4>',
        'id' => 'modal-relatorio',
        'size' => Modal::SIZE_DEFAULT,
        'options' => [
            'tabindex' => false,
        ],
    ]);
?>
    <div class="box box-default">
    	<div class="box-header">
    		<h3 class="font16">
    			<b class="text-info"><i class="fa fa-filter"></i>&nbsp; Opções de Filtro</b>
			</h3>	
    	</div>
    	<div class="box-body">
    		<div class="row">
        		<div class="col-md-12">
        			<label for="clientes-ativo">Filtrar por Situação</label>
        			<?= Select2::widget([
            			    'name' => 'Colaborador[ativo]',
            			    'id' => 'colaborador-ativo',
            			    'hideSearch' => true,
            			    'options' => ['placeholder' => 'Selecione'],
            			    'data' => [0 => 'INATIVO', 1 => 'ATIVO']
                        ]);
        			?>
        		</div>
    		</div>
    	</div>
    	<!-- ./box-body -->
    	<div class="box-footer">
    		<div class="row">
        		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
            		<?= Html::button('<i class="fa fa-file-pdf"></i>&nbsp; Gerar', [
            		        'class' => Util::BTN_COLOR_PURPLE.' btn-block',
                		    'id' => 'btnGerar',
                		    'title' => 'Gerar Relatório',
                            'data-toggle' => 'tooltip',
                        ]);
            		?>
        		</div>
        		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 pull-right">
                    <?= Html::button('<i class="fa fa-times"></i>&nbsp; Cancelar', [
                            'data-dismiss' => 'modal',
                            'class' => Util::BTN_COLOR_DEFAULT.' btn-block'
                        ]);
                    ?>
                </div>
            </div>
    	</div>
    	<!-- ./box-footer -->
    </div>
    <!-- ./box -->
<?php Modal::end();?>
<!-- ./modal -->
<?php
$js = <<< 'JS'
    $(document).ready(function() {
        // insere o tooltip
        $('#btnRelatorio').tooltip();

        // gera o pdf do relatório
        $('body').on('click', '#btnGerar', function() {
            let url = BASE_PATH+'colaborador/relatorio';
            
            // verifica se selecionou algum filtro
            if ($('#colaborador-ativo').val() != '') {
                url += '?filtros[ativo]=' + $('#colaborador-ativo').val();
            }

            // passa o ajax para geração do relatório
    		toastr.info('<i class="fa fa-spinner fa-pulse"></i>&nbsp; Gerando Relatório de Clientes');
            window.open(url, '_blank');
            location.reload();
        });
    });
JS;
$this->registerJs($js, View::POS_READY);
?>


