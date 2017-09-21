<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model frontend\models\Financial */
/* @var $form ActiveForm */

$this->title = ($motion_id == 1) ? 'Доход' : 'Траты';
$this->params['breadcrumbs'][] = ['label' => 'Мои данные', 'url' => ['myfinances']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div style="width:100%; text-align: center; font-size: 16px;">
<h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="addrecord">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'money') ?>

		<?= $form->field($model, 'currency_id')->dropDownList($currencies) ?>

		<?= $form->field($model, 'wallet_id')->dropDownList($my_wallet) ?>

		<?= $form->field($model, 'category_id')->dropDownList($categories) ?>

		<?= $form->field($model, 'comment')->textarea() ?>

		<?= $form->field($model, 'tagValues')->label('Тэги')
			->widget(Select2::classname(), [
				'data' => $tags,
				'language' => 'ru',
				'options' => ['placeholder' => 'add some tags...','multiple' => true],
				'pluginOptions' => [
					'allowClear' => true,
					'tags'=>true,
				],
			]);
		?>

		<?= $form->field($model, 'date')
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

		<div class="form-group">
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

			<?= Html::submitButton('Ещё', ['name'=>'new', 'class' => 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end(); ?>

</div><!-- addrecord -->
