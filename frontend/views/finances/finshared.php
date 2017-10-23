<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use frontend\assets\GridAsset;
GridAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FinancesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Общие данные';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="finances-index">

	<h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_data', [
        'my_balance' => $my_balance,
        'our_balance' => $our_balance,
        'my_balance2' => $my_balance2,
        'our_balance2' => $our_balance2,
    	]);
    ?>

<div style="margin: 10px 0px;">

    <?= $this->render('_buttons.php'); ?>

<?php
echo GridView::widget([
	'dataProvider'=> $dataProvider,
	'filterModel' => $searchModel,
	'filterRowOptions'=>['class' => 'kartik-sheet-style'],
	'headerRowOptions'=>['class' => 'kartik-sheet-style ', ],
	'bootstrap'=>true,
	'striped'=>true,
	'responsive'=>true,
	'responsiveWrap'=>true,
	'hover'=>true,
	'filterUrl'          => Url::to(["finances/finshared"]),
	'pjax'=>true,
	'pjaxSettings'       => [
			'options' => [
				'enablePushState' => false,
			]
		],
	'bordered'=>true,
	'showPageSummary'=>true,
	'rowOptions' => function ($model, $key, $index, $grid) use ($settingsAdmin) {

		if ($model->approve) {
			$bgColor = $settingsAdmin['color_approve'];
		} elseif (in_array($model->motion_id, [0,1])) {
			if ($model->motion_id == 0) {
				$bgColor = $settingsAdmin['color_expenses'];
			} elseif ($model->motion_id == 1) {
				$bgColor = $settingsAdmin['color_incomes'];
			}
		} elseif (($index % 2) == 0) {
			$bgColor = $settingsAdmin['color_row2'];
		} else {
			$bgColor = $settingsAdmin['color_row1'];
		}

		return [
			'onclick' => 'selectRow(this, "'.$settingsAdmin['color_select'].'");',
			'style'=>'background-color: '.$bgColor.';',
		];

	},
	'pageSummaryRowOptions'=>['style' => 'background-color:white; color:black; font-weight:bold;'],
	'panel'=>[
		'type'=>GridView::TYPE_DEFAULT  ,
		'heading'=>false,
	],

	'toggleDataOptions'=>['minCount'=>10],
	'exportConfig'=>true,
	'columns' => [
			[
				'class' => 'kartik\grid\SerialColumn',
				'contentOptions'=>[
					'style' => 'color:#C0C0C0;'],
				'width' => '60px',
				'header' => '№',
				'pageSummary'=>'Всего',
			],
			['class' => 'kartik\grid\DataColumn',
				'attribute' => 'id',
				'label'=> $settingsAdmin['name_id'],
				'visible' => $settingsAdmin['id'],
				'vAlign' => 'middle',
				'contentOptions'=>['class'=>'kartik-sheet-style'],

			],
			[
				'attribute' => 'money',
				'label'=> $settingsAdmin['name_money'],
				'visible' => $settingsAdmin['money'],
				'vAlign' => 'middle',
				'pageSummary'=> true,
				'value'=>function ($model) {
					return floatval($model->money);
				},
			],
			[
				'label'=> $settingsAdmin['name_motion_id'],
				'visible' => $settingsAdmin['motion_id'],
				'attribute' => 'motion_id',
				'vAlign' => 'middle',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->motionType;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$motionTypes,
				'filterWidgetOptions'=>[
					'pluginOptions'=>[
					'allowClear'=>true,
					//'multiple' => true
					],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'label'=> $settingsAdmin['name_category_id'],
				'visible' => $settingsAdmin['category_id'],
				'attribute' => 'category_id',
				'vAlign' => 'middle',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->categoryName;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$categories,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'label'=> $settingsAdmin['name_user_id'],
				'visible' => $settingsAdmin['user_id'],
				'attribute' => 'user_id',
				'vAlign' => 'middle',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->realName;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$users,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'label' => $settingsAdmin['name_tags'],
				'visible' => $settingsAdmin['tags'],
				'attribute' => 'tag',
				'vAlign' => 'middle',
				'width' => '180px',
				'value'=>function ($model, $key, $index, $widget ) {
				 	return $model->tagName;
				 },
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$tags,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true,
					'multiple'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'label' => $settingsAdmin['name_walletgroup_id'],
				'visible' => $settingsAdmin['walletgroup_id'],
				'attribute' => 'walletgroup_id',
				'vAlign' => 'middle',
				'width' => '180px',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->walletgroupName;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$walletGroups,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'label' => $settingsAdmin['name_wallet_id'],
				'visible' => $settingsAdmin['wallet_id'],
				'attribute' => 'wallet_id',
				'vAlign' => 'middle',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->walletName;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$wallets,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'label'=> $settingsAdmin['name_currency_id'],
				'visible' => $settingsAdmin['currency_id'],
				'attribute' => 'currency_id',
				'vAlign' => 'middle',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->currency_name;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$currencies,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'attribute' => 'date',
				//'label'=> $settingsAdmin['name_date'],
				'label'=> $settingsAdmin['name_date'],
				'visible' => $settingsAdmin['date'],
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'comment',
				//'label'=> $settingsAdmin['name_comment'],
				'label'=> $settingsAdmin['name_comment'],
				'visible' => $settingsAdmin['comment'],
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'my_old_wallet_balance',
				//'label'=> $settingsAdmin['name_my_old_wallet_balance'],
				'label'=> $settingsAdmin['name_my_old_wallet_balance'],
				'visible' => $settingsAdmin['my_old_wallet_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_old_wallet_balance);
				},
			],
			[
				'attribute' => 'my_old_wgsumma_balance',
				//'label'=> $settingsAdmin['name_my_old_wgsumma_balance'],
				'label'=> $settingsAdmin['name_my_old_wgsumma_balance'],
				'visible' => $settingsAdmin['my_old_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_old_wgsumma_balance);
				},
			],
			[
				'attribute' => 'my_old_summa_balance',
				//'label'=> $settingsAdmin['name_my_old_summa_balance'],
				'label'=> $settingsAdmin['name_my_old_summa_balance'],
				'visible' => $settingsAdmin['my_old_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_old_summa_balance);
				},
			],
			[
				'attribute' => 'my_new_wallet_balance',
				//'label'=> $settingsAdmin['name_my_new_wallet_balance'],
				'label'=> $settingsAdmin['name_my_new_wallet_balance'],
				'visible' => $settingsAdmin['my_new_wallet_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_new_wallet_balance);
				},
			],
			[
				'attribute' => 'my_new_wgsumma_balance',
				//'label'=> $settingsAdmin['name_my_new_wgsumma_balance'],
				'label'=> $settingsAdmin['name_my_new_wgsumma_balance'],
				'visible' => $settingsAdmin['my_new_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_new_wgsumma_balance);
				},
			],
			[
				'attribute' => 'my_new_summa_balance',
				//'label'=> $settingsAdmin['name_my_new_summa_balance'],
				'label'=> $settingsAdmin['name_my_new_summa_balance'],
				'visible' => $settingsAdmin['my_new_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_new_summa_balance);
				},
			],
			[
				'attribute' => 'our_old_wgsumma_balance',
				//'label'=> $settingsAdmin['name_our_old_wgsumma_balance'],
				'label'=> $settingsAdmin['name_our_old_wgsumma_balance'],
				'visible' => $settingsAdmin['our_old_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_old_wgsumma_balance);
				},
			],
			[
				'attribute' => 'our_old_summa_balance',
				//'label'=> $settingsAdmin['name_our_old_summa_balance'],
				'label'=> $settingsAdmin['name_our_old_summa_balance'],
				'visible' => $settingsAdmin['our_old_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_old_summa_balance);
				},
			],
			[
				'attribute' => 'our_new_wgsumma_balance',
				//'label'=> $settingsAdmin['name_our_new_wgsumma_balance'],
				'label'=> $settingsAdmin['name_our_new_wgsumma_balance'],
				'visible' => $settingsAdmin['our_new_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_new_wgsumma_balance);
				},
			],
			[
				'attribute' => 'our_new_summa_balance',
				//'label'=> $settingsAdmin['name_our_new_summa_balance'],
				'label'=> $settingsAdmin['name_our_new_summa_balance'],
				'visible' => $settingsAdmin['our_new_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_new_summa_balance);
				},
			],
			[
				'attribute' => 'related_record',
				//'label'=> $settingsAdmin['name_related_record'],
				'label'=> $settingsAdmin['name_related_record'],
				'visible' => $settingsAdmin['related_record'],
				'vAlign' => 'middle',
			],
			[
				'label'=> $settingsAdmin['name_approve'],
				'visible' => $settingsAdmin['approve'],
				'attribute' => 'approve',
				'vAlign' => 'middle',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->realNameByID;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$users,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'attribute'=>'timestamp',
				//'label'=> $settingsAdmin['name_timestamp'],
				'label'=> $settingsAdmin['name_timestamp'],
				'visible' => $settingsAdmin['timestamp'],
				'format' => ['date', 'php:Y.m.d H:i:s'],
				'vAlign' => 'middle',
			],
			[
				'class'=>'kartik\grid\ActionColumn',

				'dropdownOptions'=>['class'=>'pull-right'],
				'urlCreator'=>function($action, $model, $key, $index) { return '#'; },
				'viewOptions'=>['title'=>'This will launch the book details page. Disabled for this demo!', 'data-toggle'=>'tooltip'],
				'updateOptions'=>['title'=>'This will launch the book update page. Disabled for this demo!', 'data-toggle'=>'tooltip'],
				'deleteOptions'=>['title'=>'This will launch the book delete action. Disabled for this demo!', 'data-toggle'=>'tooltip'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
				'buttons' => [
					'view' => function ($url,$model, $key) {
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id'=>$model->id]);
					},
					'update' => function ($url,$model, $key) {
						//return $model->getTemp($url);
						//user can edit just his records

						return ((in_array($model->motion_id, [0,1])) && ($model->user_id == Yii::$app->user->identity->id)) ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id'=>$model->id]) : false ;
					},
					'delete' => function ($url,$model, $key) {
						//return $model->getTemp($url);
						//user can delete just his records
						return (!(in_array($model->motion_id, [3])) && ($model->user_id == Yii::$app->user->identity->id)) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id'=>$model->id]) : false ;
					},
				],
			],
/*			[
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
			],*/
		],
	]);
	?>




</div>
