<?php

namespace app\models;

use yii\base\Model;

class YearForm extends Model
{
    public $year;

    public function rules()
    {
        return [
            [['year'], 'required'],
            [['year'], 'integer'],
            [['year'], 'match', 'pattern' => '/^[0-9]{4}$/'], // Ensures the year is a four-digit number
        ];
    }

    public function attributeLabels()
    {
        return [
            'year' => 'Год',
        ];
    }
}
