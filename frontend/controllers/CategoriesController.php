<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Categories;
use frontend\models\CategoriesSearch;
use frontend\models\Categroups;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends LoginController
{

	public function beforeAction($action)
	{
		if (in_array(Yii::$app->controller->action->id, ['myfinances', 'finshared'])) Yii::$app->getUser()->setReturnUrl(Url::current());
		return parent::beforeAction($action); //проверка на залогиненность

	}
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
	 * Lists all Categories models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new CategoriesSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Categories model.
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
	 * Creates a new Categories model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Categories();

		//Список групп трат
		$categroups = Categroups::findCategroups();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
		   // return $this->redirect(['view', 'id' => $model->id]); //отрисовка просмотра категории
			return $this->redirect(['index']);
		} else {
			return $this->render('create', [
				'model' => $model,
				'categroups' => $categroups,
			]);
		}
	}

	/**
	 * Updates an existing Categories model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		//Список групп трат
		$categroups = Categroups::findCategroups();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->id]);
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
				'categroups' => $categroups,
			]);
		}
	}

	/**
	 * Deletes an existing Categories model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		//$this->findModel($id)->delete(); // удаление категории

		$model = $this->findModel($id);
		$model->deleted = true;
		if ($model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
				'wallets' => $wallets,
			]);
		}

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Categories model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Categories the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Categories::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('Запрошенная страница не существует.');
		}
	}
}
