<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Settingsuser;
use frontend\models\Settingsadmin;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends LoginController
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'signup'],
				'rules' => [
					[
						'actions' => ['signup'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	public function beforeAction($action)
	{
		if (in_array(Yii::$app->controller->action->id, ['myfinances', 'finshared'])) Yii::$app->getUser()->setReturnUrl(Url::current());

		return parent::beforeAction($action); //проверка на залогиненность
	}

	/**
	 * @inheritdoc
	 */

	public function actionSettings()
	{
		return $this->render('settings');
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->render('/finances/index');
	}

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
			//return $this->redirect(Yii::$app->getHomeUrl(),302);
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			if (Settingsadmin::find()->one()->session_using) {
				$settingsUserModel = Settingsuser::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
				$settingsUser = $settingsUserModel ? $settingsUserModel->settings : false ;
				$settingsAdminModel = Settingsadmin::find()->one();
				$settingsAdmin = $settingsAdminModel ? $settingsAdminModel->settings : false ;

				if ($settingsUser) Yii::$app->session->set('settingsUser', $settingsUser);
				if ($settingsAdmin)  Yii::$app->session->set('settingsAdmin', $settingsAdmin);
				Yii::$app->session->set('sessionUsing', true);
			}

			return $this->goBack();

		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return mixed
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
			} else {
				Yii::$app->session->setFlash('error', 'There was an error sending email.');
			}

			return $this->refresh();
		} else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays about page.
	 *
	 * @return mixed
	 */
	public function actionAbout()
	{
		return $this->render('about');
	}

	/**
	 * Signs user up.
	 *
	 * @return mixed
	 */
	public function actionSignup()
	{
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {

				if (Yii::$app->getUser()->login($user)) {
				//Adding basic settings to user
				$settingsUser = new Settingsuser();
					$settingsUser->user_id = Yii::$app->user->identity->id;
					$settingsUser->name_id = $settingsUser->getAttributeLabel('name_id');
					$settingsUser->name_money = $settingsUser->getAttributeLabel('name_money');
					$settingsUser->name_motion_id = $settingsUser->getAttributeLabel('name_motion_id');
					$settingsUser->name_category_id = $settingsUser->getAttributeLabel('name_category_id');
					$settingsUser->name_wallet_id = $settingsUser->getAttributeLabel('name_wallet_id');
					$settingsUser->name_currency_id = $settingsUser->getAttributeLabel('name_currency_id');
					$settingsUser->name_date = $settingsUser->getAttributeLabel('name_date');
					$settingsUser->name_comment = $settingsUser->getAttributeLabel('name_comment');
					$settingsUser->name_my_old_wallet_balance = $settingsUser->getAttributeLabel('name_my_old_wallet_balance');
					$settingsUser->name_my_old_wgsumma_balance = $settingsUser->getAttributeLabel('name_my_old_wgsumma_balance');
					$settingsUser->name_my_old_summa_balance = $settingsUser->getAttributeLabel('name_my_old_summa_balance');
					$settingsUser->name_my_new_wallet_balance = $settingsUser->getAttributeLabel('name_my_new_wallet_balance');
					$settingsUser->name_my_new_wgsumma_balance = $settingsUser->getAttributeLabel('name_my_new_wgsumma_balance');
					$settingsUser->name_my_new_summa_balance =  $settingsUser->getAttributeLabel('name_my_new_summa_balance');
					$settingsUser->name_our_old_wgsumma_balance = $settingsUser->getAttributeLabel('name_our_old_wgsumma_balance');
					$settingsUser->name_our_old_summa_balance = $settingsUser->getAttributeLabel('name_our_old_summa_balance');
					$settingsUser->name_our_new_wgsumma_balance = $settingsUser->getAttributeLabel('name_our_new_wgsumma_balance');
					$settingsUser->name_our_new_summa_balance = $settingsUser->getAttributeLabel('name_our_new_summa_balance');
					$settingsUser->name_related_record = $settingsUser->getAttributeLabel('name_related_record');
					$settingsUser->name_timestamp = $settingsUser->getAttributeLabel('name_timestamp');

					$settingsUser->id_id = true;
					$settingsUser->money = true;
					$settingsUser->motion_id = true;
					$settingsUser->category_id = true;
					$settingsUser->wallet_id = true;
					$settingsUser->currency_id = true;
					$settingsUser->date = true;
					$settingsUser->comment = true;
					$settingsUser->my_old_wallet_balance = true;
					$settingsUser->my_old_wgsumma_balance = true;
					$settingsUser->my_old_summa_balance = true;
					$settingsUser->my_new_wallet_balance = true;
					$settingsUser->my_new_wgsumma_balance = true;
					$settingsUser->my_new_summa_balance = true;
					$settingsUser->our_old_wgsumma_balance = true;
					$settingsUser->our_old_summa_balance = true;
					$settingsUser->our_new_wgsumma_balance = true;
					$settingsUser->our_new_summa_balance = true;
					$settingsUser->related_record = true;
					$settingsUser->timestamp = true;

					$settingsUser->save();

					return $this->goHome();
				}
			}
		}

		if (!Settingsadmin::find()->one()->registration) return $this->goHome();

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Requests password reset.
	 *
	 * @return mixed
	 */
	public function actionRequestPasswordReset()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

				return $this->goHome();
			} else {
				Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
			}
		}

		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	/**
	 * Resets password.
	 *
	 * @param string $token
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
	public function actionResetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->session->setFlash('success', 'New password was saved.');

			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}
}
