<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Finances */

$this->title = 'Редактировать запись: ' . ' ID ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Финансы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';

?>
<div class="finances-update">

	<h1><?= Html::encode($this->title) ?></h1>

 <?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'money') ?>

		<?= $form->field($model, 'category_id')->dropDownList($categories) ?>

		<?= $form->field($model, 'finTags')->label('Тэги')
			->widget(Select2::classname(), [
				'data' => $tags,
				'language' => 'ru',
				'options' => ['placeholder' => 'add some tags...','multiple' => true],
				'pluginOptions' => [
					'allowClear' => true,
					'tags' => true,
				],
			]);
		?>

		<?= $form->field($model, 'comment')->textarea() ?>

		<div class="form-group">
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end(); ?>

</div>
