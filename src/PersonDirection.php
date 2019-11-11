<?php

namespace WalkingDirections;


class PersonDirection
{
    /** @var Instruction[] */
    public $instructions = [];

    /**
     * location when you meet the person
     * @var Point
     */
    private $yourCurrentCoordinates;

    /**
     * @var Point
     */
    private $initPoint;

    /**
     * PersonDirection constructor.
     * @param array $instructions
     * @param Point $initPoint
     */
    public function __construct(array $instructions, Point $initPoint)
    {
        $this->instructions = $instructions;
        $this->yourCurrentCoordinates = $initPoint;
        $this->yourCurrentCoordinates->x = $this->getInitCoordinate(Instruction::NAME_X);
        $this->yourCurrentCoordinates->y = $this->getInitCoordinate(Instruction::NAME_Y);
        $this->initPoint = clone $this->yourCurrentCoordinates;
    }

    /**
     * NAME_START is the initial direction you are facing in degrees (east is 0 degrees, north is 90 degrees)
     * NAME_TURN is an angle in degrees you should turn. A positive α indicates to turn to the left.
     * @return bool
     */
    public function walkToEndPoint(): bool
    {
        $angle = 0;
        foreach ($this->instructions as $instruction) {
            if (Instruction::NAME_START == $instruction->name) {
                $angle = $instruction->value;
            }
            if (Instruction::NAME_TURN == $instruction->name) {
                $angle = $instruction->value;
            }
            if (Instruction::NAME_WALK == $instruction->name) {
                $distanceX = $instruction->value;
                if (!empty($angle) || !empty($distanceX)) {
                    // remind a new current coordinate
                    $this->yourCurrentCoordinates->getNextPoint($angle, $distanceX);
                }
            }
        }
        return $this->yourCurrentCoordinates !== $this->initPoint ;
    }

    /**
     * @param string $instructionName
     * @return float
     */
    private function getInitCoordinate(string $instructionName): float
    {
        $value = 0;
        foreach ($this->instructions as $instruction) {
            if ($instructionName == $instruction->name) {
                $value = $instruction->value;
                break;
            }
        }
        return $value;
    }

    /**
     * Person’s directions from where you are standing.
     * @return Point
     * @var Point
     */
    public function getCurrentCoordinates(): Point
    {
        return $this->yourCurrentCoordinates;
    }

}