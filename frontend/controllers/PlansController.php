<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Plans;
use frontend\models\PlansSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Categories;
use frontend\models\Categroups;
use frontend\models\Currencies;
use frontend\models\Wallets;
use frontend\models\Walletgroups;
use frontend\models\Motions;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use frontend\models\Finances;

/**
 * PlansController implements the CRUD actions for Plans model.
 */
class PlansController extends LoginController
{
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	/**
	 * Lists all Plans models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PlansSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Plans model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Plans model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate($planType=1)
	{
		$model = new Plans();
		$model->plantype = $planType;
		$model->user_id = Yii::$app->user->identity->id;

$allTags = array_merge(Plans::getAllTags(), Finances::getAllTags());
$allTags = array_combine($allTags, $allTags);
 // print_r($allTags);exit;
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			if (isset($model->tagValues) && !empty($model->tagValues)) $model->tagValues = implode(",",$model->planTags);
			$model->save();
			return $this->redirect(['index']);

		} else {

			return $this->render('create', [
				'model' => $model,
				'planType' => $planType,
				'currency_id' => Currencies::findCurrencies(),
				'categroups' => Categroups::findCategroups(),
				'walletgroup_id' => Walletgroups::findWalletgroups(),
				//'motions' => [0=>'Затраты', 1=>'Доходы'],
				'motions' => Motions::findMotions(),
				'tags' => $allTags,
			]);
		}
	}

	/**
	 * Updates an existing Plans model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		//this redord's tags
		$recordsWithTags = Plans::find()->where(['id'=>$id])->with('tags')->all();

		$tagValues=[];
		foreach ($recordsWithTags as $record) {
			if ($record->tags) $tagValues = array_merge($tagValues, $record->getTagValues(true));
		}

		$model->planTags = array_combine($tagValues, $tagValues);

		$allTags = array_merge(Plans::getAllTags(), Finances::getAllTags());
		$allTags = array_combine($allTags, $allTags);

		if ($model->load(Yii::$app->request->post()) /*&& $model->save()*/) {
			$model->tagValues = $model->planTags ? implode(",",$model->planTags) : false;
			$model->save();
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
				'moneyline' => $model->moneyline,
				'planType' => $model->plantype,
				'date_from' => $model->date_from,
				'date_to' => $model->date_to,
				'wallet_id' => $model->wallet_id,
				'category_id' => $model->category_id,
				'currency_id' => Currencies::findCurrencies(),
				'categroups' => Categroups::findCategroups(),
				'walletgroup_id' => Walletgroups::findWalletgroups(),
				'motions' => Motions::findMotions(),
				'tags' => $allTags,
			]);
		}
	}

	public function actionGetwallet() {
		$out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			if ($parents != null) {
				$cat_id = $parents[0];

				//$out = self::getSubCatList($cat_id);
				$output =Wallets::find()
				->Where(['deleted'=>false])
				->andWhere(['user_id'=>Yii::$app->user->identity->id])
				->andWhere(['walletgroup_id'=>$cat_id])
				->all();
				$prom1=ArrayHelper::map($output,'id','name');
				foreach ($prom1 as $key => $value) {
					$out[]=['id'=>$key, 'name'=>$value];
				}

				// the getSubCatList function will query the database based on the
				// cat_id and return an array like below:
				// [
				//    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
				//    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
				// ]
				echo Json::encode(['output'=>$out, 'selected'=>'']);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}

	public function actionGetcategory() {
		$out = [];
		if (isset($_POST['depdrop_parents'])) {
			$parents = $_POST['depdrop_parents'];
			if ($parents != null) {
				$cat_id = $parents[0];

				//$out = self::getSubCatList($cat_id);
				$output =Categories::find()
				->Where(['deleted'=>false])
				->andWhere(['cgroup_id'=>$cat_id])
				->all();
				$prom1=ArrayHelper::map($output,'id','category');
				foreach ($prom1 as $key => $value) {
					$out[]=['id'=>$key, 'name'=>$value];
				}

				// the getSubCatList function will query the database based on the
				// cat_id and return an array like below:
				// [
				//    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
				//    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
				// ]
				echo Json::encode(['output'=>$out, 'selected'=>'']);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}

	/**
	 * Deletes an existing Plans model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}


	/**
	 * Displays a single Plans model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionData()
	{
		return $this->render('data', [
			//'model' => $this->findModel($id),
		]);
	}

	/**
	 * Finds the Plans model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Plans the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Plans::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
