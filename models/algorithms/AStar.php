<?php

namespace app\models\algorithms;

use JetBrains\PhpStorm\NoReturn;

class AStar extends Algorithm
{
//    #[NoReturn]
    public function run()
    {
        $st = microtime(true);
        $closed = [];
        $open[] = $this->start;
        $from = [];
        $weight = 0;
        $g[$this->start['x']][$this->start['y']] = $weight;
        $f[$this->start['x']][$this->start['y']] = $weight + $this->heuristic($this->start, $this->end);

        while(count($open) > 0) {
            $curr = $this->minFPoint($open, $f);

            if ((int)$curr['x'] === (int)$this->end['x'] && (int)$curr['y'] === (int)$this->end['y']) {
                break;
            }

            $this->removePoint($curr, $open);
            $this->addPoint($curr, $closed);

            $neighbours = $this->unclosedNeighbour($curr, $closed);

            foreach ($neighbours as $neighbour) {
                $tempG = $g[$curr['x']][$curr['y']] + $neighbour['weight'];

                if (!$this->isOpen($neighbour, $open) || $tempG < $g[$neighbour['x']][$neighbour['y']]) {
                    $from[][$neighbour['x']][$neighbour['y']] = $curr;
                    $g[$neighbour['x']][$neighbour['y']] = $tempG;
                    $f[$neighbour['x']][$neighbour['y']] = $tempG + $this->heuristic($neighbour, $this->end);

                    if (!$this->isOpen($neighbour, $open)) {
                        $open[] = $neighbour;
                    }
                }
            }
        }

        $sorted = [];
        foreach ($from as $point) {
            foreach ($point as $x => $coll) {
                foreach ($coll as $y => $val) {
                    $sorted[$x][$y][] = $val;
                }
            }
        }

        $to = $this->end;
        $path[] = $this->end;

        while (true) {
            $minWeight = null;
            $from = $sorted[(int)$to['x']][(int)$to['y']];
            $node = [];

            foreach ($from as $point) {
                $weight = $f[(int)$point['x']][(int)$point['y']];

                if (!isset($minWeight) || $weight < $minWeight) {
                    $minWeight = $weight;
                    $node = $point;
                }
            }

            $to = $node;
            $path[] = $node;

            if ((int)$node['x'] === (int)$this->start['x'] && (int)$node['y'] === (int)$this->start['y']) {
                break;
            }
        }

        $et = microtime(true);
        $time = round($et - $st, 6);
        $time = explode('.', (string)$time);
        
//        echo json_encode([
//            'path' => array_reverse($path),
//            'time' => [
//                's' => $time[0],
//                'ms' => substr($time[1], 0, 3),
//                'mcs' => str_pad(substr($time[1], 3, 3), 3, 0),
//            ]
//        ]);
//        exit;
        return [
            'path' => array_reverse($path),
            'time' => [
                's' => $time[0],
                'ms' => substr($time[1], 0, 3),
                'mcs' => str_pad(substr($time[1], 3, 3), 3, 0),
            ]
        ];
    }
    
    /**
     * @param $point
     * @param $end
     * @return float
     */
    private function heuristic($point, $end): float
    {
        $x = (int)$end['x'] - (int)$point['x'];
        $y = (int)$end['y'] - (int)$point['y'];

        return abs(sqrt(pow($x, 2) + pow($y, 2)));
    }

    /**
     * @param $open
     * @param $f
     * @return array
     */
    private function minFPoint($open, $f): array
    {
        $result = [];
        $minF = null;

        foreach ($open as $point) {
            $pointWeight = $f[$point['x']][$point['y']];

            if (!isset($minF) || $pointWeight < $minF) {
                $minF = $pointWeight;
                $result = $point;
            }
        }

        return $result;
    }

    /**
     * @param $point
     * @param $open
     */
    private function removePoint($point, &$open)
    {
        foreach ($open as $key => $node) {
            if ((int)$node['x'] === (int)$point['x'] && (int)$node['y'] === (int)$point['y']) {
                unset($open[$key]);
                break;
            }
        }
    }

    /**
     * @param $point
     * @param $closed
     */
    private function addPoint($point, &$closed)
    {
        $closed[] = $point;
    }

    /**
     * @param $point
     * @param $closed
     * @return array
     */
    private function unclosedNeighbour($point, $closed): array
    {
        $result = [];

        foreach (self::NEIGHBOUR_OFFSET as $offset) {
            $x = $point['x'] + $offset[0];
            $y = $point['y'] + $offset[1];
            $inClosed = false;

            if ($x < 0 || $x > 49 || $y < 0 || $y > 49 || isset($this->obstacles[$x][$y])) {
                continue;
            }

            foreach ($closed as $node) {
                if ((int)$node['x'] === (int)$x && (int)$node['y'] === (int)$y) {
                    $inClosed = true;
                    break;
                }
            }

            if (!$inClosed) {
                $result[] = ['x' => (int)$x, 'y' => (int)$y, 'weight' => $offset[2]];
            }
        }

        return $result;
    }

    /**
     * @param $point
     * @param $open
     * @return bool
     */
    private function isOpen($point, $open): bool
    {
        foreach ($open as $node) {
            if ((int)$node['x'] === (int)$point['x'] && (int)$node['y'] === (int)$point['y']) {
                return true;
            }
        }

        return false;
    }
}
