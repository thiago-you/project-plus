<?php
use yii\helpers\Url;
?>
<div class="sidenav-header">
	<img src="<?= \Yii::$app->request->baseUrl; ?>/img/logo.png"/>
    <a class="navbar-brand" href="<?= \Yii::$app->homeUrl; ?>">
    	Maklen <i>RC</i>
    </a>
</div>
<!-- ./brand -->
<div class="sidenav-content">
	<ul>
		<li class="section-item">
        	<a href="#" class="item"><i class="fa fa-file-invoice-dollar fa-fw"></i>&nbsp; Contrato<i class="fa fa-chevron-down fa-fw"></i></a>
        	<ul class="child-menu">
        		<li>
        			<a href="<?= Url::to(['/contrato']); ?>">Contratos</a>
        		</li>
        		<li>
        			<a href="<?= Url::to(['/contrato/create']); ?>">Novo Contrato</a>
        		</li>
        		<li>
        			<a href="<?= Url::to(['/contrato-tipo']); ?>">Tipos</a>
        		</li>
        	</ul>
		</li>
		<li>
        	<a href="<?= Url::to(['/cliente']); ?>" class="item"><i class="fa fa-users fa-fw"></i>&nbsp; Cliente</a>
		</li>
		<li>
	    	<a href="<?= Url::to(['/colaborador']); ?>" class="item"><i class="fa fa-user fa-fw"></i>&nbsp; Colaborador</a>
		</li>
		<li>
        	<a href="<?= Url::to(['/carteira']); ?>" class="item"><i class="fa fa-university fa-fw"></i>&nbsp; Carteira</a>
		</li>
		<li>
        	<a href="<?= Url::to(['/site/importacao']); ?>" class="item"><i class="fa fa-file-upload fa-fw"></i>&nbsp; Importação</a>
		</li>
	</ul>
</div>
<!-- ./conteudo do side nav -->
<footer>
	<p class="datetime-clock"></p>
	<p>
		&copy; Maklen RC - <?= date('Y') ?> - v<?= \Yii::$app->params['version']; ?>
	</p>
</footer>
<!-- ./footer do sidenav -->	