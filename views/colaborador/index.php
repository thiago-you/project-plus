<?php
use app\base\Util;
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Colaboradores';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $columns = [
        [
            'attribute' => 'id',
            'hAlign' => GridView::ALIGN_CENTER,
            'width' => '10%',
        ],
        [
            'attribute' => 'nome',
            'width' => '40%'
        ],
        [
            'attribute' => 'cargo',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
        	'hAlign'=> GridView::ALIGN_CENTER,
        	'template' => '{update}{delete}',
        	'header' => '',
        	'buttons' => [
        		'update' => function ($url, $model) {
        			return Html::a('<i class="fa fa-pencil-alt"></i>', $url, [
        				'class' => Util::BTN_COLOR_WARNING,
        				'title' => 'Alterar',
        				'data-toggle' => 'tooltip',
        			]);
        		},
        		'delete' => function ($url, $model) {
        			return Html::a('<i class="fa fa-trash-alt"></i>', $url, [
        				'class' => Util::BTN_COLOR_DANGER,
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
                'id' => 'grid-colaborador',
                'enablePushState'=>false
            ],
        ], */
	    'toolbar' => [
	        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Colaborador', ['create'], ['class' => Util::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Novo Colaborador', 'data-toggle' => 'tooltip',])],
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

       
       
       