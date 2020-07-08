<?php

use TaskForce\models\Task;
use yii\helpers\Html;

$currentUser = Yii::$app->user->getIdentity();
?>

    <div class="main-container page-container">
        <section class="content-view">
            <div class="user__card-wrapper">
                <div class="user__card">
                    <img src="/uploads/<?=$user->profiles->avatar ?? '../img/man-glasses.jpg';?>" width="120" height="120" alt="Аватар пользователя">
                    <div class="content-view__headline">
                        <h1><?= $user->name ?></h1>
                        <p>Россия, <?= $user->city->name ?>, 30 лет</p>
                        <div class="profile-mini__name five-stars__rate">
                            <span></span><span></span><span></span><span></span><span class="star-disabled"></span>
                            <b>4.25</b>
                        </div>
                        <b class="done-task">Выполнил 5 заказов</b><b class="done-review">Получил <?= count($user->reviews) ?> отзывов</b>
                    </div>
                    <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                        <span>Был на сайте <?= date_format(date_create($user->profiles->last_visit), 'd-m-Y'); ?></span>
                        <a href="#"><b></b></a>
                    </div>
                </div>
                <div class="content-view__description">
                    <p><?= $user->profiles->about ?></p>
                </div>
                <div class="user__card-general-information">
                    <div class="user__card-info">
                        <h3 class="content-view__h3">Специализации</h3>
                        <div class="link-specialization">
                            <?php foreach ($user->userCategories as $category): ?>
                                <?= Html::a($category->category->name, ['/users', 'categories[]' => $category->id],
                                    ['class' => 'link-regular']) ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!$user->profiles->hide_contact_info) : ?>
                        <h3 class="content-view__h3">Контакты</h3>
                        <div class="user__card-link">
                            <a class="user__card-link--tel link-regular" href="#"><?= $user->profiles->phone_number ?></a>
                            <a class="user__card-link--email link-regular" href="#"><?= $user->email ?></a>
                            <a class="user__card-link--skype link-regular" href="#"><?= $user->profiles->skype ?></a>
                        </div>
                        <?php endif ?>
                        <?php if (($user->profiles->hide_contact_info)
                            && $currentUser->role === Task::ROLE_CUSTOMER) : ?>
                            <h3 class="content-view__h3">Контакты</h3>
                            <div class="user__card-link">
                                <a class="user__card-link--tel link-regular" href="#"><?= $user->profiles->phone_number ?></a>
                                <a class="user__card-link--email link-regular" href="#"><?= $user->email ?></a>
                                <a class="user__card-link--skype link-regular" href="#"><?= $user->profiles->skype ?></a>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="user__card-photo">
                        <?php if (!empty($user->profiles->userPictures)) : ?>
                        <h3 class="content-view__h3">Фото работ</h3>
                            <?php foreach ($user->profiles->userPictures as $file) : ?>
                                <a href="/uploads/<?=$file->name?>">
                                    <img src="/uploads/<?=$file->name?>" width="85" height="86" alt="Фото работы">
                                </a>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="content-view__feedback">
                <h2>Отзывы<span>(<?= count($user->reviews) ?>)</span></h2>
                <div class="content-view__feedback-wrapper reviews-wrapper">
                    <?php foreach ($user->reviews as $review): ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание <a href="#" class="link-regular">«Выгулять моего боевого петуха»</a></p>
                        <div class="card__review">
                            <a href="#"><img src="../../img/man-glasses.jpg" width="55" height="54"></a>
                            <div class="feedback-card__reviews-content">
                                <p><?= Html::a($review->user->name, ['/user/view'],
                                        ['class' => 'link-regular']) ?></p>
                                <p class="review-text">
                                    <?= $review->review ?>
                                </p>
                            </div>
                            <div class="card__review-rate">
                                <p class="five-rate big-rate"><?= $review->rating ?><span></span></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <section class="connect-desk">
            <div class="connect-desk__chat">

            </div>
        </section>
    </div>
