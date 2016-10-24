<?php

namespace app\controllers;

use app\models\Change;
use app\controllers\PageTrait;
use Yii;
use app\models\Page;
use app\models\PageSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Zelenin\yii\extensions\Rss\RssView;
use Zelenin\Feed;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */

    use PageTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'check' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $changesDataProvider = new ActiveDataProvider([
            'query' => Change::find()->where(['page_id' => $id])->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'changes' => $changesDataProvider
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Generates RSS feed with diffs for all pages.
     * @return mixed
     */
    public function actionRss()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Change::find()->innerJoinWith(['page'])->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 100
            ],
        ]);

        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();

        $headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

        echo RssView::widget([
            'dataProvider' => $dataProvider,
            'channel' => [
                'title' => function ($widget, Feed $feed) {
                    $feed->addChannelTitle(Yii::$app->params['name']);
                },
                'link' => Url::toRoute('/', true),
                'description' => function ($widget, Feed $feed) {
                    $feed->addChannelDescription(Yii::$app->params['name']);
                },
            ],
            'items' => [
                'title' => function ($model, $widget, Feed $feed) {
                    return empty($model->page->description) ? $model->page->url : $model->page->description;
                },
                'description' => function ($model, $widget, Feed $feed) {
                    $formatter = \Yii::$app->formatter;
                    return $formatter->asNtext(empty($model->status) ? $model->diff : $model->status);
                },
                'link' => function ($model, $widget, Feed $feed) {
                    return $model->page->url;
                },
                'guid' => function ($model, $widget, Feed $feed) {
                    return Url::toRoute(['change/view', 'id' => $model->id], true);
                },
                'pubDate' => function ($model, $widget, Feed $feed) {
                    $date = \DateTime::createFromFormat('U', $model->updated_at);
                    return $date->format(DATE_RSS);
                }
            ]
        ]);
    }

    public function actionCheck($id) {
        $page = $this->findModel($id);

        $diff = $this->makeChange($page);
        $this->saveChange($page, $diff);

        return $this->redirect(['page/view', 'id' => $id]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
