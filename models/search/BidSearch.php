<?php

namespace app\models\search;

use app\helpers\constants\Constants;
use phpDocumentor\Reflection\Types\Parent_;
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
    public $date_manufacturer_from;
    public $date_manufacturer_to;
    public $date_completion_from;
    public $date_completion_to;
    public $restrictions;
    public $client_name;
    public $client_phone;
    public $client_email;
    public $client_type;
    public $client_manufacturer_name;
    public $client_manufacturer_phone;
    public $client_manufacturer_email;
    public $client_manufacturer_type;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'manufacturer_id',
                'status_id',
                'warranty_status_id',
                'repair_status_id',
                'user_id',
                'master_id'
            ],
                'integer'],
            [[
                'is_warranty_defect',
                'is_repair_possible',
                'is_for_warranty',
            ],
                'boolean'],
            [[
                'brand_name',
                'equipment',
                'brand_model_name',
                'composition_name',
                'serial_number',
                'vendor_code',
                'client_id',
                'client_name',
                'client_phone',
                'client_email',
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
                'diagnostic',
                'date_manufacturer_from',
                'date_manufacturer_to',
                'date_completion_from',
                'date_completion_to',
                'client_type',
                'comment',
                'repair_recommendations',
                'saler_name',
                'diagnostic_manufacturer',
                'defect_manufacturer',
                'client_manufacturer_name',
                'client_manufacturer_phone',
                'client_manufacturer_email',
                'client_manufacturer_type'
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'client_name' => 'Клиент',
            'client_phone' => 'Телефон клиента',
            'client_email' => 'Email клиента',
            'client_type' => 'Тип клиента',
            'client_manufacturer_name' => 'Клиент для представительства',
            'client_manufacturer_phone' => 'Телефон клиента для представительства',
            'client_manufacturer_email' => 'Email клиента для представительства',
            'client_manufacturer_type' => 'Тип клиента для представительства',
        ]);
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
        /* @todo search by client */

        $query = Bid::find()
            ->distinct()
            ->joinWith(['client', 'client.clientPhones', 'clientManufacturer client_manufacturer'], false)
            ->with([
                'brand', 'brandCorrespondence', 'brandModel', 'manufacturer', 'repairStatus',
                'status', 'master', 'workshop', 'warrantyStatus'
                ])
            ->where($this->restrictions)
            ->orderBy('created_at DESC');

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
            'is_warranty_defect' => $this->is_warranty_defect,
            'is_repair_possible' => $this->is_repair_possible,
            'is_for_warranty' => $this->is_for_warranty,
        ]);

        if (!empty($this->repair_status_id)) {
            $query->andWhere([
                'repair_status_id' => $this->repair_status_id != Constants::EMPTY_VALUE_ID ? $this->repair_status_id : null,
            ]);
        }

        if (!empty($this->manufacturer_id)) {
            $query->andWhere([
                'manufacturer_id' => $this->manufacturer_id != Constants::EMPTY_VALUE_ID ? $this->manufacturer_id : null,
            ]);
        }

        if (!empty($this->master_id)) {
            $query->andWhere([
                'master_id' => $this->master_id != Constants::EMPTY_VALUE_ID ? $this->master_id : null,
            ]);
        }

        if (!empty($this->status_id)) {
            $query->andWhere([
                'status_id' => $this->status_id != Constants::EMPTY_VALUE_ID ? $this->status_id : null,
            ]);
        }

        if (!empty($this->treatment_type)) {
            $query->andWhere([
                'treatment_type' => $this->treatment_type != Constants::EMPTY_VALUE_ID ? $this->treatment_type : null,
            ]);
        }

        $query->andFilterWhere([
            'warranty_status_id' => $this->warranty_status_id,
            'bid.client_id' => $this->client_id,
            'client.client_type' => $this->client_type,
            'client_manufacturer.client_type' => $this->client_manufacturer_type
        ]);

        $query
            ->andFilterWhere(['between', 'created_at', $this->created_at_from, $this->created_at_to])
            ->andFilterWhere(['between', 'purchase_date', $this->purchase_date_from, $this->purchase_date_to])
            ->andFilterWhere(['between', 'application_date', $this->application_date_from, $this->application_date_to])
            ->andFilterWhere(['between', 'date_manufacturer', $this->date_manufacturer_from, $this->date_manufacturer_to])
            ->andFilterWhere(['between', 'date_completion', $this->date_completion_from, $this->date_completion_to]);

        $query
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'condition_name', $this->condition_name])
            ->andFilterWhere(['like', 'equipment', $this->equipment])
            ->andFilterWhere(['like', 'brand_model_name', $this->brand_model_name])
            ->andFilterWhere(['like', 'composition_name', $this->composition_name])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'vendor_code', $this->vendor_code])
            ->andFilterWhere(['like', 'warranty_number', $this->warranty_number])
            ->andFilterWhere(['like', 'bid_number', $this->bid_number])
            ->andFilterWhere(['like', 'bid_1C_number', $this->bid_1C_number])
            ->andFilterWhere(['like', 'bid_manufacturer_number', $this->bid_manufacturer_number])
            ->andFilterWhere(['like', 'defect', $this->defect])
            ->andFilterWhere(['like', 'diagnostic', $this->diagnostic])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'repair_recommendations', $this->repair_recommendations])
            ->andFilterWhere(['like', 'saler_name', $this->saler_name])
            ->andFilterWhere(['like', 'diagnostic_manufacturer', $this->diagnostic_manufacturer])
            ->andFilterWhere(['like', 'defect_manufacturer', $this->defect_manufacturer])
            ->andFilterWhere(['like', 'client_manufacturer.name', $this->client_manufacturer_name])
            ->andFilterWhere(['like', 'client_manufacturer.email', $this->client_manufacturer_email])
            ->andFilterWhere(['like', 'client_manufacturer.client_phone.phone', $this->client_manufacturer_phone])
            ->andFilterWhere(['like', 'client.name', $this->client_name])
            ->andFilterWhere(['like', 'client.email', $this->client_email])
            ->andFilterWhere(['like', 'client_phone.phone', $this->client_phone]);

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
