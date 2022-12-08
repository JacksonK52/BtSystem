<?php

/** @var yii\web\View $this */
/** @var app\models\Favorite $favorites */

use app\models\FavoriteList;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Bug Tracking System';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">All Features</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/site']) ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">All Features</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <?php foreach ($favorites as $favorite) : ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card-body bg-white rounded">
                        <form class="form-inline">
                            <div class="form-group">
                                <input type="checkbox" onclick="setFavorite(`<?= $favorite->id ?>`)" class="form-check-input mr-4" name="<?= $favorite->slug ?>" id="<?= $favorite->slug ?>" <?= (empty($favorite->favoriteLists[0]->user_id) ? '' : (($favorite->favoriteLists[0]->user_id == Yii::$app->user->getId()) && ($favorite->favoriteLists[0]->status === FavoriteList::STATUS_ACTIVE) ? 'checked' : '')) ?>>
                                <label for="<?= $favorite->slug ?>"><i class="<?= $favorite->icon ?> mr-2"></i> <?= $favorite->title ?></label>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    // Function to set user favorite using Ajax
    const setFavorite = (id) => {
        // Check Param
        if(id == '') {
            showToastr('danger', 'Missing Required Information!');
            return false;
        }

        $.ajax({
            url: "<?= Url::to(['/site/add-favorite']) ?>",
            type: 'post',
            data: {
                id: id,
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            error: function() {
                showToastr('danger', 'Fail To Set Favorite!');
                return false;
            }
        }).done((data) => {
            showToastr(data.status, data.msg);
        });
    }
</script>