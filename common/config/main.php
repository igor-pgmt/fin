<?php
use \yii\web\Request;
use frontend\models\Settingsadmin;
use Yii;
$baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());

//date_default_timezone_set('Europe/Podgorica');

return [
//'timezone' => 'Pacific/Pitcairn',
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	//'sourceLanguage' => 'ru-RU',
	'language' => 'ru',
	'modules' => [
		'debug' => [
			'class' => 'yii\debug\Module',
		],
		//kartik gridview
		'gridview' =>  [
        'class' => '\kartik\grid\Module'
        // enter optional module parameters below - only if you need to
        // use your own export download action or custom translation
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
    ],
	],
	'components' => [
			'request' => [
			'baseUrl' => $baseUrl,
		],
			'urlManager' => [ //pretty url
			'baseUrl' => $baseUrl,
			'showScriptName' => false,  // Disable index.php
			'enablePrettyUrl' => true,  // Disable r= routes
			//'enableStrictParsing' => true,
			//
			//
			//
			'rules' => array(
					// .....
					),
		],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],

	],
];
