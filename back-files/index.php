<?php
use app\base\Util;
use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use app\models\Clientes;
use kartik\grid\GridView;
use kartik\select2\Select2;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Default box -->
<div id='dv_grid' class="box">
	<div class="box-body">
        <?php
            $gridColumns =  [
                [
                    'class' => '\kartik\grid\ExpandRowColumn',
                    'enableRowClick' => true,
                    'expandIcon' => '<i class="fa fa-plus"></i>',
                    'collapseIcon' => '<i class="fa fa-minus"></i>',
                    'width' => '5%',
                    'expandOneOnly' => true,
                    'allowBatchToggle' => false,
                    'detailAnimationDuration' => 'fast',
                    'detailRowCssClass' => GridView::TYPE_ACTIVE,
                    'value' => function ($model, $key, $index) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function($model) {
                        $labelApelido = 'Apelido';
                        if ($model->tipo == Clientes::TIPO_PESSOA_JURIDICA) {
                            $labelApelido == 'Nome Fantasia';
                        }

                        return '
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12"> 
                                    <b>Endereço: </b>'.$model->endereco_completo.'
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>'. $labelApelido .': </b>'. $model->apelido .'<br>
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>Data de Nascimento: </b>'.($model->dtanascto ? date('d/m/Y', strtotime($model->dtanascto)) : '').'<br>
                                </div>                            
                            </div>
                            <div class="row">                            
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>Telefone: </b>'.Util::maskBackend($model->fone, Util::MASK_TELEFONE).'<br>
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>Celular: </b>'.Util::maskBackend($model->fone_celular, Util::MASK_TELEFONE).'<br>
                                </div>
                            </div>
                            <div class="row">                            
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>Pessoa p/ Contato: </b>'.$model->nome_contato.'<br>
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>Telefone p/ Contato: </b>'.Util::maskBackend($model->fone_contato, Util::MASK_TELEFONE).'<br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>Email: </b>'.$model->email.'<br>
                                </div>
                                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
                                    <b>Email Alternativo 1: </b>'.$model->email_alt_1.'<br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                                    <b>Observação: </b>'.$model->observacao.'<br>
                                </div>
                            </div>
                        ';
                    },
                ],
                [
                    'attribute' => 'id_cliente',
                    'hAlign' => 'center',
                    'label' => 'Cód.',
                    'contentOptions' => ['style' => 'min-width: 50px;',],
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
                                    'url' => \yii\helpers\Url::to(['clientes/search-list']) . '?q[id_cliente]=%QUERY',
                                    'wildcard' => '%QUERY'
                                ],
                                'templates' => [
                                    'suggestion' => new JsExpression("Handlebars.compile('<p style=\"white-space: normal; word-wrap: break-word;\">{{value}}</p>')"),
                                ],
                            ],
                        ]
                    ]
                ],
                [
        		    'attribute' => 'nome',
        		    'hAlign' => 'left',
        		    'vAlign' => 'middle',
                    'contentOptions' => ['style' => 'min-width: 200px;',],
        	        'value' => function ($model){

        	            $abrevia = $model->nome;
            		    if(strlen($model->nome) > 30)
            		        $abrevia  = Util::shortName($model->nome, 30);

        		        return $abrevia;
        	        },
        	        'filterType' => GridView::FILTER_TYPEAHEAD,
        	        'filterWidgetOptions' => [
        	            'options' => ['placeholder' => 'Filtrar por nome ...'],
        	            'pluginOptions' => [
        	                'highlight' => true
        	            ],
        	            'dataset' => [
        	                [
        	                    'display' => 'value',
        	                    'remote' => [
        	                        'url' => \yii\helpers\Url::to(['clientes/search-list']) . '?q[nome]=%QUERY',
        	                        'wildcard' => '%QUERY'
        	                    ],
        	                    'templates' => [
        	                        'suggestion' => new JsExpression("Handlebars.compile('<p style=\"white-space: normal; word-wrap: break-word;\">{{value}}</p>')"),
        	                    ],
        	                ],
        	            ]
        	        ]
        	    ],
                [
                    'attribute' => 'tipo',
                    'label' => 'CPF/CNPJ',
                    'hAlign' => 'left',
                    'vAlign' => 'middle',
                    'contentOptions' => ['style' => 'min-width: 140px;',],
                    'value' => function ($model) {
                        $docto = Util::maskBackend($model->cnpj, Util::MASK_CNPJ);
                	    if ($model->tipo == '1')
                		    $docto = Util::maskBackend($model->cpf, Util::MASK_CPF);

                	    return $docto;
                    },
                    'filter'=>false
                ],
                [
                    'attribute' => 'limite_credito',
                    'label' => 'Limite de Crétido',
                    'vAlign' => 'middle',
                    'hAlign' => 'right',
                    'contentOptions' => ['style' => 'min-width: 120px;',],
                    'format' => 'currency',
                    'filterType'=>GridView::FILTER_MONEY,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>[
                            'prefix' => 'R$ ',
                            'allowZero' => true,
                            'allowNegative'=>false
                        ]
                    ]
                ],
                [
                    'attribute' => 'divida_atual',
                    'header' => '<span class="text-primary">Dívida Atual</span>',
                    'vAlign' => 'middle',
                    'hAlign' => 'right',
                    'contentOptions' => ['style' => 'min-width: 120px;',],
                    'format' => 'currency',
                    'filterType'=>GridView::FILTER_MONEY,
                    'filterWidgetOptions'=>[
                        'pluginOptions'=>[
                            'prefix' => 'R$ ',
                            'allowZero' => true,
                            'allowNegative'=>false
                        ]
                    ]
                ],
                [
                    'attribute' => 'vlr_disponivel',
                    'header' => '<span class="text-primary">Valor Disponível</span>',
                    'vAlign' => 'middle',
                    'hAlign' => 'right',
                    'contentOptions' => ['style' => 'min-width: 120px;',],
                    'format' => 'currency',
                    'filterType'=>GridView::FILTER_MONEY,
					'filterWidgetOptions'=>[
                        'pluginOptions'=>[
                            'prefix' => 'R$ ',
                            'allowZero' => true,
                            'allowNegative'=>false
                        ]
                    ]
                ],
                [
                    'attribute' => 'situacao',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'min-width: 90px;',],
                    'value' => function ($model) {

                        $situacao = '<small class="label label-success">&nbsp;&nbsp; Selecione &nbsp;&nbsp;</small>';
                        if ($model->situacao == '1') $situacao = '<small class="label label-success">&nbsp;&nbsp; Normal &nbsp;&nbsp;</small>';
                        else if ($model->situacao == '2') $situacao = '<small class="label label-warning">Bloqueada</small>';
                        else if ($model->situacao == '3') $situacao = '<small class="label label-danger">&nbsp;&nbsp; Inativa &nbsp;&nbsp;</small>';

                        return $situacao;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['placeholder' => 'Selecione'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'data' => [Clientes::SITUACAO_FINANCEIRA_NORMAL => 'NORMAL', Clientes::SITUACAO_FINANCEIRA_BLOQUEADA => 'BLOQUEADA', Clientes::SITUACAO_FINANCEIRA_INATIVA => 'INATIVA'],
                    ],
                ],
                [
                    'attribute' => 'ativo',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'max-width: 20px;',],
                    'value' => function($data){
                        $html = '<span class="label label-danger"><i class="fa fa-ban"></i></span>';
                        if ($data->ativo == Clientes::CLIENTE_ATIVO)
                            $html = '<span class="label label-success"><i class="fa fa-check"></i></span>';

                        return $html;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'options' => ['placeholder' => '...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'data' => [Clientes::CLIENTE_ATIVO => 'ATIVO', Clientes::CLIENTE_INATIVO => 'INATIVO'],
                    ],
                ],
                [
        			'class' => 'kartik\grid\ActionColumn',
        			'contentOptions' => ['style' => 'min-width: 90px;',],
    	            'header' => '',
                    'hAlign' => 'center',
        			'template' => '{update}{delete}',
        			'buttons' => [
        				'update' => function ($url, $model) {
        				    return Html::a('<i class="fa fa-pencil-alt"></i>', ['/clientes/update/' ,'id'=>$model->id_cliente], [
			                    'class' => 'btn btn-sm btn-flat btn-warning',
        				        'title' => 'Alterar',
        				        'data-toggle' => 'tooltip',
        					]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="fa fa-trash-alt"></i>', $url, [
                                'class' => 'btn btn-sm btn-danger btn-flat btnDeletaLista',
                                'title' => 'Excluír',
                                'data-toggle' => 'tooltip',
                                'data-method' => 'post',
                            ]);
                        }
    			    ]
                ],
            ];

            $dataProvider->setSort(['defaultOrder' => ['id_cliente' => 'DESC']]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumns,
                'filterModel' => $searchModel,
                'pjax' => true,
        	    'pjaxSettings' => [
                    'options' => [
                        'id' => 'grid-clientes',
                        'enablePushState'=>false
                    ],
                ],
                'toolbar' => [
                    ['content' => Html::a('<i class="fa fa-plus"></i> Cliente', ['create'], ['class' => Util::BTN_CREATE_CLASS, 'title' => 'Cadastrar Novo Cliente'])],
                    ['content' => Html::button('<i class="fa fa-file-pdf"></i> Relatório', ['id' => 'btnRelatorio', 'class' => Util::BTN_COLOR_DANGER, 'data-toggle' => 'tooltip', 'title' => 'Gerar Relatório de Clientes'])],
                    ['content'=> Html::a('<i class="fa fa-undo"></i>', ['index'], ['id' => '_LimparFiltro', 'class' => Util::BTN_COLOR_DEFAULT, 'data-toggle' => 'tooltip', 'title' => 'Limpar Filtros'])],
                    '{toggleData}',
                ],
                'bordered' => true,
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'hover' => true,
                'showPageSummary' => false,
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading'=> '<i class="fa fa-plus"></i>&nbsp; - Exíbir detalhes adicionais sobre o cliente',
                ],
                'persistResize' => false,
            ]);
        ?>
     </div>
