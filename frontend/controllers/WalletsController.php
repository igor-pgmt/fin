<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Wallets;
use frontend\models\WalletsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Walletgroups;
use yii\helpers\ArrayHelper;
//use frontend\models\Finances;
use frontend\models\Currencies;

/**
 * WalletsController implements the CRUD actions for Wallets model.
 */
class WalletsController extends LoginController
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
	 * Lists all Wallets models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new WalletsSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Wallets model.
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
	 * Lists all Wallets.
	 * @return mixed
	 */
	public function actionInfo()
	{

		$currencies = Currencies::find()
			->select(['id', 'name_g'])
			->asArray()
			->all();

		$myWallets = Wallets::find()
			->select(['id', 'name'])
			->Where(['user_id' => Yii::$app->user->identity->id])
			->andWhere(['deleted' => false])
			->asArray()
			->all();

		foreach ($myWallets as $valueWallet) {
			foreach ($currencies as $valueCurr) {
				$myData[$valueWallet['name']][$valueCurr['name_g']] = Wallets::getWamount($valueCurr['id'], $valueWallet['id']);
			}
		}

		return $this->render('info', [
			'myData' => $myData,
		]);
	}

	/**
	 * Creates a new Wallets model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Wallets();
		$model->user_id = Yii::$app->user->identity->id; // В поле user_id вставляем id текущего юзера

		//use app\models\Walletgroups;
		$wallet_types=Walletgroups::find()
		->Where(['deleted' => false])
		->all();
		//use yii\helpers\ArrayHelper;
		$wallets=ArrayHelper::map($wallet_types,'id','name');

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]); Показ созданного кошелька
			return $this->redirect(['index']);
		} else {
			return $this->render('create', [
				'model' => $model,
				'wallets' => $wallets,
			]);
		}
	}

	/**
	 * Updates an existing Wallets model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		//use app\models\Walletgroups;
		$wallet_types=Walletgroups::find()
		->Where(['deleted' => false])
		->all();
		//use yii\helpers\ArrayHelper;
		$wallets=ArrayHelper::map($wallet_types,'id','name');

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]);
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
				'wallets' => $wallets,
			]);
		}
	}

	/**
	 * Deletes an existing Wallets model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		//$this->findModel($id)->delete(); // удаление моего кошелька

		$model = $this->findModel($id);
		$model->deleted = true;
		if ($model->save()) {
			return $this->redirect(['index']);
		} /*else {
			return $this->render('update', [
				'model' => $model,
				'wallets' => $wallets,
			]);
		}*/

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Wallets model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Wallets the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Wallets::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('Запрашиваемая страница не существует.');
		}
	}


}
