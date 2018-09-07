<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Acionamento */

$this->title = 'Create Acionamento';
$this->params['breadcrumbs'][] = ['label' => 'Acionamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acionamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
