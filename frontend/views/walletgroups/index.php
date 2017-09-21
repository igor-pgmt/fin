<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WalletsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы кошельков';
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['site/settings']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wallets-index">

	<h1><?= Html::encode($this->title) ?></h1>
	Группы, в которые можно объединить кошельки<br /><br />

	<p>
		<?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel, //скрываем фильтр
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			//'id',
			'name',

			//['class' => 'yii\grid\ActionColumn'],
			[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{update} {delete}',
			'buttons' => [
				'update' => function ($url,$model) {
					return Html::a(
					'<span class="glyphicon glyphicon-pencil"></span>',
					$url);
				},
						],
			],
		],
	]); ?>

</div>
