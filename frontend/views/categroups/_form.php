<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Categroups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categroups-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'cgroupname')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
