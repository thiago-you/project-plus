<?php
use yii\helpers\Url;
?>
<!-- ./footer do sidenav -->	
<div class="left_col scroll-view">
    <div class="navbar nav_title text-center">
        <a class="navbar-brand" href="<?= \Yii::$app->homeUrl; ?>">
        	Maklen <i>RC</i>
        </a>
    </div>
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">	
        	<ul class="nav side-menu">
        		<li>
                	<a href="<?= Url::to(['/contrato']); ?>"><i class="fa fa-file-invoice-dollar"></i>&nbsp; Contrato</a>
                </li>
                <li>
    				<a href="<?= Url::to(['/colaborador']); ?>"><i class="fa fa-user"></i>&nbsp; Colaborador</a>
    			</li>
			</ul>
        </div>
    </div>
    <div class="sidebar-footer hidden-small">
        <p>
        	&copy; Maklen RC - <?= date('Y') ?> - v<?= \Yii::$app->params['version']; ?>
        </p>
    </div>
</div>

		
		
		