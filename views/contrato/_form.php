<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contrato */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contrato-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'codigo_cliente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_contrato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num_contrato')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'num_plano')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_cadastro')->textInput() ?>

    <?= $form->field($model, 'data_vencimento')->textInput() ?>

    <?= $form->field($model, 'tipo')->textInput() ?>

    <?= $form->field($model, 'regiao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'filial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacao')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
