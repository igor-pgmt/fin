<?php

namespace frontend\components;

use yii\base\BootstrapInterface;
use yii\web\Application;
use frontend\models\Settingsadmin;
use frontend\models\Settingsuser;
use Yii;

class UserTimeZoneBootstrap implements BootstrapInterface
{
	/**
	 * @param Application $app
	 */
	public function bootstrap($app)
	{
		if (!$app->user->isGuest) {
			$timezone = Settingsuser::findOne(['user_id' => $app->user->identity->id])->timezone;
			$app->timezone = $timezone ? $timezone : Settingsadmin::find()->one()->timezone;
		}
	}
}