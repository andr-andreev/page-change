<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property integer $is_active
 * @property integer $category_id
 * @property string $description
 * @property string $url
 * @property string $filter_from
 * @property string $filter_to
 * @property string $last_content
 * @property string $last_status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Page extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            ['url', 'url', 'defaultScheme' => 'http'],
            [['category_id'], 'integer'],
            [['is_active'], 'in', 'range' => [static::STATUS_ACTIVE, static::STATUS_INACTIVE]],
            [['last_content', 'last_status'], 'string'],
            [['description', 'filter_from', 'filter_to'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_active' => 'Active',
            'category_id' => 'Category',
            'description' => 'Title',
            'url' => 'Url',
            'filter_from' => 'Filter From',
            'filter_to' => 'Filter To',
            'last_content' => 'Last Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function find()
    {
        return new PageQuery(get_called_class());
    }


    public function getChanges()
    {
        return $this->hasMany(Change::className(), ['page_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function isActive()
    {
        return $this->is_active === static::STATUS_ACTIVE;
    }
}
