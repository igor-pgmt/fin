<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'cgroup_id')->dropDownList($categroups, ['prompt'=>'Выбрать...']) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
