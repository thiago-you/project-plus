<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CredorCampanhaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Credor Campanhas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="credor-campanha-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Credor Campanha', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_credor',
            'nome',
            'vigencia_inicial',
            'vigencia_final',
            //'prioridade',
            //'por_parcela',
            //'por_portal',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
