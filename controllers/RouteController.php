<?php
namespace denchotsanov\yii2rbac\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use denchotsanov\yii2rbac\models\RouteModel;

class RouteController extends Controller
{
    /**
     * @var array route model class
     */
    public $modelClass = [
        'class' => RouteModel::class,
    ];
    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get', 'post'],
                    'create' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                    'refresh' => ['post'],
                ],
            ],
            'contentNegotiator' => [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['assign', 'remove', 'refresh'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * Lists all Route models.
     *
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $model = Yii::createObject($this->modelClass);
        return $this->render('index', ['routes' => $model->getAvailableAndAssignedRoutes()]);
    }

    /**
     * Assign routes
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function actionAssign(): array
    {
        $routes = Yii::$app->getRequest()->post('routes', []);
        $model = Yii::createObject($this->modelClass);
        $model->addNew($routes);
        return $model->getAvailableAndAssignedRoutes();
    }

    /**
     * Remove routes
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRemove(): array
    {
        $routes = Yii::$app->getRequest()->post('routes', []);
        $model = Yii::createObject($this->modelClass);
        $model->remove($routes);
        return $model->getAvailableAndAssignedRoutes();
    }

    /**
     * Refresh cache of routes
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRefresh(): array
    {
        $model = Yii::createObject($this->modelClass);
        $model->invalidate();
        return $model->getAvailableAndAssignedRoutes();
    }
}