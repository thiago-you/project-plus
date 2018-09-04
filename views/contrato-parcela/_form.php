<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContratoParcela */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contrato-parcela-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_contrato')->textInput() ?>

    <?= $form->field($model, 'data_cadastro')->textInput() ?>

    <?= $form->field($model, 'data_vencimento')->textInput() ?>

    <?= $form->field($model, 'valor')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
