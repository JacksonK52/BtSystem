<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "favorite_list".
 *
 * @property int $id
 * @property string $slug
 * @property int $user_id
 * @property int $favorite_id
 * @property int $status
 * @property string|null $updated_at
 * @property string $created_at
 *
 * @property Favorite $favorite
 * @property User $user
 */
class FavoriteList extends \yii\db\ActiveRecord
{
    // Constant Value for Status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorite_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'user_id', 'favorite_id'], 'required'],
            [['user_id', 'favorite_id', 'status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['slug'], 'string', 'max' => 255],
            [['favorite_id'], 'exist', 'skipOnError' => true, 'targetClass' => Favorite::class, 'targetAttribute' => ['favorite_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'user_id' => 'User ID',
            'favorite_id' => 'Favorite ID',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Get status equivalent status name
     * --------------------------------------------------------
     * This function will return its status equivalent name.
     */
    public function getStatus($status)
    {
        return ($status === $this::STATUS_INACTIVE ? 'Inactive' : ($status === $this::STATUS_ACTIVE ? 'Active' : 'Deleted'));
    }

    /**
     * Get status equivalent status color
     * ---------------------------------------------------------
     * This function will return its status equivalent color.
     */
    public function getStatusColor($status)
    {
        return ($status === $this::STATUS_INACTIVE ? 'text-warning' : ($status === $this::STATUS_ACTIVE ? 'text-success' : 'text-danger'));
    }

    /**
     * Gets query for [[Favorite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorite()
    {
        return $this->hasOne(Favorite::class, ['id' => 'favorite_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
