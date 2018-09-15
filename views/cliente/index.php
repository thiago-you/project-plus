<?php
use app\base\Helper;
use yii\helpers\Html;
use app\models\Cliente;
use kartik\grid\GridView;

$this->title = 'Clientes';
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
                    'attribute' => 'id',
                    'hAlign' => GridView::ALIGN_CENTER,
                ],
                [
                    'attribute' => 'nome',
                    'width' => '40%'
                ],
				[
					'attribute' => 'documento',
					'label' => 'CPF/CNPJ',
					'value' => function($model) {
						if ($model->tipo == Cliente::TIPO_FISICO) {
							return Helper::mask($model->documento, Helper::MASK_CPF);
						}
						
						return Helper::mask($model->documento, Helper::MASK_CNPJ);
					}
				],
                [
                    'class' => 'kartik\grid\ActionColumn',
                	'hAlign'=> GridView::ALIGN_CENTER,
                	'template' => '{contrato}{update}{delete}',
                    'width' => '15%',
                	'header' => '',
                	'buttons' => [
    	                'contrato' => function ($url, $model) {
                            return Html::a('<i class="fa fa-file-invoice-dollar"></i>', $url, [
                                'class' => Helper::BTN_COLOR_PURPLE,
                                'title' => 'Contratos do Cliente',
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
		
            // sort desc
            $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);
		
		    // grid
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $columns,
                /* 'pjax' => true,
                'pjaxSettings' => [
                    'options' => [
                        'id' => 'grid-clientes',
                        'enablePushState'=>false
                    ],
                ], */
    		    'toolbar' => [
    		        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Cliente', ['create'], ['class' => Helper::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Novo Cliente', 'data-toggle' => 'tooltip',])],
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
