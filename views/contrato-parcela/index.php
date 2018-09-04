<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContratoParcelaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contrato Parcelas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contrato-parcela-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Contrato Parcela', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_contrato',
            'data_cadastro',
            'data_vencimento',
            'valor',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
