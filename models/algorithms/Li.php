<?php

namespace app\models\algorithms;

use JetBrains\PhpStorm\NoReturn;

class Li extends Algorithm
{
//    #[NoReturn]
    public function run()
    {
        $field = [];
        $st = microtime(true);
        $this->findPath($field);
        $path = $this->restorePath($field);
        $et = microtime(true);

        $time = round($et - $st, 6);
        $time = explode('.', (string)$time);
//        echo json_encode([
//            'path' => $path,
//            'time' => [
//                's' => $time[0],
//                'ms' => substr($time[1], 0, 3),
//                'mcs' => str_pad(substr($time[1], 3, 3), 3, 0),
//            ]
//        ]);
//        exit;
        return [
            'path' => $path,
            'time' => [
                's' => $time[0],
                'ms' => substr($time[1], 0, 3),
                'mcs' => str_pad(substr($time[1], 3, 3), 3, 0),
            ]
        ];
    }

    private function findPath(array &$field)
    {
        $waveWeight = 0;
        $field[(int)$this->start['y']][(int)$this->start['x']] = $waveWeight;
        $wave[] = $this->start;
        $noFinalPoint = true;

        do {
            $nextWave = [];
            $waveWeight++;

            foreach ($wave as $cell) {
                if ((int)$cell['x'] === (int)$this->end['x'] && (int)$cell['y'] === (int)$this->end['y']) {
                    $noFinalPoint = false;
                    $field[(int)$this->end['y']][(int)$this->end['x']] = $waveWeight - 1;
                    break;
                }

                foreach (self::NEIGHBOUR_OFFSET as $offset) {
                    $bottomBorder = 0;
                    $topBorder = 49;
                    $x = $cell['x'] + $offset[0];
                    $y = $cell['y'] + $offset[1];

                    if ($x < $bottomBorder || $x > $topBorder || $y < $bottomBorder || $y > $topBorder) {
                        continue;
                    }

                    if (!isset($field[$y][$x]) && !isset($this->obstacles[$x][$y])) {
                        $field[$y][$x] = $waveWeight;
                        $nextWave[] = [
                            'x' => $x,
                            'y' => $y,
                        ];
                    }
                }
            }

            $wave = $nextWave;
        } while ($noFinalPoint);
    }
    
    /**
     * @param array $field
     * @return array
     */
    private function restorePath(array $field): array
    {
        $step = $this->end;
        $waveWeight = $field[(int)$this->end['y']][(int)$this->end['x']];
        $path[] = $this->end;

        do {
            $nextStep = [];
            $waveWeight--;

            if ((int)$step['x'] === (int)$this->start['x'] && (int)$step['y'] === (int)$this->start['y']) {
                break;
            }

            foreach (self::NEIGHBOUR_OFFSET as $offset) {
                $x = $step['x'] + $offset[0];
                $y = $step['y'] + $offset[1];

                try {
                    $cellWeight = $field[$y][$x];
                } catch (\Exception $e) {
                    continue;
                }

                if ($cellWeight !== $waveWeight) {
                    continue;
                }

                $nextStep = ['x' => $x, 'y' => $y,];
                break;
            }

            $step = $nextStep;
            $path[] = $nextStep;
        } while (true);
        
        return array_reverse($path);
    }
}
