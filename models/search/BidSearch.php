<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bid;

/**
 * BidSearch represents the model behind the search form of `app\models\Bid`.
 */
class BidSearch extends Bid
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'manufacturer_id', 'brand_id', 'brand_model_id', 'composition_id', 'client_id', 'condition_id'], 'integer'],
            [['brand_model_name', 'composition_table', 'composition_name', 'serial_number', 'vendor_code', 'client_name', 'client_phone', 'client_address', 'treatment_type', 'purchase_date', 'application_date', 'created_at', 'updated_at', 'warranty_number', 'bid_number', 'bid_1C_number', 'bid_manufacturer_number'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Bid::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'manufacturer_id' => $this->manufacturer_id,
            'brand_id' => $this->brand_id,
            'brand_model_id' => $this->brand_model_id,
            'composition_id' => $this->composition_id,
            'client_id' => $this->client_id,
            'purchase_date' => $this->purchase_date,
            'application_date' => $this->application_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'condition_id' => $this->condition_id,
        ]);

        $query->andFilterWhere(['like', 'brand_model_name', $this->brand_model_name])
            ->andFilterWhere(['like', 'composition_table', $this->composition_table])
            ->andFilterWhere(['like', 'composition_name', $this->composition_name])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'vendor_code', $this->vendor_code])
            ->andFilterWhere(['like', 'client_name', $this->client_name])
            ->andFilterWhere(['like', 'client_phone', $this->client_phone])
            ->andFilterWhere(['like', 'client_address', $this->client_address])
            ->andFilterWhere(['like', 'treatment_type', $this->treatment_type])
            ->andFilterWhere(['like', 'warranty_number', $this->warranty_number])
            ->andFilterWhere(['like', 'bid_number', $this->bid_number])
            ->andFilterWhere(['like', 'bid_1C_number', $this->bid_1C_number])
            ->andFilterWhere(['like', 'bid_manufacturer_number', $this->bid_manufacturer_number]);

        return $dataProvider;
    }
}
