<script>
  function convertTimestamp(timestamp) {
  var d = new Date(timestamp), // Convert the passed timestamp to milliseconds
	yyyy = d.getFullYear(),
	mm = ("0" + (d.getMonth() + 1)).slice(-2),  // Months are zero based. Add leading 0.
	dd = ("0" + d.getDate()).slice(-2),     // Add leading 0.
	hh = d.getHours(),
	h = hh,
	min = ("0" + d.getMinutes()).slice(-2),   // Add leading 0.
	ampm = "AM",
	time;

  if (hh > 12) {
	h = hh - 12;
	ampm = "PM";
  } else if (hh === 12) {
	h = 12;
	ampm = "PM";
  } else if (hh == 0) {
	h = 12;
  }

  // ie: 2013-02-18, 8:35 AM
  //time = yyyy + "-" + mm + "-" + dd + ", " + h + ":" + min + " " + ampm;
  time = yyyy + "-" + mm + "-" + dd;

  return time;
}

//функция для highcharts;
function findme(point) {

$("#pointClickLog").html('Нажатая дата: '+point.x+'; '+point.y+"; "+ convertTimestamp(point.x));

link="finances/finshared?FinsharedSearch%5Bmoney%5D=&FinsharedSearch%5Bcurrency_id%5D=&FinsharedSearch%5Bmotion_id%5D=&FinsharedSearch%5Bcategory_id%5D=&FinsharedSearch%5Bwallet_id%5D=&FinsharedSearch%5Bdate%5D="+convertTimestamp(point.x)+"&FinsharedSearch%5Bcomment%5D=&FinsharedSearch%5Bmy_old_wallet_balance%5D=&FinsharedSearch%5Bmy_old_summa_balance%5D=&FinsharedSearch%5Bmy_new_wallet_balance%5D=&FinsharedSearch%5Bmy_new_summa_balance%5D=&FinsharedSearch%5Bour_old_wgsumma_balance%5D=&FinsharedSearch%5Bour_old_summa_balance%5D=&FinsharedSearch%5Bour_new_wgsumma_balance%5D=&FinsharedSearch%5Bour_new_summa_balance%5D=&FinsharedSearch%5Btimestamp%5D=";
window.open(link, "window name");

}

</script>


<?php
/* @var $this yii\web\View */
use sibilino\y2dygraphs\DygraphsWidget;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\SeriesDataHelper;
use yii\helpers\Html;

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = $this->title;

$string_my='Мой баланс:<br>';
foreach ($my_balance as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_my.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}
$string_our='Общий баланс:<br>';
foreach ($our_balance as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_our.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}

$string_my2='Мой вычисляемый баланс:<br>';
foreach ($my_balance2 as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_my2.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}
$string_our2='Общий вычисляемый баланс:<br>';
foreach ($our_balance2 as $key => $value) {
$value['my_sum']=floatval($value['my_sum']);
$string_our2.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}

//костыль
$style="white";
$style2="margin: 10px; display:none;";
for ($i=0; $i < 2; $i++) {
  if (!($my_balance[$i]['my_sum']==$my_balance2[$i]['my_sum']) OR !($our_balance[$i]['my_sum']==$our_balance2[$i]['my_sum'])) {
	$style="red";
	$style2="margin: 10px; display: inline-block;";
  }
}

?>
<div class="finances-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div style="overflow: hidden; text-align:left; background-color: <?=$style?>;">
  <div style="margin: 10px; display:inline-block;"><?= $string_my; ?></div>

  <div style="margin: 10px; display:inline-block;"><?= $string_our; ?></div>

  <div style="<?= $style2; ?>"><?= $string_my2; ?></div>

  <div style="<?= $style2; ?>"><?= $string_our2; ?></div>
</div>

<?php

echo ' <div id="pointClickLog">Последняя нажатая дата будет здесь</div>';
echo '<br /><br />';

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
[      //  'type'=>'line',

	 'type'=> 'scatter',
	 'marker'=> ['enabled'=> 'false'],

		'value'=>$value['moneyline'],
		'color'=> '#ff0000',
		'width'=>2,],
	],
	  ],


	 'series' => [[
	 //
		 //['data' => new SeriesDataHelper($value, ['name:text', 'date:timestamp', 'amount:int']),
		 'name' => isset($value['sPlanresult'][0]['name'])?$value['sPlanresult'][0]['name']:'',
		 'data' => new SeriesDataHelper( $value['sPlanresult'], [ 'date:timestamp', 'amount:int']),
			   'type' => 'area',
					  'fillColor' => [
					'linearGradient' => [
						'x1' => 0,
						'y1' => 1,
					],

					'stops' => [
/*                        [0, new JsExpression('Highcharts.getOptions().colors[0]')],
						[1, new JsExpression('Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get("rgba")')]*/
						[0, 'rgb(255, 255, 255)'],
						[1, 'rgba(0, 0, 150, 0.1)']


					],

],
		 ]
		 ],

	   'plotOptions' => [
	   'minRange' =>1,
	  'series' => [

		'cursor' => 'pointer',
		'point' => [
		  'events' => [
			'click' => new JsExpression('function(e){ findme(this); }')
			//'click' =>  new JsExpression('function(event, this) { findme(this); }'),
		  ]
		]
	  ]

]
   ]
]);
echo '</div>';
  }}

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
/*[

	 'type'=> 'scatter',
	 'marker'=> ['enabled'=> 'false'],

		'value'=>$value['moneyline'],
		'color'=> '#ff0000',
		'width'=>2,],*/
	],
	  ],
'tooltip' => [

				'formatter' => new JsExpression('function(){  return convertTimestamp(this.key); }')
			 ],


	 'series' => [[
	 //
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
/*                        [0, new JsExpression('Highcharts.getOptions().colors[0]')],
						[1, new JsExpression('Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get("rgba")')]*/
						[0, 'rgb(255, 255, 255)'],
						[1, 'rgba(0, 0, 150, 0.1)']


					],

],
		 ]
		 ],

	   'plotOptions' => [
	   'minRange' =>1,
	  'series' => [

		'cursor' => 'pointer',
		'point' => [
		  'events' => [
			'click' => new JsExpression('function(e){ findme(this); }')
			//'click' =>  new JsExpression('function(event, this) { findme(this); }'),
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

if ($allPlans==0){ echo '<br />Все планы пусты<br />';
} else {
  foreach ($allPlans as $key => $value) {
  	echo '<div style = "border: 2px solid #DDDDDD; margin: 15px;">';
	if (empty($value['planresult'])) { echo '<br />План "'.$value['planname'].'" пуст<br /><br /></div>';
  } else {

	echo Highcharts::widget([
	   'options' => [
		  'chart'=>['zoomType'=> 'x'],
		  'title' => ['text' => $value['planname']],
		  'xAxis' => [
			 //'categories' => ['Apples', 'Bananas', 'Oranges']
		  'title' => ['text' => 'Дата'],
		  'type' => 'datetime',
		  ],
		  'yAxis' => [
		  'max'=>$value['max'],
		  'min'=>$value['min'],
			 'title' => ['text' => $value['planresult'][0]['name']],
			'plotLines' => [
			[  //  'type'=>'line',
			'type'=> 'scatter',
			'marker'=> ['enabled'=> 'false'],
			'value'=>$value['moneyline'],
			'color'=> '#ff0000',
			'width'=>2,],
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
				//'click' =>  new JsExpression('function(event, this) { findme(this); }'),
			  ]
			]
		  ]
		]

	   ]
	]);
	echo '</div>';
}}}
?>


</div>