<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class BaseController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
						'delete' => ['POST'],
				],
			],
		];
	}
	
	public function init()
	{
	    if(Yii::$app->user->isGuest && $this->module->requestedRoute != 'site/login') {
	        return $this->redirect(['/site/login', 'invalidAcess' => true]);
        }
	 
	    parent::init();
	}
}
?>