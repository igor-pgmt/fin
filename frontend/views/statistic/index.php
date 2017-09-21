<?php
/* @var $this yii\web\View */
//use sibilino\y2dygraphs\DygraphsWidget;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\SeriesDataHelper;
use yii\helpers\Html;
use frontend\assets\StatisticAsset;
StatisticAsset::register($this);

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="finances-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('/finances/_data', [
		'my_balance' => $my_balance,
		'our_balance' => $our_balance,
		'my_balance2' => $my_balance2,
		'our_balance2' => $our_balance2,
		]);
	?>

<div id="pointClickLog">Последняя нажатая дата появится здесь.</div>
<br />

<?php

if (!$staticPlans==0){
	foreach ($staticPlans as $key => $value) {
	echo '<div style = "border: 2px solid #DDDDDD; margin: 25px;">';
	echo Highcharts::widget([
		'options' => [
			'chart'=>['zoomType'=> 'x'],
			'title' => ['text' => $value['planname']],
			'xAxis' => [
				'title' => ['text' => 'Дата'],
				'type' => 'datetime',
			],
			'yAxis' => [
				'max'=>$value['max'],
				'min'=>$value['min'],
				'title' => ['text' => isset($value['sPlanresult'][0]['name'])?$value['sPlanresult'][0]['name']:''],
				'plotLines' => [
					[
					//	'type'=>'line',
					'type'=> 'scatter',
					'marker'=> ['enabled'=> 'false'],
					'value'=>$value['moneyline'],
					'color'=> '#ff0000',
					'width'=>2,
					],
				],
			],
			'series' => [
				[
					//['data' => new SeriesDataHelper($value, ['name:text', 'date:timestamp', 'amount:int']),
					'name' => isset($value['sPlanresult'][0]['name'])?$value['sPlanresult'][0]['name']:'',
					'data' => new SeriesDataHelper( $value['sPlanresult'], [ 'date:timestamp', 'amount:int']),
					'type' => 'area',
					'fillColor' => [
						'linearGradient' =>
							[
								'x1' => 0,
								'y1' => 1,
							],
						'stops' => [
						/*
							[0, new JsExpression('Highcharts.getOptions().colors[0]')],
							[1, new JsExpression('Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get("rgba")')]
						*/
							[0, 'rgb(255, 255, 255)'],
							[1, 'rgba(0, 0, 150, 0.1)']
						],
					],
				],
			],
			'plotOptions' => [
				'minRange' =>1,
				'series' => [
					'cursor' => 'pointer',
					'point' => [
						'events' => [
							'click' => new JsExpression('function(e){ findme(this); }')
						]
					]
				]
			],
		]
	]);

echo '</div>';

	}
}


/*--- График сравнения доходов и расходов за последние 30 дней ---*/

if (!$comparisonPlan==0){

	echo '<div style = "border: 2px solid #DDDDDD; margin: 25px;">';
		echo Highcharts::widget([
			'options' => [
				'chart'=>[
					'zoomType'=> 'y',
					'type' => 'column'
				],
				'title' => ['text' => 'Доходы и расходы за последние 30 дней'],
				'type' => 'column',
				'xAxis' => [
					'title' => ['text' => 'Валюта'],
					'categories' => $comparisonPlan['planresult']['currencies'],
				],
				'yAxis' => [
					'max'=>$comparisonPlan['max'],
					'min'=>0,
					'title' => ['text' => 'Номинал'],
				],
				'series' =>$comparisonPlan['cPlan'],

				'plotOptions' => [
					'column' => [
						'grouping' => false,
			            'shadow' => false,
			            'borderWidth' => 0,]
				],
			]
		]);

	echo '</div>';

}

/*--- End Of График сравнения доходов и расходов за последние 30 дней ---*/



