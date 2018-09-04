<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TelefoneSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="telefone-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'numero') ?>

    <?= $form->field($model, 'ramal') ?>

    <?= $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'observacao') ?>

    <?php // echo $form->field($model, 'contato') ?>

    <?php // echo $form->field($model, 'whatsapp') ?>

    <?php // echo $form->field($model, 'ativo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
