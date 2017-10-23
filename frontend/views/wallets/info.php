<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use app\models\Finances;

$this->title = 'Мои деньги';
$this->params['breadcrumbs'][] = $this->title;

?>

<div style="width:100%; text-align: center; font-size: 16px;">
	<h1><?= Html::encode($this->title) ?></h1>
	<div class="my-wallet-info">
		<div>
			<?php

				foreach ($myData as $key => $value) { //левый столбец, название кошелька
				echo '<div style="border: 1px solid grey; margin: 5px; padding: 3px;">';
				echo '<div style="border: 0px solid grey; margin: 5px; padding: 3px;">';
				echo '<b>'.$key.'</b>';
				echo '</div>';
				echo '<div style="border: 1px solid grey; margin: 5px; padding: 3px;">';
					foreach ($value as $key2 => $value2) { //правый столбец, название валюты и её объём
						$value2 = isset($value2) ? $value2 : 0;
						echo '<div style="border: 0px solid grey; margin: 5px; padding: 3px;">';
						echo sprintf('%.20F', $value2).' '.$key2;
						echo '</div>';
					}
				echo '</div>';
				echo '</div>';
				}
			?>
		</div>
	</div>
</div>
