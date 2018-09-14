<?php
use app\base\Helper;
use yii\helpers\Html;
use app\models\Cliente;
use kartik\grid\GridView;
use app\models\Contrato;

$this->title = 'Contratos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary panel-box">
	<div class="panel-body">
		<?= $this->render('_filter', [
                'model' => $searchModel,
            ]);                
        ?>
	</div>
	<!-- ./filtro -->
	<div class="panel-body">
		<?php $columns = [
                [
                    'attribute' => 'cliente.nome',
                    'width' => '30%',
                    'format' => 'raw',
                    'header' => '<span class="text-primary">Nome</span>',
                ],
                [
                    'attribute' => 'cliente.documento',
                    'format' => 'raw',
                    'header' => '<span class="text-primary">CPF/CNPJ</span>',
                    'value' => function($model) {
                        if ($model->cliente->documento) {                            
                            if ($model->cliente->tipo == Cliente::TIPO_FISICO) {
                                return Helper::mask($model->cliente->documento, Helper::MASK_CPF);
                            }
                            
                            return Helper::mask($model->cliente->documento, Helper::MASK_CNPJ);
                        }
                        
                        return 'Sem Documento';
                    }
                ],
                [
                    'attribute' => 'credor.nome',
                    'format' => 'raw',
                    'header' => '<span class="text-primary">Credor</span>',
                    'value' => function($modeç) {
                        return $model->id_credor ? $model->credor->nome : '';  
                    },
                ],
                [
                    'attribute' => 'codigo_contrato',
                    'label' => 'Contrato',
                    'value' => function($modeç) {
                    return $model->codigo_contrato ? $model->codigo_contrato : '';
                    },
                ],
                [
                    'attribute' => 'atraso',
                    'format' => 'raw',
                    'header' => '<span class="text-primary">Atraso</span>',
                    'value' => function($model) {
                        return '';   
                    }
                ],
                [
                    'attribute' => 'situacao',
                    'width' => '150px',
                    'label' => 'Estágio',
                    'value' => function($model) {
                        if ($model->situacao == Contrato::SIT_EM_ANDAMENTO) {
                            return 'Em Andamento';
                        }
                        
                        return 'Fechado';
                    }
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                	'hAlign'=> GridView::ALIGN_CENTER,
                	'template' => '{negociacao}{update}{delete}',
                    'width' => '150px',
                	'header' => '',
                	'buttons' => [
    	                'negociacao' => function ($url, $model) {
                            return Html::a('<i class="fa fa-cog"></i>', ['/cliente/negociacao/', 'id' => $model->cliente->id], [
                                'class' => Helper::BTN_COLOR_PURPLE,
                                'title' => 'Negociação',
                                'data-toggle' => 'tooltip',
        	                ]);
    	                },
    	                'update' => function ($url, $model) {
                			return Html::a('<i class="fa fa-pencil-alt"></i>', $url, [
                				'class' => Helper::BTN_COLOR_WARNING,
                				'title' => 'Alterar',
                				'data-toggle' => 'tooltip',
                			]);
                		},
                		'delete' => function ($url, $model) {
                			return Html::a('<i class="fa fa-trash-alt"></i>', $url, [
                				'class' => Helper::BTN_COLOR_DANGER,
                				'title' => 'Excluír',
                				'data-toggle' => 'tooltip',
                				'data-method' => 'post',
                			]);
                		},
                	],
                ],
            ];
		
		    // grid
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $columns,
                /* 'pjax' => true,
                'pjaxSettings' => [
                    'options' => [
                        'id' => 'grid-contratos',
                        'enablePushState'=>false
                    ],
                ], */
    		    'toolbar' => [
    		        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Contrato', ['create'], ['class' => Helper::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Novo Contrato', 'data-toggle' => 'tooltip',])],
    		        ['content'=> Html::a('<i class="fa fa-undo"></i>', ['index'], ['id' => '_LimparFiltro', 'class' => Helper::BTN_COLOR_DEFAULT, 'data-toggle' => 'tooltip', 'title' => 'Limpar Filtros'])],
    		        '{toggleData}',
    		    ],
    		    'bordered' => true,
    		    'striped' => true,
    		    'condensed' => true,
    		    'responsive' => true,
    		    'hover' => true,
    		    'showPageSummary' => false,
    		    'persistResize' => false,
            ]);
       ?>
    </div>
</div>
