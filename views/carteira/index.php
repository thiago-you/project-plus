<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use app\base\Helper;
use app\models\Carteira;

$this->title = 'Carteiraes';
$this->params['breadcrumbs'][] = $this->title;

// columns
$columns = [
    [
        'attribute' => 'id',
        'hAlign' => GridView::ALIGN_CENTER,
    ],
    [
        'attribute' => 'nome',
        'width' => '40%'
    ],
	[
		'attribute' => 'tipo',
	],
    [
        'attribute' => 'ativo',
        'hAlign' => GridView::ALIGN_CENTER,
    	'value' => function($model) {
	    	if ($model->ativo == Carteira::ATIVO) {
	    		return 'SIM';
	    	}
	    	
	    	return 'NÃO';
    	}
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
    	'hAlign'=> GridView::ALIGN_CENTER,
    	'template' => '{configuracao}{update}{delete}',
    	'header' => '',
        'width' => '10%',
    	'buttons' => [
            'configuracao' => function ($url, $model) {
                return Html::a('<i class="fa fa-cogs fa-fw"></i>', ['/carteira/configuracao/', 'id' => $model->id], [
                    'class' => Helper::BTN_COLOR_PURPLE.' btn-xs',
                    'title' => 'Configurar Cálculo',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $model) {
    			return Html::a('<i class="fa fa-pencil-alt fa-fw"></i>', ['/carteira/update/', 'id' => $model->id], [
	                'class' => Helper::BTN_COLOR_WARNING.' btn-xs',
    				'title' => 'Alterar',
    				'data-toggle' => 'tooltip',
    			]);
    		},
    		'delete' => function ($url, $model) {
    			return Html::a('<i class="fa fa-trash-alt fa-fw"></i>', $url, [
	                'class' => Helper::BTN_COLOR_DANGER.' btn-xs',
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
        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Carteira', ['create'], ['class' => Helper::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Nova Carteira', 'data-toggle' => 'tooltip',])],
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

