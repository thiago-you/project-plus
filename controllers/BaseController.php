<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class BaseController extends Controller
{	
	/**
	 * @inheritDoc
	 * @see \yii\web\Controller::beforeAction()
	 */
	public function beforeAction($action)
	{		
		return parent::beforeAction($action);
	}
}
?>