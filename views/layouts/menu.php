<?php
use yii\helpers\Url;
use kartik\typeahead\Typeahead;
use yii\web\View;
?>
<div class="collapse navbar-collapse" id="menu-navbar-collapse">
	<ul class="nav navbar-nav">
		<li><a href="<?= Url::to(['/contrato']); ?>"><i class="fa fa-file-invoice-dollar"></i>&nbsp; Contratos</a></li>
	</ul>
    <!-- ./menu -->
	<form class="navbar-form navbar-left">
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
	<!-- ./pesquisa -->
	<ul class="nav navbar-nav navbar-right">
		<li>
            <a data-method="post" href="<?= Url::to(['/site/logout']); ?>">
            	<i class="fa fa-power-off"></i>&nbsp; Logout
            </a>
        </li>
	</ul>
    <!-- ./logout -->
</div>
<!-- /.navbar-collapse -->
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
		
		
		