<?php

namespace app\controllers;

use app\models\algorithms\AStar;
use app\models\algorithms\Li;
use Yii;
use yii\web\Controller;

class AlgorithmController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionLi()
    {
        if (Yii::$app->request->isPost) {
            $post = json_decode(Yii::$app->request->getRawBody(), true);
            $algorithm = new Li($post);
            $algorithm->run();
        }
        
        return $this->render('index');
    }
    
    public function actionAStar()
    {
        if (Yii::$app->request->isPost) {
            $post = json_decode(Yii::$app->request->getRawBody(), true);
            $algorithm = new AStar($post);
            $algorithm->run();
        }
        
        return $this->render('index');
    }

    public function actionCompare()
    {
        if (Yii::$app->request->isPost) {
            $result = [];
            $post = json_decode(Yii::$app->request->getRawBody(), true);
            $algorithm = new AStar($post);
            $result['astar'] = $algorithm->run();
            $algorithm = new Li($post);
            $result['li'] = $algorithm->run();
            echo json_encode($result);
            exit;
        }

        return $this->render('index');
    }
}
