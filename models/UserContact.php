<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * Class UserContact
 *
 * @property string|integer id
 * @property string|integer user_id
 * @property string name
 * @property string phone
 * @property string email
 *
 * @property User user
 *
 * @package app\models
 */
class UserContact extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [self::EVENT_BEFORE_VALIDATE => 'user_id'],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'phone', 'email'], 'required'],
            ['user_id', 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id'],
            ['name', 'string', 'max' => 64],
            [['phone', 'email'], 'string'],
            ['phone', 'match', 'pattern' => '/^([0-9\+\.]+)$/'],
            ['email', 'email'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
