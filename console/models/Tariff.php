<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "tariff".
 *
 * @property int $id
 * @property int $id_company
 * @property string $name
 */
class Tariff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tariff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_company', 'name'], 'required'],
            [['id_company'], 'integer'],
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
            'id_company' => 'Id Company',
            'name' => 'Name',
        ];
    }

    /**
     * Список тарифов и количество активных клиентов подписанных на эти тарифы (по компаниям)
     */
    public static function getCountActiveCustomersByTariffs(){
        return Yii::$app->db->createCommand('
                    select t.id_company id_company, t.id id_tariff, t.name name_tariff, count(tc.id_customer) count_customer 
                    from tariff_customer tc
                    join tariff t on t.id = tc.id_tariff
                    where tc.active = 1
                    group by tc.id_tariff')
            ->queryAll();
    }
}
