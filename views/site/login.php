<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary panel-box">
	<div class="panel-body">
        <h1><?= Html::encode($this->title) ?></h1>
    	<br/>
        <p>Favor preencher os campos a seguir para efetuar login:</p>
        <br/><br/>
        <?php $form = ActiveForm::begin([
                  'id' => 'login-form',
                  'layout' => 'horizontal',
                  'fieldConfig' => [
                      'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                      'labelOptions' => ['class' => 'col-lg-1 control-label'],
                  ],
              ]); 
        ?>
        	<div class="row">
        		<div class="col-md-12">
                    <div class="form-group">
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Usuario'); ?>
                        <?= $form->field($model, 'password')->passwordInput()->label('Senha'); ?>
                        <?= $form->field($model, 'rememberMe')->checkbox([
                                'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                            ])->label('Lembrar Login');
                        ?>
                    </div>
                </div>
   			</div>
   			<div class="row">
   				<div class="col-md-3 col-md-offset-1">
                    <div class="form-group"> 
                         <?= Html::submitButton('<i class="fa fa-arrow-right"></i>&nbsp; Login', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                    </div>
               	</div>
   			</div>
        <?php ActiveForm::end(); ?>
        <div class="help-block">
            <i class="fa fa-info-circle"></i>&nbsp; Login Padrão para o ambiente de desenvolvimento: <strong>admin/admin</strong> ou <strong>demo/demo</strong>.<br>
        </div>
    </div>
</div>
