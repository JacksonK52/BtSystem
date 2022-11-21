<?php

use yii\helpers\Url;

/** @var String $name */
/** @var String $token */
/** @var String $authkey */

?>

<p>Hello, <?= $name ?></p>
<p>Thank you for choosing BtSystem. To verify your email click the link below</p>
<p>
    <a href="<?= Yii::$app->request->hostInfo . '/user/verify-email?token=' . $token . '&auth=' . $authkey ?>" style="text-decoration: none; background-color: #1B79DD; border-radius: 10px; color: white; padding: 3px 4px;">
        Verify Email
    </a><br>
</p>
<p>
    If you didn't do it please ignore this mail and deleted as soon as possible.
</p><br>
<p>Regards</p>
<p>BtSystem Team</p>