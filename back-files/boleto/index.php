<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

use app\base\Util;
use app\models\Boleto;
use app\models\Clientes;
use kartik\grid\GridView;

$this->title = 'Boletos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">
	<div class="box-body">

        <?php
            $gridColumns = [
                [
                    'attribute' => 'id',
                    'width' => '5%',
                    'hAlign' => GridView::ALIGN_CENTER,
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
                                    'url' => Url::to(['boleto/search-list']).'?q[id]=%QUERY',
                                    'wildcard' => '%QUERY'
                                ],
                            ],
                        ]
                    ]
                ],
                [
                    'attribute'=>'cliente_id',
                    'width' => '20%',
                    'contentOptions' => ['style' => 'min-width:170px;'],
                	'filterType'=>GridView::FILTER_SELECT2,
                	'value' => function($model) { 
                	    return $model->cliente->nome; 
                	},
                	'filterWidgetOptions'=>[
                		'options' => [
                			'placeholder' => 'Selecione'
                		],
                		'pluginOptions' => [
                			'allowClear' => false
                		],
                		'data'=> ArrayHelper::map(Clientes::find()->all(), 'id_cliente', 'nome'),
                	]
                ],
                [
                    'attribute' => 'data_emissao',
                    'width' => '5%',
                    'hAlign' => GridView::ALIGN_CENTER,
                	'filterType' => GridView::FILTER_DATE,
                    'filterOptions' => [
                        'class' => 'text-center',
                    ],
                    'filterWidgetOptions' => [
                        'type' => 6,
                        'buttonOptions' => [
                            'class' => Util::BTN_COLOR_DEFAULT,
                        ],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy'
                        ]
                	],
                ],
                [
                    'attribute' => 'data_vencimento',
                    'width' => '5%',
                    'hAlign' => GridView::ALIGN_CENTER,
                	'filterType' => GridView::FILTER_DATE,
                    'filterOptions' => [
                        'class' => 'text-center',
                    ],
                    'filterWidgetOptions' => [
                        'type' => 6,
                        'buttonOptions' => [
                            'class' => Util::BTN_COLOR_DEFAULT,
                        ],
                		'pluginOptions' => [
                			'allowClear' => false,
                		    'format' => 'dd-mm-yyyy'
                		],
                	],
                ],
                [
                    'attribute' => 'valor',
                    'width' => '12%',
                    'hAlign' => GridView::ALIGN_RIGHT,
                    'format' => 'currency',
                	'filterType' => GridView::FILTER_MONEY,
                	'filterWidgetOptions'=>[
                	    'pluginOptions'=>[
                	        'prefix' => 'R$ ',
                	        'allowZero' => true,
                	        'allowNegative' => false
                	    ]
                	]
                ],
                [
                    'attribute' => 'nosso_numero',
                    'value' => function($model) {
                        return $model->nosso_numero ? $model->nosso_numero : '';
                    },
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
                                    'url' => Url::to(['boleto/search-list']).'?q[nosso_numero]=%QUERY',
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
                    'attribute' => 'situacao_fluxo',
                    'width' => '10%',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'format' => 'raw',
                    'value' => function($model) {
                        if($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API) {
                            return '<span class="label label-warning">&nbsp; PENDENTE &nbsp;</span>';
                        } elseif($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_GERADO_API) {
                            return '<span class="label label-primary">&nbsp;&nbsp;&nbsp;&nbsp; EMITIDO &nbsp;&nbsp;&nbsp;&nbsp;</span>';
                        } elseif($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_DE_REMESSA) {
                            return '<span class="label label-info">EM REMESSA</span>';
                        } elseif($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_RETORNO) {
                            return '<span class="label label-emerald">&nbsp;&nbsp;&nbsp; ENVIADO &nbsp;&nbsp;&nbsp;</span>';
                        } else {
                            return '<span class="label label-pink">INDEFINIDO</span>';
                        }
                            
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                    	'hideSearch' => true,
                    	'options' => [
                    		'placeholder' => 'Selecione',
                    	],
                    	'pluginOptions' => [
                    		'allowClear' => false,
                    	],
                    	'data'=> Boleto::situacoesFluxo()
                    ],
                ],
                [
                    'attribute' => 'situacao_pagamento',
                    'width' => '10%',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'format' => 'raw',
                    'value' => function($model) {
                        switch($model->situacao_pagamento) {
                            case Boleto::SITUACAO_PAGAMENTO_LIQUIDADO:
                                return '<span class="label label-success">LIQUIDADO</span>';
                                break;
                            case Boleto::SITUACAO_PAGAMENTO_ATRASADO:
                                return '<span class="label label-danger">ATRASADO</span>';
                                break;
                            case Boleto::SITUACAO_PAGAMENTO_PROTESTADO:
                                return '<span class="label label-danger">PROTESTADO</span>';
                                break;
                            case Boleto::SITUACAO_PAGAMENTO_PARCIAL:
                                return '<span class="label label-info">PARCIAL</span>';
                                break;
                            case Boleto::SITUACAO_PAGAMENTO_DEVOLVIDO:
                                return '<span class="label label-primary">DEVOLVIDO</span>';
                                break;
                            default:
                                return '<span class="label label-warning">PENDENTE</span>';
                                break;
                        }
                    },
                    'filterType'=>GridView::FILTER_SELECT2,
                    'filterWidgetOptions'=>[
                        'hideSearch' => true,
                        'options' => [
                            'placeholder' => 'Selecione',
                        ],
                        'pluginOptions' => [
                            'allowClear' => false,
                        ],
                        'data'=> Boleto::situacoesPagamento()
                    ],
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                	'template' => '{update}{email}{view}{detail}',
                    'header' => '',
                    'hAlign' => GridView::ALIGN_CENTER,
                    'width' => '15%',
                    'contentOptions' => ['style' => 'min-width: 150px;'],
                	'buttons' => [
                	    'update' => function($url, $model) {
                    	    if($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API) {
                    	        return Html::a('<i class="fa fa-fw fa-pencil-alt"></i>', ['/boleto/update' ,'id' => $model->id], [
                    	            'class' => Util::BTN_COLOR_WARNING.' btn-xs',
                    	            'style' => 'margin-left: 3px',
                    	            'title' => 'Atualizar',
                    	            'data-toggle' => 'tooltip',
                    	        ]);
                    	    }
                    	    
                	        return Html::button('<i class="fa fa-fw fa-pencil-alt"></i>', [
                	            'class' => Util::BTN_COLOR_WARNING.' btn-xs',
                    	        'style' => 'margin-left: 3px',
                    	        'readonly' => true,
                    		    'disabled' => true,
                    	    ]);                  	       
                	    },
                		'view' => function($url, $model) {
                            if($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API) {
                        		return Html::button('<i class="fa fa-fw fa-eye"></i>', [
                        		    'class' => Util::BTN_COLOR_INFO.' btn-xs',
                        			'style' => 'margin-left: 3px',
                                    'readonly' => true,
                        		    'disabled' => true,
                                ]);
                        	}

                        	return Html::a('<i class="fa fa-fw fa-eye"></i>', ['/boleto/view', 'id' => $model->id], [
                    		    'target' => '_blank',
                    		    'class' => Util::BTN_COLOR_INFO.' btn-xs',
                				'style' => 'margin-left: 3px',
                				'title' => 'Visualizar',
                				'data-toggle' => 'tooltip',
                        	    'onClick' => 'this.blur();',
                    		]);
                        },
                        'email' => function($url, $model) {
                            if($model->situacao_fluxo == Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API) {
                                return Html::button('<i class="fa fa-fw fa-paper-plane"></i>', [
                                    'class' => Util::BTN_COLOR_SUCCESS.' btn-xs',
                                    'disabled' => true,
                                    'readonly' => true,
                                    'style' => 'margin-left: 3px',
                                ]);
                            }
                            
                            return Html::a('<i class="fa fa-fw fa-paper-plane"></i>', $url, [
                                'class' => Util::BTN_COLOR_SUCCESS.' btn-xs btnModalEmail',
                                'style' => 'margin-left: 3px',
                                'id' => 'btnModal',
                                'data-id' => $model->id,
                                'title' => 'Enviar por E-mail',
                                'dhref' => 'boleto/email',
                                'data-toggle' => 'tooltip',
                            ]);
                        },
    					'detail' => function($url, $model) {
				    		return Html::button('<i class="fa fa-fw fa-list"></i>', [
				    		    'id' => $model->id,
								'class' => Util::BTN_COLOR_PRIMARY.' btn-xs btnDetail',
								'style' => 'margin-left: 3px',
								'title' => 'Detalhes do Boleto',
								'data-toggle'=>'tooltip',
				    		    'onClick' => 'this.blur();',
							]);
    					},
                	],
                ],
            ];

            // grid
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumns,
                'containerOptions' => ['style' => 'overflow: auto'], // only set when responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => false,
                'toolbar' => [
                    ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Gerar Boleto', ['create'], ['class' => Util::BTN_COLOR_SUCCESS, 'title' => 'Novo Boleto'])],
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
                    'heading' => '<i class="fa fa-list"></i>'
                ],
                'persistResize' => false,
            ]);
        ?>
    </div>
