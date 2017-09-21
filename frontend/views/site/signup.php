<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">


	<div class="row" style="text-align:center;">
<h1><?= Html::encode($this->title) ?></h1>
			<?php $form = ActiveForm::begin(['id' => 'slick-login']); ?>

				<?= $form->field($model, 'realname')->textinput(['placeholder' => "Имя"]) ?>

				<?= $form->field($model, 'username')->textinput(['placeholder' => "Логин"]) ?>

				<?= $form->field($model, 'email')->textinput(['placeholder' => "E-mail"]) ?>

				<?= $form->field($model, 'password')->passwordInput(['placeholder' => "Пароль"]) ?>



				<div class="form-group">
				<input type="submit" class = 'btn btn-primary' value="Зарегистрироваться">
<!-- 					<?= Html::submitButton('Зарегиться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?> -->
				</div>

			<?php ActiveForm::end(); ?>

	</div>
</div>
