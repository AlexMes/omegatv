<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $name
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Количество всех клиентов, подписанных хоть на один тариф (по компаниям)
     */
    public static function getCountAllByCompanys(){
        return Yii::$app->db->createCommand('
                    select t.id_company id_company, count(tc.id_customer) count_customer 
                    from tariff_customer tc
                    join tariff t on t.id = tc.id_tariff
                    group by t.id_company')
            ->queryAll();
    }

    /**
     * Количество неактивных клиентов, подписанных на тарифы (по компаниям)
     */
    public static function getCountNoActiveByCompanys(){
        return Yii::$app->db->createCommand('
                    select t.id_company id_company, count(tc.id_customer) count_customer 
                    from tariff_customer tc
                    join tariff t on t.id = tc.id_tariff
                    where tc.active = 0
                    group by t.id_company')
            ->queryAll();
    }

    /**
     * Список активных клиентов и тарифы, на которые они подписаны
     */
    public static function getActiveTariff(){
        return Yii::$app->db->createCommand('
                    select t.id_company id_company, c.id id_customer, c.name name_customer, t.name name_tariff 
                    from tariff_customer tc
                    join tariff t on t.id = tc.id_tariff
                    join customer c on c.id = tc.id_customer
                    where tc.active = 1')
            ->queryAll();
    }



}
