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
    public $purchase_date_from;
    public $purchase_date_to;
    public $application_date_from;
    public $application_date_to;
    public $created_at_from;
    public $created_at_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {


        return [
            [[
                'manufacturer_id',
                'condition_id',
                'status_id',
                'warranty_status_id',
                'repair_status_id'
            ],
                'integer'],
            [[
                'brand_name',
                'equipment',
                'brand_model_name',
                'composition_name',
                'serial_number',
                'vendor_code',
                'client_name',
                'client_phone',
                'client_address',
                'treatment_type',
                'purchase_date_from',
                'purchase_date_to',
                'application_date_from',
                'application_date_to',
                'created_at_from',
                'created_at_to',
                'warranty_number',
                'bid_number',
                'bid_1C_number',
                'bid_manufacturer_number',
                'defect',
                'diagnostic'
            ], 'safe'],
        ];
    }

    public function beforeValidate()
    {
        $this->normalizeDateRange($this->created_at_from, $this->created_at_to);
        $this->normalizeDateRange($this->purchase_date_from, $this->purchase_date_to);
        $this->normalizeDateRange($this->application_date_from, $this->application_date_to);
        return Model::beforeValidate();
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
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'manufacturer_id' => $this->manufacturer_id,
            'condition_id' => $this->condition_id,
            'repair_status_id' => $this->repair_status_id,
            'warranty_status_id' => $this->warranty_status_id,
            'status_id' => $this->status_id
        ]);

        $query
            ->andFilterWhere(['between', 'created_at', $this->created_at_from, $this->created_at_to])
            ->andFilterWhere(['between', 'purchase_date', $this->purchase_date_from, $this->purchase_date_to])
            ->andFilterWhere(['between', 'application_date', $this->application_date_from, $this->application_date_to]);

        $query
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'equipment', $this->equipment])
            ->andFilterWhere(['like', 'brand_model_name', $this->brand_model_name])
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
            ->andFilterWhere(['like', 'bid_manufacturer_number', $this->bid_manufacturer_number])
            ->andFilterWhere(['like', 'defect', $this->defect])
            ->andFilterWhere(['like', 'diagnostic', $this->diagnostic]);


        return $dataProvider;
    }

    private function normalizeDateRange(&$from, &$to)
    {
        if (empty($from)) {
            $from = null;
            $to = null;
            return;
        }

        if (empty($to)) {
            $to = $from;
        }

        $from = strtotime("midnight", strtotime($from));
        $to = strtotime("tomorrow", strtotime($to)) - 1;

        $from = date('Y-m-d H:i:s', $from);
        $to = date('Y-m-d H:i:s', $to);
    }
}
