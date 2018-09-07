<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Acionamento */

$this->title = 'Update Acionamento: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Acionamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="acionamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
