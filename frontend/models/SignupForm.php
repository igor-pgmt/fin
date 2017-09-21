<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
	public $realname;
	public $username;
	public $email;
	public $password;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['realname', 'username'], 'filter', 'filter' => 'trim'],
			[['realname', 'username'], 'required'],
			[['realname', 'username'], 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такое имя уже есть.'],
			['username', 'string', 'min' => 2, 'max' => 255],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

			['password', 'required'],
			['password', 'string', 'min' => 6],
		];
	}

	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function signup()
	{
		if ($this->validate()) {
			$user = new User();
			$user->realname = $this->realname;
			$user->username = $this->username;
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->generateAuthKey();
			if ($user->save()) {

				return $user;
			}
		}

		return null;
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'realname' => 'Имя',
			'username' => 'Логин',
			'email' => 'email',

		];
	}
}
