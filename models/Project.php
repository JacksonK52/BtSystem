<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $description
 * @property int $updated_by
 * @property int $created_by
 * @property int $status
 * @property string|null $updated_at
 * @property string $created_at
 *
 * @property Bug[] $bugs
 * @property User $createdBy
 * @property Team[] $teams
 * @property User $updatedBy
 */
class Project extends \yii\db\ActiveRecord
{
    // Constant Value for Status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    const STATUS_COMPLETED = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'updated_by', 'created_by'], 'required'],
            [['description'], 'string'],
            [['updated_by', 'created_by', 'status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['slug', 'title'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
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
            'description' => 'Description',
            'updated_by' => 'Updated By',
            'created_by' => 'Created By',
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
        return ($status === $this::STATUS_INACTIVE ? 'Inactive' : ($status === $this::STATUS_ACTIVE ? 'Active' : ($status === $this::STATUS_COMPLETED ? 'Completed' : 'Deleted')));
    }

    /**
     * Get status equivalent status color
     * ---------------------------------------------------------
     * This function will return its status equivalent color.
     */
    public function getStatusColor($status)
    {
        return ($status === $this::STATUS_INACTIVE ? 'text-warning' : ($status === $this::STATUS_ACTIVE || $status === $this::STATUS_COMPLETED ? 'text-success' : 'text-danger'));
    }

    /**
     * Gets query for [[Bugs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBugs()
    {
        return $this->hasMany(Bug::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Teams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }
}
