<?php
namespace frontend\controllers;

use frontend\models\Cities;
use frontend\models\Profiles;
use frontend\models\Users;
use Yii;
use yii\authclient\clients\VKontakte;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function actionLogin()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
    }

    public function actionChangecity(): void
    {
        $session = Yii::$app->session;
        if (Yii::$app->request->isAjax) {
            $id = (int)Yii::$app->request->post('id');
            Yii::$app->session->set('city_id', $id);
        }
    }

    public function onAuthSuccess(VKontakte $client): Response
    {
        $defaultCityId = 667;

        $attributes = $client->getUserAttributes();
        $userVkId = Users::find()->where(['vk_id' => $attributes['id']])->one();
        $userEmailVk = Users::find()->where(['email' => $attributes['email']])->one();
        if (!$userVkId && $userEmailVk) {
            $userEmailVk->vk_id = $attributes['id'];
            $userEmailVk->save();
        } elseif (!$userVkId) {
            $user = new Users();
            $user->vk_id = $attributes['id'];
            $user->email = $attributes['email'];
            $user->name = $attributes['first_name'] . ' ' . $attributes['last_name'];
            $user->setPassword(Yii::$app->security->generateRandomString(8));
            if (isset($attributes['city']['title'])) {
                $user->city_id = Cities::find()
                        ->where(['name' => $attributes['city']['title']])->one()->id ?? $defaultCityId;
            } else {
                $user->city_id = $defaultCityId;
            }

            if (!($user->validate() && $user->save())) {
                throw new ServerErrorHttpException('Попробуйте позже');
            }
            $newProfile = new Profiles();
            $newProfile->user_id = $user->id;
            $newProfile->city_id = $user->city_id;
            $newProfile->save();
        }
        Yii::$app->user->login($userVkId ?? $userEmailVk ?? $user);
        return $this->redirect(Url::to(['/tasks']));
    }
}
