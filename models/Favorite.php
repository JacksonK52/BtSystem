<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "favorite".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $icon
 * @property int $access_level
 * @property int $status
 * @property string|null $updated_at
 * @property string $created_at
 * 
 * @property FavoriteList[] $favoriteLists
 */
class Favorite extends \yii\db\ActiveRecord
{
    // Constant Value for Access Level
    const ACCESS_LEVEL_SUPERADMIN = 0;
    const ACCESS_LEVEL_ADMIN = 1;
    const ACCESS_LEVEL_TEAMLEADER = 2;
    const ACCESS_LEVEL_EVERYONE = 3;

    // Constant Value for Status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'icon'], 'required'],
            [['access_level', 'status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['slug', 'title'], 'string', 'max' => 255],
            [['icon'], 'string', 'max' => 20],
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
            'title' => 'Title',
            'icon' => 'Icon',
            'access_level' => 'Access Level',
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
     * Get Access Level equivalent access name
     * ---------------------------------------------------------
     * This function will return its access equivalent name.
     */
    public function getAccessLevel($access_level)
    {
        return ($access_level === $this::ACCESS_LEVEL_SUPERADMIN ? 'Super Admin' : ($access_level === $this::ACCESS_LEVEL_ADMIN ? 'Admin' : ($access_level === $this::ACCESS_LEVEL_TEAMLEADER ? 'Team Leader' : 'Everyone')));
    }

    /**
     * Gets query for [[FavoriteLists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoriteLists()
    {
        return $this->hasMany(FavoriteList::class, ['favorite_id' => 'id']);
    }
}