</div>

<div id="modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width:740px;text-align:center">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Fechar</button>
        <button type="button" class="btn btn-warning" id="imprimeIframe"><i class="fa fa-print"></i> Imprimir</button>
        <iframe id="modalFrame" name="modalFrame" style="width:100%;border:0;border-radius:5px;margin-top:20px"></iframe>
    </div>
</div>

<?php
    // Modal detalhes
    Modal::begin([
        'id' => 'modal-detail',
        'header' => '<h4 class="modal-title text-primary"><i class="fa fa-tags"></i>&nbsp; Boleto - Detalhes</h4>',
        'footer' => '<div class="row"><div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 pull-right"><button class="btn btn-block btn-default btn-flat" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Fechar</button></div></div>',
        'footerOptions' => [
            'style' => 'padding: 0px 10px 10px 10px;',
        ],
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'tabindex' => false,
            'class' => 'body-no-padding',
        ],
    ]);

        echo '
            <div class="modal-body">
                <br/>
                    <h2 class="text-center text-primary"><i class="fa fa-spinner fa-spin"></i>&nbsp; Carregando ...</h2>
                <br/><br/>
            </div>
        ';
    
    Modal::end();
    // Modal Novo Email
    Modal::begin([
        'header' => '<h4 class="modal-title text-primary"><i class="fa fa-pulse"></i>&nbsp; Enviar E-mail</h4>',
        'id' => 'modal-email',
        'size' => Modal::SIZE_LARGE,
        'closeButton' => false,
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
        'options' => [
            'tabindex' => false,
            'class' => 'body-no-padding',
        ],
    ]);
        echo '
            <div class="modal-body">
                <br/>
                    <h2 class="text-center text-primary"><i class="fa fa-spinner fa-pulse"></i>&nbsp; Carregando ...</h2>
                <br/><br/>
            </div>
        ';

    Modal::end();
// JS
$this->registerJsFile(Url::home() . 'app/js/boleto.js?d=201802141435', ['depends' => [JqueryAsset::className()]]);
$this->registerJsFile(Url::home() . 'app/js/email.js', ['depends' => [JqueryAsset::className()]]);
?>
