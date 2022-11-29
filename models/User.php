<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $slug
 * @property int $salt
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $auth_key
 * @property string $token_id
 * @property string $img_location
 * @property int $verify
 * @property int $role
 * @property int $status
 * @property string|null $updated_at
 * @property string $created_at
 *
 * @property Bug[] $bugs
 * @property Bug[] $bugs0
 * @property Images[] $images
 * @property Images[] $images0
 * @property Profile[] $profiles
 * @property Profile[] $profiles0
 * @property Profile[] $profiles1
 * @property Project[] $projects
 * @property Project[] $projects0
 * @property Scheduling[] $schedulings
 * @property Scheduling[] $schedulings0
 * @property TeamMember[] $teamMembers
 * @property TeamMember[] $teamMembers0
 * @property TeamMember[] $teamMembers1
 * @property Team[] $teams
 * @property Team[] $teams0
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @var $confirm_password
     * Defining confirm_password field
     *
     * @var $rememberMe
     * Defining rememberMe field
     */
    public $confirm_password;
    public $rememberMe = true;

    // Constant Value for Verify
    const VERIFY_NOT = 0;
    const VERIFY_YES = 1;

    // Const Value for Role
    const ROLE_SUPERADMIN = 0;
    const ROLE_ADMIN = 1;
    const ROLE_TEAM_LEADER = 2;
    const ROLE_TESTER = 3;
    const ROLE_DEVELOPER = 4;

    // Constant Value for Status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;



    public static function tableName()
    {
        return '{{%user}}';
    }

     /**
     * @var $confirm_password
     * confirm_password is not defined in the database, yet it act like a defined field
     *
     * @rule Defining rules
     * Since tbl_user has notNull fields, we need to defined them as required.
     * Email field must meet mail validation criteria
     * Password and confirm_password field value must be exactly similar else pop the error msg. We can change the error msg
     *
     * @return 'true' if all rules are satisfied
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['slug', 'name', 'email', 'password', 'salt', 'confirm_password'], 'required'],
            [['salt', 'verify', 'role', 'status'], 'integer'],
            // name must be at least 2 characters long
            [['name'], 'string', 'min' => 2],
            [['email'], 'string'],
            [['img_location'], 'image', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png', 'minWidth' => 500, 'maxWidth' => 500, 'minHeight' => 500, 'maxHeight' => 500, "maxSize" => 307200],
            // password an confirm_password must be same
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => "Password doesn't match"],
            // Password must be at least 6 characters long
            [['password', 'confirm_password'], 'string', 'min' => 6],
        ];
    }


    /**
     * Finds an identity by the given ID.
     * -----------------------------------------------
     * It return all the data of that particular user even its password and auth_key.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * -----------------------------------------------
     * It return all the data of that particular user even its password and auth_key.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token_id' => $token]);
    }

    /**
     * Get Current User Id
     * -------------------------------------------------------
     * This Function will return currently login user id
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Current User Auth Key
     * -----------------------------------------------------
     * This function will return currently login user auth key
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validate Auth Key
     * -------------------------------------------------------
     * This function will validate auth by comparing current user auth and parameter auth.
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validate Email base on parameter
     * --------------------------------------------------------
     * Check if email number already exist or not.
     */
    public function validateEmail($email)
    {
        $user = User::find()->andWhere("email = :e and status != :deleted", ["e" => $email, "deleted" => $this::STATUS_DELETED])->one();
        return (empty($user) ? true : false);
    }

    /**
     * Get verify equivalent verify name
     * --------------------------------------------------------
     * This function will return its verify equivalent name.
     */
    public function getVerify($verify)
    {
        return ($verify === $this::VERIFY_YES ? 'Verified' : 'Not Verify');
    }

    /**
     * Get role equivalent role name
     * --------------------------------------------------------
     * This function will return its role equivalent name.
     */
    public function getRole($role)
    {
        return ($role === $this::ROLE_SUPERADMIN ? 'Super Admin' : ($role === $this::ROLE_ADMIN ? 'Admin' : ($role === $this::ROLE_TEAM_LEADER ? 'Team Leader' : ($role === $this::ROLE_TESTER ? 'Tester' : 'Developer'))));
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
     * Validates password
     * ---------------------------------------------------------
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $_password = $password.$this->salt;
        return password_verify($_password, $this->password);
    }

    /**
     * Find User information by email number
     * --------------------------------------------------------
     */
    public static function findByEmail($email)
    {
        return User::find()->andWhere("email = :m and status = :active", ['m' => $email, 'active' => User::STATUS_ACTIVE])->one();
    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->token_id = Yii::$app->security->generateRandomString();
            }
            if (isset($this->password)) {
                $this->password = password_hash($this->password.$this->salt, PASSWORD_DEFAULT); //bCrypt Encryption with salt;
                return parent::beforeSave($insert);
            }
        }
    }

    /**
     * Gets query for [[Bugs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBugs()
    {
        return $this->hasMany(Bug::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Bugs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBugs0()
    {
        return $this->hasMany(Bug::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Images::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Images0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages0()
    {
        return $this->hasMany(Images::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Profiles0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles0()
    {
        return $this->hasMany(Profile::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Profiles1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles1()
    {
        return $this->hasMany(Profile::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Projects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Projects0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects0()
    {
        return $this->hasMany(Project::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Schedulings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings()
    {
        return $this->hasMany(Scheduling::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Schedulings0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings0()
    {
        return $this->hasMany(Scheduling::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[TeamMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMembers()
    {
        return $this->hasMany(TeamMember::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[TeamMembers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMembers0()
    {
        return $this->hasMany(TeamMember::class, ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[TeamMembers1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeamMembers1()
    {
        return $this->hasMany(TeamMember::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Teams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::class, ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Teams0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeams0()
    {
        return $this->hasMany(Team::class, ['updated_by' => 'id']);
    }
}
