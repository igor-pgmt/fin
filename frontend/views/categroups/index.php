<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categroups-index">

	<h1><?= Html::encode($this->title) ?></h1>
	Группы, в которые можно объединить статьи затрат и доходов<br /><br />

	<p>
		<?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
	</p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel, //скрываем фильтр
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			//'id',
			'cgroupname',
			//'deleted',

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
<?php Pjax::end(); ?></div>
