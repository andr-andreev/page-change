<?php

namespace app\controllers;

use app\controllers\PageChange;
use app\models\Category;
use app\models\Change;
use app\models\Page;
use app\models\PageSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\di\Container;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Zelenin\Feed;
use Zelenin\yii\extensions\Rss\RssView;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */

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
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $changesDataProvider = new ActiveDataProvider([
            'query' => $model->getChanges()->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10
            ],
        ]);


        return $this->render('view', [
            'model' => $model,
            'changes' => $changesDataProvider
        ]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
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

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        $model->is_active = Page::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->actionCheck($model->id);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => ArrayHelper::map(Category::find()->orderBy('title')->all(), 'id', 'title')
        ]);
    }

    public function actionCheck($id)
    {
        $page = $this->findModel($id);

        $container = new Container;

        /** @var \app\components\pagechange\PageChange $pageChange */
        $pageChange = $container->get(\app\components\pagechange\PageChange::className(), [$page]);

        $diff = $pageChange->makeChange();
        $pageChange->saveChange($diff);

        return $this->redirect(['page/view', 'id' => $id]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
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
                'categories' => ArrayHelper::map(Category::find()->orderBy('title')->all(), 'id', 'title')
            ]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
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
            'query' => Change::find()->innerJoinWith(['page', 'category'])->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => Yii::$app->params['rssItemsCount']
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
                    $categoryPart = $model->category->title . ' / ';
                    if (empty($model->page->description)) {
                        return $categoryPart . $model->page->url;
                    }

                    return $categoryPart . $model->page->description;
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
}