</div>
<?php 
Modal::begin([
    'header' => '<h4 class="modal-title text-primary"><i class="fa fa-list"></i> Relatório de Clientes</h4>',
    'id' => 'modal-relatorio',
    'size' => Modal::SIZE_SMALL,
    'options' => [
        'tabindex'=>false,
        'class' => 'body-no-padding',
    ],
]);?>
<div class="box box-default">
	<div class="box-header">
		<h4><strong class="text-info"><i class="fa fa-filter"></i> Opções de Filtro</strong></h4>	
	</div>
	<div class="box-body">
		<div class="col-md-12">
			<label for="clientes-ativo">Filtrar por Situação</label>
			<?= Select2::widget([
			    'name' => 'Clientes[ativo]',
			    'id' => 'clientes-ativo',
			    'hideSearch' => true,
			    'options' => ['placeholder' => 'Selecione'],
			    'data' => [0 => 'INATIVO', 1 => 'ATIVO']
            ])?>
		</div>
	</div>
	<div class="box-footer">
		<?= Html::button('<i class="fa fa-file-pdf"></i> Gerar', [
            'class' => 'btn btn-danger pull-left',
		    'id' => 'btnGerar',
		    'title' => 'Clique aqui para gerar o relatório de clientes',
            'data-toggle' => 'tooltip',
        ])?>
        <?= Html::button('<i class="fa fa-reply-all"></i>', ['id' => 'btnCancelar', 'class' => 'btn btn-default pull-right'])?>
	</div>
</div>
<?php Modal::end();?>

<?php
$formatJs = <<< 'JS'
    //Abre a modal de realtório
    $('#btnRelatorio').on('click', function(){
        $('#modal-relatorio').modal('show');
    });
    //Fecha a modal de relatório    
    $('#btnCancelar').on('click', function(){
        $('#modal-relatorio').modal('hide');
    });    
    //Gera o pdf do relatório
    $('#btnGerar').on('click', function(){
        var url = BASE_PATH + 'clientes/relatorio';
        //Verifica se selecionou algum filtro
        if($('#clientes-ativo').val() != ''){
            url += '?filtros[ativo]=' + $('#clientes-ativo').val();
        }
        //Passa o ajax para geração do relatório
		toastr.info('<i class="fa fa-spinner fa-spin"></i> Gerando Relatório de Clientes');
        window.open(url, '_blank');
        location.reload();
    });
        
    $('body').on('click', '#filtro', function(e){
	   $('#caixa_filtro').toggle('fast');
	   return false;
    });
JS;

// Register the formatting script
$this->registerJs($formatJs, View::POS_READY);
?>
