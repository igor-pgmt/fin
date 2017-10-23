<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\jui\DatePicker;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\models\Plans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plans-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'planname')->textInput() ?>

 <!--   <?= $form->field($model, 'user_id')->textInput() ?> -->

	<?= $form->field($model, 'moneyline')->textInput(['value' => 0]) ?>

	<?= $form->field($model, 'walletgroup_id')->dropDownList($walletgroup_id, ['prompt'=>'Нет', 'id'=>'cat-id']); ?>

	<?= $form->field($model, 'wallet_id')->widget(DepDrop::classname(), [
		'options'=>['id'=>'subcat-id'],
		'pluginOptions'=>[
			'depends'=>['cat-id'],
			'placeholder'=>'Нет',
			'url'=>Url::to(['/plans/getwallet'])
		]
	]); ?>

	<?= $form->field($model, 'cgroup_id')->dropDownList($categroups, ['prompt'=>'Нет', 'id'=>'cat-id2']); ?>

	<?= $form->field($model, 'category_id')->widget(DepDrop::classname(), [
		'options'=>['id'=>'subcat-id2'],
		'pluginOptions'=>[
			'depends'=>['cat-id2'],
			'placeholder'=>'Нет',
			'url'=>Url::to(['/plans/getcategory'])
		]
	]); ?>

	<?= $form->field($model, 'currency_id')->dropDownList($currency_id) ?>

	<?= $form->field($model, 'motion_id')->dropDownList($motions,  ['prompt'=>'Select...']) ?>

	<?= $form->field($model, 'planTags')->label('Тэги')
		->widget(Select2::classname(), [
			'data' => $tags,
			'language' => 'ru',
			'options' => ['multiple' => true],
			'pluginOptions' => [
				'allowClear' => true,
			],
		]);
	?>

	<?= $form->field($model, 'tag_search')->checkbox() ?>

	<?= $form->field($model, 'date_from')
		->widget(DatePicker::classname(), [
			'type' => DatePicker::TYPE_COMPONENT_APPEND,
			'pickerButton' => false,
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'todayBtn' => true,
				'autoclose'=>true,
			]
		])
	?>

	<?= $form->field($model, 'date_to')
		->widget(DatePicker::classname(), [
			'type' => DatePicker::TYPE_COMPONENT_APPEND,
			'pickerButton' => false,
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd',
				'todayHighlight' => true,
				'todayBtn' => true,
				'autoclose'=>true,
			]
		])
	?>

	<?= $form->field($model, 'common_plan')->checkbox() ?>

	<?= $form->field($model, 'difference')->checkbox() ?>

	<?= $form->field($model, 'summation')->checkbox() ?>

	<?= $form->field($model, 'shared_plan')->dropDownList(['0'=>'Только мне','1'=>'Мой план доступен всем','2'=>'Индивидуальный план каждому', ]); ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
