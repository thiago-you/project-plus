<?php
use yii\helpers\Url;
use kartik\typeahead\Typeahead;
use yii\web\View;
use yii\web\JsExpression;
use app\models\User;
?>
<div class="nav toggle">
	<a id="menu_toggle"><i class="fa fa-bars fw-fw fa-2x"></i></a>
</div>
<!-- ./toggle menu -->
<ul class="nav navbar-nav navbar-left">
	<li>
		<a href="<?= Url::to(['/contrato']); ?>"><i class="fa fa-file-invoice-dollar"></i>&nbsp; Contratos</a>
	</li>
    <li>
        <form class="navbar-form">
        	<div class="form-group">
        		<?= Typeahead::widget([
        				'id' => 'quick-search-nome',
        				'name' => 'nome',
        				'pluginOptions' => ['highlight' => true],
        				'options' => [
        					'placeholder' => 'Pesquisar contrato pelo nome ou CPF/CNPJ do cliente...',		
        					'style' => 'width: 450px;',
        					'autocomplete' => 'off',
        				],
        				'dataset' => [
        					[
        						'display'=> 'value',
        						'notFound' => '<span class="alert alert-danger"><i class="fa fa-ban"></i>&nbsp; Nehum cliente foi encontrado ...</div>',
        						'remote'=>[
        							'url' => Url::to(['contrato/search-list']).'?q[quick]=%QUERY',
        							'wildcard' => '%QUERY',
        						],
        		                'templates' => [
                                    'suggestion' => new JsExpression("Handlebars.compile('<p style=\"white-space: normal; word-wrap: break-word;\">{{value}}</p>')"),
        		                ],
        					],
        				],
        				'pluginEvents' => [
        					'typeahead:select' => 'function(event, data) { 
        						if (data.value != undefined && data.value.length > 0) {
        							window.location = BASE_PATH + "contrato/quick-search?value="+data.value+"&strict=true";	
        						}
        					}',
        				],
        			]);
        		?>
        	</div>
        </form>
    </li>
</ul>
<!-- ./menu -->
<!-- ./pesquisa -->
<ul class="nav navbar-nav navbar-right">
	<li>
        <a data-method="post" href="<?= Url::to(['/site/logout']); ?>">
        	<i class="fa fa-power-off"></i>&nbsp; Logout
        </a>
    </li>
	<li>
		<a href="<?= Url::to(['/colaborador/update', 'id' => \Yii::$app->user->identity->id]); ?>">
			<i class="fa fa-user"></i>&nbsp; 
			<?= User::findIdentity(\Yii::$app->user->identity->id)->nome; ?>
		</a>
	</li>
</ul>
<!-- ./logout -->
<?php 
$script = <<< JS
	$(document).ready(function() {
		$('body').on('keypress keydown keyup', '#quick-search-nome', function(e) {
			if (e.which == 13) {
				if (this.value != undefined && this.value.length > 0) {
					window.location = BASE_PATH + "contrato/quick-search?value="+this.value;
				}
			}
		});
	});
JS;
$this->registerJs($script, View::POS_LOAD);
?>		
		
		
		