<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use app\models\BookHasAuthor;
use app\models\BookSearch;
use app\models\Subscription;
use app\models\YearForm;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Json;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [

                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ],

            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'top-authors', 'view', "subscribe"],
                            'allow' => true,
                            'roles' => ['?', '@'],
                        ],
                        [
                            'actions' => ['create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],


                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Book();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->photoFile = UploadedFile::getInstance($model, 'photoFile');

                if ($model->save() && $model->upload()) {
                    $this->saveAuthors($model->id, \Yii::$app->request->post("Book")["authors"]);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        $authors = Author::find()->all();
        $model->loadDefaultValues();

        return $this->render('create', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->photoFile = UploadedFile::getInstance($model, 'photoFile');
            $model->save();
            if ($model->photoFile) {
                $model->upload();
            }
            $this->saveAuthors($model->id, \Yii::$app->request->post("Book")["authors"]);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $authors = Author::find()->all();
        $selectedAuthors = $model->getAuthors()->select('id')->column();

        return $this->render('update', [
            'model' => $model,
            'authors' => $authors,
            'selectedAuthors' => $selectedAuthors,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        $model->deletePhoto();


        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Summary of saveAuthors
     * @param mixed $bookId
     * @param mixed $authorIds
     * @return void
     */
    protected function saveAuthors($bookId, $authorIds)
    {
        if (!is_array($authorIds)) {
            return;
        }
        BookHasAuthor::deleteAll(['book_id' => $bookId]);
        foreach ($authorIds as $authorId) {
            $bookAuthor = new BookHasAuthor();
            $bookAuthor->book_id = $bookId;
            $bookAuthor->author_id = $authorId;
            $bookAuthor->save();
        }
    }
    /**
     * Summary of actionTopAuthors
     * @return string
     */
    public function actionTopAuthors()
    {
        $model = new YearForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $year = $model->year;
            $topAuthors = (new \yii\db\Query())
                ->select(['author.name', 'COUNT(book.id) AS book_count'])
                ->from('book')
                ->innerJoin('book_has_author', 'book.id = book_has_author.book_id')
                ->innerJoin('author', 'book_has_author.author_id = author.id')
                ->where(['book.year' => $year])
                ->groupBy('author.id')
                ->orderBy(['book_count' => SORT_DESC])
                ->limit(10)
                ->all();

            return $this->render('top_authors', [
                'model' => $model,
                'topAuthors' => $topAuthors,
                'year' => $year,
            ]);
        }

        return $this->render('year_form', [
            'model' => $model,
        ]);
    }
    /**
     * Summary of actionSubscribe
     * @return array
     */
    public function actionSubscribe()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Subscription();
        $model->user_id = \Yii::$app->user->id ?? null;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            $hasSubscription = Subscription::find()
                ->where([
                    'author_id' => $model->author_id,
                    'user_id' => \Yii::$app->user->id,
                ])
                ->exists();

            if (!$hasSubscription) {
                $model->save();
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'Вы уже подписаны на автора.'];
            }
        }
        return ['success' => false, 'errors' => $model->errors];
    }
}
