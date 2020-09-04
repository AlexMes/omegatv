<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "tariff_customer".
 *
 * @property int $id_customer
 * @property int $id_tariff
 * @property int $active
 */
class Tariff_customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tariff_customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_customer', 'id_tariff'], 'required'],
            [['id_customer', 'id_tariff', 'active'], 'integer'],
            [['id_customer', 'id_tariff'], 'unique', 'targetAttribute' => ['id_customer', 'id_tariff']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_customer' => 'Id Customer',
            'id_tariff' => 'Id Tariff',
            'active' => 'Active',
        ];
    }
}
