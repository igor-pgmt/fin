<?php

use yii\helpers\Html;
use yii\grid\GridView;
use frontend\models\Finances;
use frontend\components\UserTimeZoneBootstrap;

use frontend\models\Settingsadmin;

/*$a='2010-11-23';
echo strtotime($a).'<br />';

$TimeStr="2010-11-23";
$TimeZoneNameFrom="UTC";
$TimeZoneNameTo="Pacific/Pitcairn";
echo date_create($TimeStr, new DateTimeZone($TimeZoneNameFrom))
		->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("Y-m-d H:i:s");

echo '<br />';
echo '<br />';
echo '<br />';

echo Yii::$app->timeZone;

echo '<br />';

$tz = Settingsadmin::find()->one()->timezone;


echo Yii::$app->timeZone;

echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';
UserTimeZoneBootstrap::bootstrap(Yii::$app);
echo Yii::$app->timeZone;*/
echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';


$posts = Finances::find()->anyTagValues('testtag')->column();
print_r($posts);

echo '<br />';
echo '<br />';
echo '<br />';


$res=Finances::find()->where(['IN', 'finances.id', $posts]);
print_r($res);

echo '<br />';
echo '<br />';
echo '<br />';
$f=Finances::find();
$f1=$f->tagName();
print_r($f1);exit;