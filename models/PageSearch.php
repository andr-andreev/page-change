<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PageSearch represents the model behind the search form about `app\models\Page`.
 */
class PageSearch extends Page
{
    public $category_title;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at',], 'integer'],
            [['category_title', 'is_active'], 'safe'],
            [['description', 'url', 'last_content'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Page::find()->joinWith(['category']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'category_title' => SORT_ASC,
                    'description' => SORT_ASC,
                    'url' => SORT_ASC
                ],
                'attributes'   => [
                    'is_active',
	                'description',
	                'url',
	                'filter_from',
	                'updated_at',
	                'category_title' => [
		                'asc'  => [ 'category.title' => SORT_ASC ],
		                'desc' => [ 'category.title' => SORT_DESC ],
	                ]
                ]
            ]
        ] );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'category.id', $this->category_title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'filter_from', $this->filter_from])
            ->andFilterWhere(['like', 'filter_to', $this->filter_to])
            ->andFilterWhere(['like', 'last_content', $this->last_content]);

        return $dataProvider;
    }
}
