<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class UserContactSearch
 *
 * @package app\models
 */
class UserContactSearch extends UserContact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'string'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = $this->find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'user_id' => Yii::$app->user->id,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
