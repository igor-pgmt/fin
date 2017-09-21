<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FinancesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Общие данные';
$this->params['breadcrumbs'][] = $this->title;

$string_my='Мой баланс:<br>';
foreach ($my_balance as $key => $value) {
$string_my.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}
$string_our='Общий баланс:<br>';
foreach ($our_balance as $key => $value) {
$string_our.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}
$string_my2='Мой вычисляемый баланс:<br>';
foreach ($my_balance2 as $key => $value) {
$string_my2.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}
$string_our2='Общий вычисляемый баланс:<br>';
foreach ($our_balance2 as $key => $value) {
$string_our2.=$value['my_sum'].' '.$value['currency_r'].'<br>';
}
//Видимость столбцов- TODO! сделать настройки с сохзранением в базе
if (Yii::$app->user->identity->id == '2') {$visiblity=true;}
else {$visiblity=true;};

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

			//'id',

/*          [
				'label'=>'Кто',
				// получаем Названия юзеров по их id
				'value' => function ($model) {
					return $model->getRealName(); // функцию getRealName см. в модели Finances.php
				}
			],
*/          [
				'attribute'=>'id',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['id'],
			],
			[
				'attribute'=>'money',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_money'],
				'visible' => Yii::$app->session->get('settingsAdmin')['money'],
			],
/*          [
				'attribute'=>'motion_id',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_motion_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['motion_id'],
			],
*/          [
				'attribute'=>'motionType',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_motion_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['motion_id'],
			],
			[
				'attribute'=>'categoryName',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_category_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['category_id'],
			],
/*          [
				'attribute'=>'category_id',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_category_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['category_id'],
			],*/
			[
				'attribute'=>'realName',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_user_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['user_id'],
			],
/*			[
				'attribute'=>'walletGroup',
				'label' => Yii::$app->session->get('settingsAdmin')['name_wallet_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['wallet_id'],
			],*/
			[
				'attribute'=>'walletName',
				'label' => Yii::$app->session->get('settingsAdmin')['name_wallet_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['wallet_id'],
			],
/*          [
				'attribute'=>'wallet_id',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_wallet_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['wallet_id'],
			],*/
			[
				'attribute'=>'currency_name',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_currency_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['currency_id'],
			],
/*          [
				'attribute'=>'currency_id',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_currency_id'],
				'visible' => Yii::$app->session->get('settingsAdmin')['currency_id'],
			],*/
			[
				'attribute'=>'date',
				//'label'=> Yii::$app->session->get('settingsAdmin')['name_date'],
				'label'=> Yii::$app->session->get('settingsAdmin')['name_date'],
				'visible' => Yii::$app->session->get('settingsAdmin')['date'],
			],
			[
				'attribute'=>'comment',
				//'label'=> Yii::$app->session->get('settingsAdmin')['name_comment'],
				'label'=> Yii::$app->session->get('settingsAdmin')['name_comment'],
				'visible' => Yii::$app->session->get('settingsAdmin')['comment'],
			],

			[
				'attribute'=>'my_old_wallet_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_my_old_wallet_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['my_old_wallet_balance'],
			],
			[
				'attribute'=>'my_old_wgsumma_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_my_old_wgsumma_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['my_old_wgsumma_balance'],
			],
			[
				'attribute'=>'my_old_summa_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_my_old_summa_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['my_old_summa_balance'],
			],
			[
				'attribute'=>'my_new_wallet_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_my_new_wallet_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['my_new_wallet_balance'],
			],
			[
				'attribute'=>'my_new_wgsumma_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_my_new_wgsumma_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['my_new_wgsumma_balance'],
			],
			[
				'attribute'=>'my_new_summa_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_my_new_summa_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['my_new_summa_balance'],
			],
			[
				'attribute'=>'our_old_wgsumma_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_our_old_wgsumma_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['our_old_wgsumma_balance'],
			],
			[
				'attribute'=>'our_old_summa_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_our_old_summa_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['our_old_summa_balance'],
			],
			[
				'attribute'=>'our_new_wgsumma_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_our_new_wgsumma_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['our_new_wgsumma_balance'],
			],
			[
				'attribute'=>'our_new_summa_balance',
				'label'=> Yii::$app->session->get('settingsAdmin')['name_our_new_summa_balance'],
				'visible' => Yii::$app->session->get('settingsAdmin')['our_new_summa_balance']
			],
			[
				'attribute'=>'related_record',
				//'label'=> Yii::$app->session->get('settingsAdmin')['name_related_record'],
				'label'=> Yii::$app->session->get('settingsAdmin')['name_related_record'],
				'visible' => Yii::$app->session->get('settingsAdmin')['related_record']
			],
			[
				'attribute'=>'timestamp',
				//'label'=> Yii::$app->session->get('settingsAdmin')['name_timestamp'],
				'label'=> Yii::$app->session->get('settingsAdmin')['name_timestamp'],
				'visible' => Yii::$app->session->get('settingsAdmin')['timestamp'],
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
	]);
	?>
<?php \yii\widgets\Pjax::end(); ?>



</div>
