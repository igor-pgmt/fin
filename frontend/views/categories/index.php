<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи трат и доходов';
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['site/settings']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

<?php Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel, //скрываем фильтр
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			//'id',
			'category',

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
			]

		],
	]); ?>
<?php Pjax::end(); ?>
</div>