if (!$lyingPlan==0){
echo '<div style = "border: 2px solid #DDDDDD; margin: 25px;">';
	echo Highcharts::widget([
		'options' => [
			'chart'=>['zoomType'=> 'x'],
			'title' => ['text' =>'Несвоевременное внесение данных'],
			'colors'=> ['#FF0000'],
			'xAxis' => [
				'title' => ['text' => 'Дата'],
				'type' => 'datetime',
			],
			'yAxis' => [
				'max'=>2,
				'min'=>0,
				// 'title' => ['text' => $lyingPlan['planresult'][0]['name']],
				'plotLines' => [
				/*
				'type'=> 'scatter',
				'marker'=> ['enabled'=> 'false'],
				'value'=>$value['moneyline'],
				'color'=> '#ff0000',
				'width'=>2,
				*/
				],
			],
			'tooltip' => [
				'formatter' => new JsExpression('function(){	return convertTimestamp(this.key); }')
			],
			'series' => [
				[
					//['data' => new SeriesDataHelper($value, ['name:text', 'date:timestamp', 'amount:int']),
					'name' => 'Запаленная дата',
					'data' => new SeriesDataHelper( $lyingPlan['planresult'], [ 'date:timestamp', 'lyingdate:int']),
					'type' => 'area',
					'fillColor' => [
						'linearGradient' => [
									'x1' => 0,
									'y1' => 1,
						],
						'stops' => [
						/*
							[0, new JsExpression('Highcharts.getOptions().colors[0]')],
							[1, new JsExpression('Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get("rgba")')]
						*/
							[0, 'rgb(255, 255, 255)'],
							[1, 'rgba(0, 0, 150, 0.1)']
						],
					],
				],
			],
			'plotOptions' => [
				'minRange' =>1,
				'series' => [
					'cursor' => 'pointer',
					'point' => [
						'events' => [
							'click' => new JsExpression('function(e){ findme(this); }')
							//'click' =>	new JsExpression('function(event, this) { findme(this); }'),
						]
					]
				]
			]
		]
	]);

echo '</div>';

}

echo '<hr />';
echo '<div style="text-align:center; font-size:19px;">Пользовательские графики</div>';
echo '<hr />';

if ($allPlans==0){
	echo '<br />Все планы пусты<br />';
} else {
	foreach ($allPlans as $key => $value) {
	echo '<div style = "border: 2px solid #DDDDDD; margin: 15px;">';
	if (empty($value['planresult'])) { echo '<br />План "'.$value['planname'].'" пуст<br /><br /></div>';
	} else {

	echo Highcharts::widget([
		'options' => [
			'chart'=>['zoomType'=> 'x'],
			'title' => ['text' => $value['planname']],
			'xAxis' =>
				[
				//'categories' => ['Apples', 'Bananas', 'Oranges']
				'title' => ['text' => 'Дата'],
				'type' => 'datetime',
				],
			'yAxis' => [
				'max'=>$value['max'],
				'min'=>$value['min'],
				'title' => ['text' => $value['planresult'][0]['name']],
				'plotLines' => [
					[	//	'type'=>'line',
						'type'=> 'scatter',
						'marker'=> ['enabled'=> 'false'],
						'value'=>$value['moneyline'],
						'color'=> '#ff0000',
						'width'=>2,
					],
				// 'label'=>'goal',
				],
			],
			'series' => [
			 //['data' => new SeriesDataHelper($value, ['name:text', 'date:timestamp', 'amount:int']),
				[
					'name' => $value['planresult'][0]['name'], 'data' => new SeriesDataHelper( $value['planresult'], [ 'date:timestamp', 'amount:int']),
			 //['data' => $data],
				],
			],
			'plotOptions' => [
				'minRange' =>1,
				'series' => [
					'cursor' => 'pointer',
					'point' => [
						'events' => [
							'click' => new JsExpression('function(e){ findme(this); }')
							//'click' =>	new JsExpression('function(event, this) { findme(this); }'),
						]
					]
				]
			]
		]
	]);

	echo '</div>';

	}
	}
}
?>

</div>
