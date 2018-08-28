<?php
use yii\helpers\Url;
?>
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="menu-navbar-collapse">
	<ul class="nav navbar-nav">
		<li><a href="<?= Url::to(['/cliente']); ?>">Clientes</a></li>
	</ul>
    <!-- ./menu -->
	<form class="navbar-form navbar-left">
		<div class="form-group">
			<input type="text" class="form-control" placeholder="Buscar">
		</div>
		<button type="submit" class="btn btn-default">Buscar</button>
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
		
		