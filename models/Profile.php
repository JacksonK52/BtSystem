<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string $slug
 * @property int $user_id
 * @property string|null $emp_id
 * @property string|null $mobile
 * @property string|null $address_line_one
 * @property string|null $address_line_two
 * @property string|null $landmark
 * @property string|null $district
 * @property int|null $pincode
 * @property string|null $state
 * @property int $updated_by
 * @property int $created_by
 * @property int $status
 * @property string|null $updated_at
 * @property string $created_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
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
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'user_id', 'updated_by', 'created_by'], 'required'],
            [['user_id', 'pincode', 'updated_by', 'created_by', 'status'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['slug', 'address_line_one', 'address_line_two'], 'string', 'max' => 255],
            [['emp_id', 'district', 'state'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 15],
            [['landmark'], 'string', 'max' => 200],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
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
            'user_id' => 'User',
            'emp_id' => 'Emp ID',
            'mobile' => 'Mobile',
            'address_line_one' => 'Address Line One',
            'address_line_two' => 'Address Line Two',
            'landmark' => 'Landmark',
            'district' => 'District',
            'pincode' => 'Pincode',
            'state' => 'State',
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
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
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
