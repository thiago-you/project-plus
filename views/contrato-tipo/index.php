<?php
use app\base\Helper;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\ContratoTipo;

$this->title = 'Tipos de Contratos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-tipos-de-contratos">
	<?php $columns = [
            [
                'attribute' => 'id',
                'width' => '10%',
                'hAlign' => GridView::ALIGN_CENTER,
            ],
            [
                'attribute' => 'descricao',
            ],
			[
				'attribute' => 'ativo',
                'format' => 'raw',
                'hAlign' => GridView::ALIGN_CENTER,
                'value' => function($model) {
	                if ($model->ativo == ContratoTipo::ATIVO) {
	                    return '<span class="label label-success">&nbsp; ATIVO &nbsp;</span>';
	                }
	                
	                return '<span class="label label-warning">INATIVO</span>';
                },
			],
            [
                'class' => 'kartik\grid\ActionColumn',
            	'hAlign'=> GridView::ALIGN_CENTER,
            	'template' => '{update}{delete}',
                'width' => '15%',
            	'header' => '',
            	'buttons' => [
	                'update' => function ($url, $model) {
            			return Html::a('<i class="fa fa-pencil-alt"></i>', $url, [
            				'class' => Helper::BTN_COLOR_WARNING,
            				'title' => 'Alterar',
            				'data-toggle' => 'tooltip',
            			]);
            		},
            		'delete' => function ($url, $model) {
            			return Html::a('<i class="fa fa-trash-alt"></i>', $url, [
            				'class' => Helper::BTN_COLOR_DANGER. ' btn-delete',
            				'title' => 'Excluír',
            				'data-toggle' => 'tooltip',
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
            'columns' => $columns,
		    'toolbar' => [
		        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Tipo de Contrato', ['create'], ['class' => Helper::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Novo Tipo', 'data-toggle' => 'tooltip',])],
		        //['content'=> Html::a('<i class="fa fa-undo"></i>', ['index'], ['id' => '_LimparFiltro', 'class' => Helper::BTN_COLOR_DEFAULT, 'data-toggle' => 'tooltip', 'title' => 'Limpar Filtros'])],
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