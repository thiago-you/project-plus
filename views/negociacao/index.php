<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NegociacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Negociacaos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="negociacao-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Negociacao', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_contrato',
            'data_negociacao',
            'data_cadastro',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
