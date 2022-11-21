<?php

use yii\helpers\Url;

/** @var String $name */
/** @var String $token */
/** @var String $authkey */

?>

<p>Hello, <?= $name ?></p>
<p>As per your request we are providing you a link to reset your password. Click on the link below to reset your password</p>
<p>
    <a href="<?= Yii::$app->request->hostInfo . '/user/resetpass?token=' . $token . '&auth=' . $authkey ?>" style="text-decoration: none; background-color: #1B79DD; border-radius: 10px; color: white; padding: 3px 4px;">
        Reset Password
    </a><br>
</p>
<p>
    If you didn't do it please ignore this mail and deleted as soon as possible.
</p><br>
<p>Regards</p>
<p>BtSystem Team</p>