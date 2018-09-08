<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use app\base\Util;
use app\models\Credor;

$this->title = 'Credores';
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
	    	if ($model->ativo == Credor::ATIVO) {
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
                return Html::a('<i class="fa fa-cogs fa-fw"></i>', ['/credor/configuracao/', 'id' => $model->id], [
                    'class' => Util::BTN_COLOR_PURPLE.' btn-xs',
                    'title' => 'Configurar Cálculo',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'update' => function ($url, $model) {
    			return Html::a('<i class="fa fa-pencil-alt fa-fw"></i>', ['/credor/update/', 'id' => $model->id], [
	                'class' => Util::BTN_COLOR_WARNING.' btn-xs',
    				'title' => 'Alterar',
    				'data-toggle' => 'tooltip',
    			]);
    		},
    		'delete' => function ($url, $model) {
    			return Html::a('<i class="fa fa-trash-alt fa-fw"></i>', $url, [
	                'class' => Util::BTN_COLOR_DANGER.' btn-xs',
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
            'id' => 'grid-clientes',
            'enablePushState'=>false
        ],
    ], */
    'toolbar' => [
        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Credor', ['create'], ['class' => Util::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Novo Credor', 'data-toggle' => 'tooltip',])],
        ['content'=> Html::a('<i class="fa fa-undo"></i>', ['index'], ['id' => '_LimparFiltro', 'class' => Util::BTN_COLOR_DEFAULT, 'data-toggle' => 'tooltip', 'title' => 'Limpar Filtros'])],
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

