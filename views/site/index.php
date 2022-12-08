<?php

/** @var yii\web\View $this */
/** @var app\models\FavoriteList $favLists */
/** @var Integer $countAdmin */
/** @var Integer $countTeamLeader */
/** @var Integer $countDeveloper */
/** @var Integer $countTester */

use app\models\User;
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
                <h1 class="m-0">Dashboard</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <?php if (empty($favLists)) : ?>
            <!-- If Favlist is empty -->
            <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center mb-0">Welcome to BtSystem</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- If favlist is not empty -->
            <div class="row">
                <?php foreach ($favLists as $favList) : ?>
                    <?php if ($favList->favorite->title == 'User') : ?>
                        <!-- User Information -->
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header bg-gradient-info">
                                    <h3 class="card-title"><i class="<?= $favList->favorite->icon ?>"></i> <?= $favList->favorite->title ?></h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- User Count -->
                                        <div class="col-6 d-flex align-items-center">
                                            <p class="text-justify">
                                                Displaying an overview information of user account.
                                                For In-depth review and functionality of user
                                                information go through user panel.
                                            </p>
                                        </div>
                                        <!-- User Stat -->
                                        <div class="col-6">
                                            <canvas id="userDonutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($favList->favorite->title == "Project") : ?>
                        <!-- Project Information -->
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header bg-gradient-primary">
                                    <h3 class="card-title"><i class="<?= $favList->favorite->icon ?>"></i> <?= $favList->favorite->title ?></h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p>Test</p>
                                        </div>
                                        <div class="col-6">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="/plugins/chartJs/chart.min.js"></script>
<script>
    //-------------
    //- DONUT CHART FOR USER -
    //-------------
    const donutChartCanvas = document.getElementById('userDonutChart');

    <?php if (Yii::$app->user->identity->role === User::ROLE_SUPERADMIN) : ?>
        // Super-admin dataset
        const data = {
            labels: [
                'Admin',
                'Team Leader',
                'Developer',
                'Tester'
            ],
            datasets: [{
                label: 'User Information',
                data: [<?= "{$countAdmin}, {$countTeamLeader}, {$countDeveloper}, {$countTester}" ?>],
                backgroundColor: [
                    '#f94144', '#f3722c', '#118ab2', '#f9c74f'
                ],
                hoverOffset: 4
            }]
        }
    <?php else : ?>
        // Admin Dataset
        const data = {
            labels: [
                'Team Leader',
                'Developer',
                'Tester'
            ],
            datasets: [{
                label: 'User Information',
                data: [<?= "{$countTeamLeader}, {$countDeveloper}, {$countTester}" ?>],
                backgroundColor: [
                    '#f94144', '#f3722c', '#118ab2', '#f9c74f'
                ],
                hoverOffset: 4
            }]
        }
    <?php endif; ?>

    new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: data,
    })
</script>