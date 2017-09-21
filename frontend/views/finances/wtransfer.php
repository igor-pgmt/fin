<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Financial */
/* @var $form ActiveForm */

$this->title = 'Распределить';
$this->params['breadcrumbs'][] = ['label' => 'Мои финансы', 'url' => ['myfinances']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div style="width:100%; text-align: center; font-size: 16px;">
<h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="addrecord">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'money') ?>

		<?= $form->field($model, 'currency_id')->dropDownList($currencies) ?>

		<?= $form->field($model, 'old_wallet_id')->dropDownList($my_wallet)->label('Откуда') ?>

		<?= $form->field($model, 'new_wallet_id')->dropDownList($my_wallet)->label('Куда') ?>

		<?= $form->field($model, 'comment')->textarea() ?>

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
		</div>
	<?php ActiveForm::end(); ?>

</div><!-- addrecord -->
