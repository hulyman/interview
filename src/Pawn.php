<?php

class Pawn extends Figure
{
    private $isFirstTurn = true;

    public function __toString()
    {
        return $this->isBlack ? '♟' : '♙';
    }

    public function getIsFirstTurn()
    {
        return $this->isFirstTurn;
    }

    public function makeTurn()
    {
        $this->isFirstTurn = false;
    }
}
