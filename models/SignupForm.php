<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

/**
 * SignupForm is the model behind the signup form.
 */
class SignupForm extends Model
{
    public $email;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],

            [['email', 'password'], 'string'],

            ['email', 'unique', 'targetClass' => User::className()],

            ['password', 'string', 'min' => 8],
            ['password', 'match', 'pattern' => '/^([a-z0-9]+)$/'],
        ];
    }

    /**
     * Register new user in db and create activation key.
     *
     * @return User|false
     * @throws ServerErrorHttpException
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->setAttributes($this->attributes);
            if ($user->save()) {
                return $user;
            } else {
                throw new ServerErrorHttpException('Error saving user.');
            }
        }

        return false;
    }
}