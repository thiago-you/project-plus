<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CredorCalculo */

$this->title = 'Update Credor Calculo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Credor Calculos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="credor-calculo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
