<?php use yii\helpers\Html; ?>

<div style="margin: 0px 0px 20px 0px;">
	<?= Html::a('Доход', ['/finances/addrecord', 'motion_type' => 1], ['class' => 'btn btn-primary']) ?>
	<?= Html::a('Траты', ['/finances/addrecord', 'motion_type' => 0], ['class' => 'btn btn-primary']) ?>
	<?= Html::a('Перевод', ['/finances/utransfer'], ['class' => 'btn btn-default']) ?>
	<?= Html::a('Распределить', ['/finances/wtransfer'], ['class' => 'btn btn-default']) ?>
	<?= Html::a('Конвертировать', ['/finances/wconvert'], ['class' => 'btn btn-default']) ?>
</div>