<?php

namespace frontend\controllers;
use Yii;
use yii\helpers\Url;
use frontend\models\Settingsadmin;

class LoginController extends \yii\web\Controller
{
	public function beforeAction($action)
	{
		Yii::$app->view->params['registration'] = Settingsadmin::find()->one()->registration;
		//проверка на залогиненность
/*		if ((Yii::$app->user->isGuest) && (Yii::$app->request->url !== '/site/login') && (Yii::$app->request->url !== '/site/signup')) {
			return $this->redirect('/site/login',302);
		}		*/
		//проверка на залогиненность
		if ((Yii::$app->user->isGuest) && (!in_array(Yii::$app->request->url, ['/site/login', '/site/signup', '/site/request-password-reset', '/site/reset-password'])))
		{
			return $this->redirect('/site/login',302);
		}

		return true;

    }






    public function actionIndex()
    {
		return $this->redirect('/site/index',302);

    }

}