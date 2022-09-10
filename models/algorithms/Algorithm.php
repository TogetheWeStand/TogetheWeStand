<?php

namespace app\models\algorithms;

abstract class Algorithm
{
    // Смещения для соседних клеток
    const NEIGHBOUR_OFFSET = [
        // x, y, weight
        // диагональный переход стоит 1.4 прямой 1
        [0, 1, 1],
        [0, -1, 1],
        [1, 0, 1],
        [-1, 0, 1],
        [1, 1, 1.4],
        [-1, -1, 1.4],
        [1, -1, 1.4],
        [-1, 1, 1.4],
    ];

    protected array $start;
    protected array $end;
    protected array $obstacles;
    
    public function __construct($post)
    {
        $this->start = $post['start'];
        $this->end = $post['end'];
        $this->obstacles = $post['obstacles'];
    }
    
    abstract public function run();
}
