<?php

namespace WalkingDirections;

/**
 * Class Point
 * @property string $x
 * @property string $y
 * @package WalkingDirections
 */
class Point
{
    const FIRST_QUOTER = 1;
    const SECOND_QUOTER = 2;
    const THIRD_QUOTER = 3;
    const FOURTH_QUOTER = 4;
    const BORDER_FIRST_SECOND = 12; // +y, x=0
    const BORDER_THIRD_FOURTH = 34; // -y, x=0
    const BORDER_SECOND_THIRD = 23; // -x, y=0
    const BORDER_FOURTH_FIRST = 41; // +x, y=0
    const REFERENCE_POINT = 0;

    private $x;
    private $y;

    /**
     * @param string $name
     * @return float
     */
    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * @param string $name
     * @param float $value
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }


    /**
     * @return float
     */
    private function getX()
    {
        return $this->x;
    }

    /**
     * @return float
     */
    private function getY()
    {
        return $this->y;
    }

    /**
     * function will be used later
     * @return int
     */
    protected function getNumberOfQuarter(): int
    {
        $x = $this->getX();
        $y = $this->getY();
        if ($x > 0) {
            if  ($y > 0) {
                return self::FIRST_QUOTER;
            }
            if  ($y < 0) {
                return self::FOURTH_QUOTER;
            }
            if  ($y = 0) {
                return self::BORDER_FOURTH_FIRST;
            }
        }
        if ($x < 0) {
            if  ($y > 0) {
                return self::SECOND_QUOTER;
            }
            if  ($y < 0) {
                return self::THIRD_QUOTER;
            }
            if  ($y = 0) {
                return self::BORDER_SECOND_THIRD;
            }
        }
        if ($x = 0) {
            if  ($y > 0) {
                return self::BORDER_FIRST_SECOND;
            }
            if  ($y < 0) {
                return self::BORDER_THIRD_FOURTH;
            }
            if  ($y = 0) {
                return self::REFERENCE_POINT;
            }
        }
        return 0;
    }

    /**
     * @param float $angle
     * @param float $distanceX
     * @return Point
     */
    public function getNextPoint(float $angle,  float $distanceX): self
    {
        $angle = abs($angle);
        $x = abs($distanceX); // $angle == 0 +x
        $y = 0;

        if (($angle > 0) && ($angle < 90)) {
            // will + x and + y
            $y = $x * tan($angle);
        }
        if ($angle == 90) {
            // will + y
            $y = abs($distanceX);
        }
        if (($angle > 90) && ($angle < 180)) {
            // will - x and + y
            $y = $x * tan($angle);
        }
        if ($angle == 180) {
            // will -x
            $x = - abs($distanceX);
        }
        if (($angle > 180) && ($angle < 270)) {
            // will - x and - y
            $y = - $x * tan($angle);
        }
        if ($angle == 270) {
            // will -y
            $y = -$x;
        }
        if (($angle > 270) && ($angle < 360)) {
            // will +x and -y
            $y = - $x * tan($angle);
        }
        $this->x = round($x, 4);
        $this->y = round($y, 4);
        return $this;
    }

    /**
     * @param self[] $points
     * @return Point
     */
    public static function getAveragePoint(array $points): self
    {
        $xSum = array_reduce($points, function($sumX, $point) {
            $sumX += $point->x;
            return $sumX;
        });
        $ySum = array_reduce($points, function($sumY, $point) {
            $sumY += $point->y;
            return $sumY;
        });
        $point = new self();
        $point->x = round($xSum / count($points), 4);
        $point->y = round($ySum / count($points), 4);
        return $point;
    }

    public static function getWorstPoint(array $points, Point $avrPoint): self
    {
        $deltaX = [];
        $deltaY = [];
        foreach ($points as $key => $point) {
            $deltaX[$key] = $point->x - $avrPoint->x;
            $deltaY[$key] = $point->y - $avrPoint->y;
        }
        $point = new self();
        $point->x = round(max($deltaX), 4);
        $point->y = round(max($deltaY), 4);
        return $point;
    }

}