<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use app\models\Finances;
use kartik\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FinancesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои данные';
$this->params['breadcrumbs'][] = $this->title;

$string_my='Мой баланс:<br>';
foreach ($my_balance as $key => $value) {

$string_my.=$value['my_sum'].' '.$value['name_g'].'<br>';
}
$string_our='Общий баланс:<br>';
foreach ($our_balance as $key => $value) {
$string_our.=$value['my_sum'].' '.$value['name_g'].'<br>';
}

$string_my2='Мой вычисляемый баланс:<br>';
foreach ($my_balance2 as $key => $value) {
$string_my2.=$value['my_sum'].' '.$value['name_g'].'<br>';
}
$string_our2='Общий вычисляемый баланс:<br>';
foreach ($our_balance2 as $key => $value) {
$string_our2.=$value['my_sum'].' '.$value['name_g'].'<br>';
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

<div style="margin: 10px 0px;">
	<p>
		<?= Html::a('Доход', ['/finances/addrecord', 'motion_type' => 1], ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Траты', ['/finances/addrecord', 'motion_type' => 0], ['class'=>'btn btn-primary']) ?>
		<?= Html::a('Перевод', ['/finances/utransfer'], ['class'=>'btn btn-default']) ?>
		<?= Html::a('Распределить', ['/finances/wtransfer'], ['class'=>'btn btn-default']) ?>
		<?= Html::a('Конвертировать', ['/finances/wconvert'], ['class'=>'btn btn-default']) ?>
	</p>
</div>

<?php \yii\widgets\Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
/*            Столбец с управляющими картинками
			[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{view} {update} {delete} {link}',
			'buttons' => [
				'update' => function ($url,$model) {
					return Html::a(
					'<span class="glyphicon glyphicon-screenshot"></span>',
					$url);
				},
				'link' => function ($url,$model,$key) {
					return Html::a('Действие', $url);
				},
						],
			],*/






			[
				'attribute'=>'id',
				'label'=> $settingsUser['name_id'],
				'visible' => $settingsUser['id'],
			],
			[
				'attribute'=>'money',
				'label'=> $settingsUser['name_money'],
				'visible' => $settingsUser['money'],
			],
/*			[
				'attribute'=>'motion_id',
				'label'=> $settingsUser['name_motion_id'],
				'visible' => $settingsUser['motion_id'],
			],
*/			[
				'attribute'=>'motionType',
				'label'=> $settingsUser['name_motion_id'],
				'visible' => $settingsUser['motion_id'],
			],
			[
				'attribute'=>'categoryName',
				'label'=> $settingsUser['name_category_id'],
				'visible' => $settingsUser['category_id'],
			],
/*			[
				'attribute'=>'category_id',
				'label'=> $settingsUser['name_category_id'],
				'visible' => $settingsUser['category_id'],
			],*/
			[
				'attribute'=>'walletName',
				'label' => $settingsUser['name_wallet_id'],
				'visible' => $settingsUser['wallet_id'],
			],
/*			[
				'attribute'=>'wallet_id',
				'label'=> $settingsUser['name_wallet_id'],
				'visible' => $settingsUser['wallet_id'],
			],*/
			[
				'attribute'=>'currency_name',
				'label'=> $settingsUser['name_currency_id'],
				'visible' => $settingsUser['currency_id'],
			],
/*			[
				'attribute'=>'currency_id',
				'label'=> $settingsUser['name_currency_id'],
				'visible' => $settingsUser['currency_id'],
			],*/
			[
				'attribute'=>'date',
				//'label'=> $settingsUser['name_date'],
				'label'=> $settingsUser['name_date'],
				'visible' => $settingsUser['date'],
			],
			[
				'attribute'=>'comment',
				//'label'=> $settingsUser['name_comment'],
				'label'=> $settingsUser['name_comment'],
				'visible' => $settingsUser['comment'],
			],
			[
				'attribute'=>'my_old_wallet_balance',
				//'label'=> $settingsUser['name_my_old_wallet_balance'],
				'label'=> $settingsUser['name_my_old_wallet_balance'],
				'visible' => $settingsUser['my_old_wallet_balance'],
			],
			[
				'attribute'=>'my_old_wgsumma_balance',
				//'label'=> $settingsUser['name_my_old_wgsumma_balance'],
				'label'=> $settingsUser['name_my_old_wgsumma_balance'],
				'visible' => $settingsUser['my_old_wgsumma_balance'],
			],
			[
				'attribute'=>'my_old_summa_balance',
				//'label'=> $settingsUser['name_my_old_summa_balance'],
				'label'=> $settingsUser['name_my_old_summa_balance'],
				'visible' => $settingsUser['my_old_summa_balance'],
			],
			[
				'attribute'=>'my_new_wallet_balance',
				//'label'=> $settingsUser['name_my_new_wallet_balance'],
				'label'=> $settingsUser['name_my_new_wallet_balance'],
				'visible' => $settingsUser['my_new_wallet_balance'],
			],
			[
				'attribute'=>'my_new_wgsumma_balance',
				//'label'=> $settingsUser['name_my_new_wgsumma_balance'],
				'label'=> $settingsUser['name_my_new_wgsumma_balance'],
				'visible' => $settingsUser['my_new_wgsumma_balance'],
			],
			[
				'attribute'=>'my_new_summa_balance',
				//'label'=> $settingsUser['name_my_new_summa_balance'],
				'label'=> $settingsUser['name_my_new_summa_balance'],
				'visible' => $settingsUser['my_new_summa_balance'],
			],
			[
				'attribute'=>'our_old_wgsumma_balance',
				//'label'=> $settingsUser['name_our_old_wgsumma_balance'],
				'label'=> $settingsUser['name_our_old_wgsumma_balance'],
				'visible' => $settingsUser['our_old_wgsumma_balance'],
			],
			[
				'attribute'=>'our_old_summa_balance',
				//'label'=> $settingsUser['name_our_old_summa_balance'],
				'label'=> $settingsUser['name_our_old_summa_balance'],
				'visible' => $settingsUser['our_old_summa_balance'],
			],
			[
				'attribute'=>'our_new_wgsumma_balance',
				//'label'=> $settingsUser['name_our_new_wgsumma_balance'],
				'label'=> $settingsUser['name_our_new_wgsumma_balance'],
				'visible' => $settingsUser['our_new_wgsumma_balance'],
			],
			[
				'attribute'=>'our_new_summa_balance',
				//'label'=> $settingsUser['name_our_new_summa_balance'],
				'label'=> $settingsUser['name_our_new_summa_balance'],
				'visible' => $settingsUser['our_new_summa_balance']
			],
			[
				'attribute'=>'related_record',
				//'label'=> $settingsUser['name_related_record'],
				'label'=> $settingsUser['name_related_record'],
				'visible' => $settingsUser['related_record']
			],
			[
				'attribute'=>'timestamp',
				//'label'=> $settingsUser['name_timestamp'],
				'label'=> $settingsUser['name_timestamp'],
				'visible' => $settingsUser['timestamp'],
				'format' => ['date', 'php:Y.m.d H:i:s']
			],
			[
			'class' => 'yii\grid\ActionColumn',
			'template' =>'{view} {update} {delete}',
			'buttons' => [
				'update' => function ($url,$model) {
					//return $model->getTemp($url);
					//user can edit just his records
					return ((in_array($model->motion_id, [0,1]))) ? $model->getEditIcon($url) : false ;
				},
				'delete' => function ($url,$model) {
					//return $model->getTemp($url);
					//user can delete just his records
					return (!(in_array($model->motion_id, [3]))) ? $model->getDeleteIcon($url) : false ;
				},
						],
			],

		],
	]); ?>
