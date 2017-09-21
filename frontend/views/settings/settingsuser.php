<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;

/* @var $this yii\web\View */
/* @var $settings frontend\models\Settingsuser */
/* @var $form ActiveForm */
?>
<div class="settingsuser">

	<?php $form = ActiveForm::begin(); ?>

		<h2>Общие настройки</h2>

		<?= $form->field($settings, 'timezone')->dropDownList(['Timezone', $timezoneList])?>
		(current user's timezone: <?= Yii::$app->timezone ?>)
		<hr>
		<h2>Настройки цветов таблицы</h2>
		<?= $form->field($settings, 'color_row1')->widget(ColorInput::classname(), [
    		'options' => ['placeholder' => 'Select color ...', 'readonly' => false, ],
    		'pluginOptions' => ['allowEmpty' => true],
		]); ?>
		<?= $form->field($settings, 'color_row2')->widget(ColorInput::classname(), [
    		'options' => ['placeholder' => 'Select color ...', 'readonly' => false],
    		'pluginOptions' => ['allowEmpty' => true],
		]); ?>
		<?= $form->field($settings, 'color_select')->widget(ColorInput::classname(), [
    		'options' => ['placeholder' => 'Select color ...', 'readonly' => false],
    		'pluginOptions' => ['allowEmpty' => true],
		]); ?>
		<?= $form->field($settings, 'color_approve')->widget(ColorInput::classname(), [
    		'options' => ['placeholder' => 'Select color ...', 'readonly' => false],
    		'pluginOptions' => ['allowEmpty' => true],
		]); ?>
		<?= $form->field($settings, 'color_incomes')->widget(ColorInput::classname(), [
    		'options' => ['placeholder' => 'Select color ...', 'readonly' => false],
    		'pluginOptions' => ['allowEmpty' => true],
		]); ?>
		<?= $form->field($settings, 'color_expenses')->widget(ColorInput::classname(), [
    		'options' => ['placeholder' => 'Select color ...', 'readonly' => false],
    		'pluginOptions' => ['allowEmpty' => true],
		]); ?>


		<hr>
		<h2>Настройки заголовков таблицы</h2>

		<?= $form->field($settings, 'id')->checkbox() ?>
		<?= $form->field($settings, 'name_id')->textInput(['value'=> $settings->name_id ? $settings->name_id : $settings->getAttributeLabel('name_id')])->label(false); ?>

		<?= $form->field($settings, 'money')->checkbox() ?>
		<?= $form->field($settings, 'name_money')->textInput(['value'=> $settings->name_money ? $settings->name_money : $settings->getAttributeLabel('name_money')])->label(false); ?>

		<?= $form->field($settings, 'motion_id')->checkbox() ?>
		<?= $form->field($settings, 'name_motion_id')->textInput(['value'=> $settings->name_motion_id ? $settings->name_motion_id : $settings->getAttributeLabel('name_motion_id')])->label(false); ?>

		<?= $form->field($settings, 'categroup_id')->checkbox() ?>
		<?= $form->field($settings, 'name_categroup_id')->textInput(['value'=> $settings->name_categroup_id ? $settings->name_categroup_id : $settings->getAttributeLabel('name_categroup_id')])->label(false); ?>

		<?= $form->field($settings, 'category_id')->checkbox() ?>
		<?= $form->field($settings, 'name_category_id')->textInput(['value'=> $settings->name_category_id ? $settings->name_category_id : $settings->getAttributeLabel('name_category_id')])->label(false); ?>

		<?= $form->field($settings, 'tags')->checkbox() ?>
		<?= $form->field($settings, 'name_tags')->textInput(['value'=> $settings->name_tags ? $settings->name_tags : $settings->getAttributeLabel('name_tags')])->label(false); ?>

		<?= $form->field($settings, 'walletgroup_id')->checkbox() ?>
		<?= $form->field($settings, 'name_walletgroup_id')->textInput(['value'=> $settings->name_walletgroup_id ? $settings->name_walletgroup_id : $settings->getAttributeLabel('name_walletgroup_id')])->label(false); ?>

		<?= $form->field($settings, 'wallet_id')->checkbox() ?>
		<?= $form->field($settings, 'name_wallet_id')->textInput(['value'=> $settings->name_wallet_id ? $settings->name_wallet_id : $settings->getAttributeLabel('name_wallet_id')])->label(false); ?>

		<?= $form->field($settings, 'currency_id')->checkbox() ?>
		<?= $form->field($settings, 'name_currency_id')->textInput(['value'=> $settings->name_currency_id ? $settings->name_currency_id : $settings->getAttributeLabel('name_currency_id')])->label(false); ?>

		<?= $form->field($settings, 'date')->checkbox() ?>
		<?= $form->field($settings, 'name_date')->textInput(['value'=> $settings->name_date ? $settings->name_date : $settings->getAttributeLabel('name_date')])->label(false); ?>

		<?= $form->field($settings, 'comment')->checkbox() ?>
		<?= $form->field($settings, 'name_comment')->textInput(['value'=> $settings->name_comment ? $settings->name_comment : $settings->getAttributeLabel('name_comment')])->label(false); ?>

		<?= $form->field($settings, 'my_old_wallet_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_my_old_wallet_balance')->textInput(['value'=> $settings->name_my_old_wallet_balance ? $settings->name_my_old_wallet_balance : $settings->getAttributeLabel('name_my_old_wallet_balance')])->label(false); ?>

		<?= $form->field($settings, 'my_old_wgsumma_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_my_old_wgsumma_balance')->textInput(['value'=> $settings->name_my_old_wgsumma_balance ? $settings->name_my_old_wgsumma_balance : $settings->getAttributeLabel('name_my_old_wgsumma_balance')])->label(false); ?>

		<?= $form->field($settings, 'my_old_summa_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_my_old_summa_balance')->textInput(['value'=> $settings->name_my_old_summa_balance ? $settings->name_my_old_summa_balance : $settings->getAttributeLabel('name_my_old_summa_balance')])->label(false); ?>

		<?= $form->field($settings, 'my_new_wallet_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_my_new_wallet_balance')->textInput(['value'=> $settings->name_my_new_wallet_balance ? $settings->name_my_new_wallet_balance : $settings->getAttributeLabel('name_my_new_wallet_balance')])->label(false); ?>

		<?= $form->field($settings, 'my_new_wgsumma_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_my_new_wgsumma_balance')->textInput(['value'=> $settings->name_my_new_wgsumma_balance ? $settings->name_my_new_wgsumma_balance : $settings->getAttributeLabel('name_my_new_wgsumma_balance')])->label(false); ?>

		<?= $form->field($settings, 'my_new_summa_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_my_new_summa_balance')->textInput(['value'=> $settings->name_my_new_summa_balance ? $settings->name_my_new_summa_balance : $settings->getAttributeLabel('name_my_new_summa_balance')])->label(false); ?>

		<?= $form->field($settings, 'our_old_wgsumma_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_our_old_wgsumma_balance')->textInput(['value'=> $settings->name_our_old_wgsumma_balance ? $settings->name_our_old_wgsumma_balance : $settings->getAttributeLabel('name_our_old_wgsumma_balance')])->label(false); ?>

		<?= $form->field($settings, 'our_old_summa_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_our_old_summa_balance')->textInput(['value'=> $settings->name_our_old_summa_balance ? $settings->name_our_old_summa_balance : $settings->getAttributeLabel('name_our_old_summa_balance')])->label(false); ?>

		<?= $form->field($settings, 'our_new_wgsumma_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_our_new_wgsumma_balance')->textInput(['value'=> $settings->name_our_new_wgsumma_balance ? $settings->name_our_new_wgsumma_balance : $settings->getAttributeLabel('name_our_new_wgsumma_balance')])->label(false); ?>

		<?= $form->field($settings, 'our_new_summa_balance')->checkbox() ?>
		<?= $form->field($settings, 'name_our_new_summa_balance')->textInput(['value'=> $settings->name_our_new_summa_balance ? $settings->name_our_new_summa_balance : $settings->getAttributeLabel('name_our_new_summa_balance')])->label(false); ?>

		<?= $form->field($settings, 'related_record')->checkbox() ?>
		<?= $form->field($settings, 'name_related_record')->textInput(['value'=> $settings->name_related_record ? $settings->name_related_record : $settings->getAttributeLabel('name_related_record')])->label(false); ?>

		<?= $form->field($settings, 'approve')->checkbox() ?>
		<?= $form->field($settings, 'name_approve')->textInput(['value'=> $settings->name_approve ? $settings->name_approve : $settings->getAttributeLabel('name_approve')])->label(false); ?>

		<?= $form->field($settings, 'timestamp')->checkbox() ?>
		<?= $form->field($settings, 'name_timestamp')->textInput(['value'=> $settings->name_timestamp ? $settings->name_timestamp : $settings->getAttributeLabel('name_timestamp')])->label(false); ?>

		<div class="form-group">
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
		</div>
	<?php ActiveForm::end(); ?>

</div><!-- settingsuser -->
