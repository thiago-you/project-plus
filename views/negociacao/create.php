<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Negociacao */

$this->title = 'Create Negociacao';
$this->params['breadcrumbs'][] = ['label' => 'Negociacaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="negociacao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
