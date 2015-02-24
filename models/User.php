<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;

/**
 * Class User
 *
 * @property string|integer id
 * @property string auth_key
 * @property string password_hash
 * @property string email
 * @property string activate_token
 *
 * @property string password @write-only @see [[setPassword()]]
 *
 * @package app\models
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_key', 'password_hash', 'email'], 'required'],
            [['auth_key', 'activate_token'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string'],
            ['email', 'email'],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['!auth_key', '!password_hash', 'email', '!activate_token', 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->generateAuthKey();
            $this->generateActivateToken();
        }
        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Current implementation not support identity by access token.');
    }

    /**
     * Finds user by [[activate_token]]
     *
     * @param string $token
     * @return self|null
     */
    public static function findByActivateToken($token)
    {
        return static::findOne(['activate_token' => $token]);
    }

    /**
     * Finds user by [[email]]
     *
     * @param string $email
     * @return self|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'activate_token' => '']);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Generate new auth key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Send activate token.
     *
     * @return bool
     */
    public function sendActivateToken()
    {
        $params = Yii::$app->params['activation'];
        return Yii::$app->mailer
            ->compose('@app/mail/activation', [
                'userName' => $this->email,
                'userEmail' => $this->email,
                'activateLink' => \yii\helpers\Url::to(['/user/activate', 'token' => $this->activate_token], 'http'),
                'siteName' => Yii::$app->name,
            ])
            ->setSubject($params['subject'])
            ->setTo($this->email)
            ->setFrom($params['fromEmail'])
            ->send();
    }

    /**
     * Generate new activate token.
     */
    public function generateActivateToken()
    {
        $this->activate_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Remove activate token.
     */
    public function removeActivateToken()
    {
        $this->activate_token = null;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return  Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generate password hash by password. Support via mass assign @see [[scenarios()]]
     *
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}