<?php \yii\widgets\Pjax::end(); ?>




</div>

<?php
echo GridView::widget([
	'dataProvider'=> $dataProvider,
	'filterModel' => $searchModel,
	'filterRowOptions'=>['class'=>'kartik-sheet-style'],
		'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
	'headerRowOptions'=>['class'=>'kartik-sheet-style'],
	'columns' => [
			[
				'attribute'=>'id',
				'label'=> $settingsUser['name_id'],
				'visible' => $settingsUser['id'],
			],
			[
				'attribute'=>'money',
				'label'=> $settingsUser['name_money'],
				'visible' => $settingsUser['money'],
			],

			[
				'attribute'=>'motionType',
				'label'=> $settingsUser['name_motion_id'],
				'visible' => $settingsUser['motion_id'],
			],
			[
				'attribute'=>'categoryName',
				'label'=> $settingsUser['name_category_id'],
				'visible' => $settingsUser['category_id'],
			],
			[
				'attribute'=>'walletName',
				'label' => $settingsUser['name_wallet_id'],
				'visible' => $settingsUser['wallet_id'],
			],
/*			[
				'attribute'=>'wallet_id',
				'label'=> $settingsUser['name_wallet_id'],
				'visible' => $settingsUser['wallet_id'],
			],*/
			[
				'attribute'=>'currency_name',
				'label'=> $settingsUser['name_currency_id'],
				'visible' => $settingsUser['currency_id'],
			],
/*			[
				'attribute'=>'currency_id',
				'label'=> $settingsUser['name_currency_id'],
				'visible' => $settingsUser['currency_id'],
			],*/
			[
				'attribute'=>'date',
				//'label'=> $settingsUser['name_date'],
				'label'=> $settingsUser['name_date'],
				'visible' => $settingsUser['date'],
			],
			[
				'attribute'=>'comment',
				//'label'=> $settingsUser['name_comment'],
				'label'=> $settingsUser['name_comment'],
				'visible' => $settingsUser['comment'],
			],
			[
				'attribute'=>'my_old_wallet_balance',
				//'label'=> $settingsUser['name_my_old_wallet_balance'],
				'label'=> $settingsUser['name_my_old_wallet_balance'],
				'visible' => $settingsUser['my_old_wallet_balance'],
			],
			[
				'attribute'=>'my_old_wgsumma_balance',
				//'label'=> $settingsUser['name_my_old_wgsumma_balance'],
				'label'=> $settingsUser['name_my_old_wgsumma_balance'],
				'visible' => $settingsUser['my_old_wgsumma_balance'],
			],
			[
				'attribute'=>'my_old_summa_balance',
				//'label'=> $settingsUser['name_my_old_summa_balance'],
				'label'=> $settingsUser['name_my_old_summa_balance'],
				'visible' => $settingsUser['my_old_summa_balance'],
			],
			[
				'attribute'=>'my_new_wallet_balance',
				//'label'=> $settingsUser['name_my_new_wallet_balance'],
				'label'=> $settingsUser['name_my_new_wallet_balance'],
				'visible' => $settingsUser['my_new_wallet_balance'],
			],
			[
				'attribute'=>'my_new_wgsumma_balance',
				//'label'=> $settingsUser['name_my_new_wgsumma_balance'],
				'label'=> $settingsUser['name_my_new_wgsumma_balance'],
				'visible' => $settingsUser['my_new_wgsumma_balance'],
			],
			[
				'attribute'=>'my_new_summa_balance',
				//'label'=> $settingsUser['name_my_new_summa_balance'],
				'label'=> $settingsUser['name_my_new_summa_balance'],
				'visible' => $settingsUser['my_new_summa_balance'],
			],
			[
				'attribute'=>'our_old_wgsumma_balance',
				//'label'=> $settingsUser['name_our_old_wgsumma_balance'],
				'label'=> $settingsUser['name_our_old_wgsumma_balance'],
				'visible' => $settingsUser['our_old_wgsumma_balance'],
			],
			[
				'attribute'=>'our_old_summa_balance',
				//'label'=> $settingsUser['name_our_old_summa_balance'],
				'label'=> $settingsUser['name_our_old_summa_balance'],
				'visible' => $settingsUser['our_old_summa_balance'],
			],
			[
				'attribute'=>'our_new_wgsumma_balance',
				//'label'=> $settingsUser['name_our_new_wgsumma_balance'],
				'label'=> $settingsUser['name_our_new_wgsumma_balance'],
				'visible' => $settingsUser['our_new_wgsumma_balance'],
			],
			[
				'attribute'=>'our_new_summa_balance',
				//'label'=> $settingsUser['name_our_new_summa_balance'],
				'label'=> $settingsUser['name_our_new_summa_balance'],
				'visible' => $settingsUser['our_new_summa_balance']
			],
			[
				'attribute'=>'related_record',
				//'label'=> $settingsUser['name_related_record'],
				'label'=> $settingsUser['name_related_record'],
				'visible' => $settingsUser['related_record']
			],
				[
	   // 'class' => '\kartik\date\DatePicker',
		'filterType' => '\kartik\date\DatePicker',
		'filterWidgetOptions' => [
		'pluginOptions' => [
			'format' => 'yyyy-mm-dd'
],

		],
		'attribute' => 'timestamp',
		'format' => ['date', 'php:Y-m-d H:i:s']


	],
/*			[
				'attribute'=>'timestamp',
				'class' => '\kartik\date\DatePicker',
				//'label'=> $settingsUser['name_timestamp'],
				'label'=> $settingsUser['name_timestamp'],
				 'visible' => $settingsUser['timestamp'],
				 'format' => ['date', 'php:Y.m.d H:i:s']
			],
*/
		],
   // 'responsive'=>true,
	//'hover'=>true,
	'pjax'=>true,
		// parameters from the demo form
	'bordered'=>true,


	'showPageSummary'=>true,
	'panel'=>[
		'type'=>GridView::TYPE_PRIMARY,
		'heading'=>true,
	],
	'persistResize'=>false,
	'toggleDataOptions'=>['minCount'=>10],
	'exportConfig'=>true,
]);
?>
