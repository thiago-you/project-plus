<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContratoParcela */

$this->title = 'Update Contrato Parcela: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contrato Parcelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contrato-parcela-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
