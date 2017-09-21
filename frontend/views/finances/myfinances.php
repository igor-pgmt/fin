<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use frontend\assets\GridAsset;
GridAsset::register($this);

if (isset($settingsAdmin)) {
	$settingsUser = $settingsAdmin;
}

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FinancesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = !isset($settingsAdmin) ? 'Мои данные' : 'Общие данные';
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
	'pjax'=>true,
	'pjaxSettings'       => [
			'options' => [
				'enablePushState' => false,
			]
		],
	'bordered'=>true,
	'showPageSummary'=>true,
	'rowOptions' => function ($model, $key, $index, $grid) use ($settingsUser) {

		if ($model->approve) {
			$bgColor = $settingsUser['color_approve'];
		} elseif (in_array($model->motion_id, [0,1])) {
			if ($model->motion_id == 0) {
				$bgColor = $settingsUser['color_expenses'];
			} elseif ($model->motion_id == 1) {
				$bgColor = $settingsUser['color_incomes'];
			}
		} elseif (($index % 2) == 0) {
			$bgColor = $settingsUser['color_row2'];
		} else {
			$bgColor = $settingsUser['color_row1'];
		}

		return [
			'onclick' => 'selectRow(this, "'.$settingsUser['color_select'].'");',
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
				'contentOptions'=>['class' => 'kartik-sheet-style', 'style' => 'color:#C0C0C0;'],
				'width' => '60px',
				'header' => '№',
				'headerOptions'=>['class' => 'kartik-sheet-style'],
				'pageSummary'=>'Всего',
			],
			[
				'attribute' => 'id',
				'label'=> $settingsUser['name_id'],
				'visible' => $settingsUser['id'],
				'vAlign' => 'middle',
				'width' => '30px',
			],
			[
				'attribute' => 'money',
				'label'=> $settingsUser['name_money'],
				'visible' => $settingsUser['money'],
				'vAlign' => 'middle',
				'pageSummary'=> true,
				'width' => '30px',
				//'format'=> ['decimal', 2],
				'value'=>function ($model) {
					return floatval($model->money);
				},
			],
/*			[
				'attribute' => 'motionType',
				'label'=> $settingsUser['name_motion_id'],
				'visible' => $settingsUser['motion_id'],
			],
*/
			[
				'label'=> $settingsUser['name_motion_id'],
				'visible' => $settingsUser['motion_id'],
				'attribute' => 'motion_id',
				'vAlign' => 'middle',
				'width' => '180px',
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
/*			[
				'attribute' => 'categoryName',
				'label'=> $settingsUser['name_category_id'],
				'visible' => $settingsUser['category_id'],
			],*/
			[
				'label'=> $settingsUser['name_categroup_id'],
				'visible' => $settingsUser['categroup_id'],
				'attribute' => 'cgroup_id',
				'vAlign' => 'middle',
				'width' => '180px',
				'value'=>function ($model, $key, $index, $widget) {
					return $model->categroupName;
				},
				'filterType'=>GridView::FILTER_SELECT2,
				'filter'=>$categroups,
				'filterWidgetOptions'=>[
					'pluginOptions'=>['allowClear'=>true],
				],
				'filterInputOptions'=>['placeholder' => 'Any'],
				'format' => 'raw'
			],
			[
				'label'=> $settingsUser['name_category_id'],
				'visible' => $settingsUser['category_id'],
				'attribute' => 'category_id',
				'vAlign' => 'middle',
				'width' => '180px',
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
			//Disabled for user's view
			[
				'label'=> $settingsUser['name_user_id'],
				'visible' => $settingsUser['user_id'],
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
/*			[
				'attribute' => 'walletName',
				'label' => $settingsUser['name_wallet_id'],
				'visible' => $settingsUser['wallet_id'],
			],
			[
				'attribute' => 'wallet_id',
				'label'=> $settingsUser['name_wallet_id'],
				'visible' => $settingsUser['wallet_id'],
			],
			[
				'attribute' => 'currency_name',
				'label'=> $settingsUser['name_currency_id'],
				'visible' => $settingsUser['currency_id'],
			],
*/
			[
				'label' => $settingsUser['name_tags'],
				'visible' => $settingsUser['tags'],
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
				'label' => $settingsUser['name_walletgroup_id'],
				'visible' => $settingsUser['walletgroup_id'],
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
				'label' => $settingsUser['name_wallet_id'],
				'visible' => $settingsUser['wallet_id'],
				'attribute' => 'wallet_id',
				'vAlign' => 'middle',
				'width' => '180px',
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
				'label'=> $settingsUser['name_currency_id'],
				'visible' => $settingsUser['currency_id'],
				'attribute' => 'currency_id',
				'vAlign' => 'middle',
				'width' => '180px',
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
/*			[
				'attribute' => 'currency_id',
				'label'=> $settingsUser['name_currency_id'],
				'visible' => $settingsUser['currency_id'],
			],*/
			[
				'attribute' => 'date',
				//'label'=> $settingsUser['name_date'],
				'label'=> $settingsUser['name_date'],
				'visible' => $settingsUser['date'],
				'vAlign' => 'middle',
				'width' => '40px',
			],
			[
				'attribute' => 'comment',
				//'label'=> $settingsUser['name_comment'],
				'label'=> $settingsUser['name_comment'],
				'visible' => $settingsUser['comment'],
				'vAlign' => 'middle',
			],
			[
				'attribute' => 'my_old_wallet_balance',
				//'label'=> $settingsUser['name_my_old_wallet_balance'],
				'label'=> $settingsUser['name_my_old_wallet_balance'],
				'visible' => $settingsUser['my_old_wallet_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_old_wallet_balance);
				},
			],
			[
				'attribute' => 'my_old_wgsumma_balance',
				//'label'=> $settingsUser['name_my_old_wgsumma_balance'],
				'label'=> $settingsUser['name_my_old_wgsumma_balance'],
				'visible' => $settingsUser['my_old_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_old_wgsumma_balance);
				},
			],
			[
				'attribute' => 'my_old_summa_balance',
				//'label'=> $settingsUser['name_my_old_summa_balance'],
				'label'=> $settingsUser['name_my_old_summa_balance'],
				'visible' => $settingsUser['my_old_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_old_summa_balance);
				},
			],
			[
				'attribute' => 'my_new_wallet_balance',
				//'label'=> $settingsUser['name_my_new_wallet_balance'],
				'label'=> $settingsUser['name_my_new_wallet_balance'],
				'visible' => $settingsUser['my_new_wallet_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_new_wallet_balance);
				},
			],
			[
				'attribute' => 'my_new_wgsumma_balance',
				//'label'=> $settingsUser['name_my_new_wgsumma_balance'],
				'label'=> $settingsUser['name_my_new_wgsumma_balance'],
				'visible' => $settingsUser['my_new_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_new_wgsumma_balance);
				},
			],
			[
				'attribute' => 'my_new_summa_balance',
				//'label'=> $settingsUser['name_my_new_summa_balance'],
				'label'=> $settingsUser['name_my_new_summa_balance'],
				'visible' => $settingsUser['my_new_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->my_new_summa_balance);
				},
			],
			[
				'attribute' => 'our_old_wgsumma_balance',
				//'label'=> $settingsUser['name_our_old_wgsumma_balance'],
				'label'=> $settingsUser['name_our_old_wgsumma_balance'],
				'visible' => $settingsUser['our_old_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_old_wgsumma_balance);
				},
			],
			[
				'attribute' => 'our_old_summa_balance',
				//'label'=> $settingsUser['name_our_old_summa_balance'],
				'label'=> $settingsUser['name_our_old_summa_balance'],
				'visible' => $settingsUser['our_old_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_old_summa_balance);
				},
			],
			[
				'attribute' => 'our_new_wgsumma_balance',
				//'label'=> $settingsUser['name_our_new_wgsumma_balance'],
				'label'=> $settingsUser['name_our_new_wgsumma_balance'],
				'visible' => $settingsUser['our_new_wgsumma_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_new_wgsumma_balance);
				},
			],
			[
				'attribute' => 'our_new_summa_balance',
				//'label'=> $settingsUser['name_our_new_summa_balance'],
				'label'=> $settingsUser['name_our_new_summa_balance'],
				'visible' => $settingsUser['our_new_summa_balance'],
				'vAlign' => 'middle',
				'value'=>function ($model) {
					return floatval($model->our_new_summa_balance);
				},
			],
			[
				'attribute' => 'related_record',
				//'label'=> $settingsUser['name_related_record'],
				'label'=> $settingsUser['name_related_record'],
				'visible' => $settingsUser['related_record'],
				'vAlign' => 'middle',
			],
			[
				'label'=> $settingsUser['name_approve'],
				'visible' => $settingsUser['approve'],
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

	   // 'class' => '\kartik\date\DatePicker',
/*		'filterType' => '\kartik\date\DatePicker',
		'filterWidgetOptions' => [
		'pluginOptions' => [
			'format' => 'yyyy-mm-dd'
],

		],*/





/*				[
		'attribute' => 'timestamp',
		'format' => ['date', 'php:Y-m-d H:i:s'],

 'value' => function ($model) {
		   $date = date('Y-m-d H:i:s', $model->timestamp);
			return $date ;
		},

	],
*/

			[
				'attribute' => 'timestamp',
				'label'=> $settingsUser['name_timestamp'],
				'visible' => $settingsUser['timestamp'],
				'value' => 'timestamp',
				'format' => ['date', 'php:Y-m-d H:i:s'],
				'vAlign' => 'middle',
			],
			[
				'class'=>'kartik\grid\ActionColumn',
'template' => '{approve}{view}{update}{delete}',
				'dropdownOptions'=>['class'=>'pull-right'],
				'urlCreator'=>function($action, $model, $key, $index) { return '#'; },
				'viewOptions'=>['title'=>'This will launch the book details page. Disabled for this demo!', 'data-toggle'=>'tooltip'],
				'updateOptions'=>['title'=>'This will launch the book update page. Disabled for this demo!', 'data-toggle'=>'tooltip'],
				'deleteOptions'=>['title'=>'This will launch the book delete action. Disabled for this demo!', 'data-toggle'=>'tooltip'],
				'headerOptions'=>['class'=>'kartik-sheet-style'],
				'buttons' => [
 					'approve' => function ($url,$model, $key) {
						return ($model->approve) ? Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', ['approve', 'id'=>$model->id]) : Html::a('<span class="glyphicon glyphicon-ok"></span>', ['approve', 'id'=>$model->id]);
 					},
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
		],




/*			[
				'attribute' => 'timestamp',
				'class' => '\kartik\date\DatePicker',
				//'label'=> $settingsUser['name_timestamp'],
				'label'=> $settingsUser['name_timestamp'],
				 'visible' => $settingsUser['timestamp'],
				 'format' => ['date', 'php:Y.m.d H:i:s']
			],
*/


]);
?>
