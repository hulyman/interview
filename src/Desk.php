<?php

class Desk
{
    private $figures = [];

    private $is_turn_white = true;

    public function __construct()
    {
        $this->figures['a'][1] = new Rook(false);
        $this->figures['b'][1] = new Knight(false);
        $this->figures['c'][1] = new Bishop(false);
        $this->figures['d'][1] = new Queen(false);
        $this->figures['e'][1] = new King(false);
        $this->figures['f'][1] = new Bishop(false);
        $this->figures['g'][1] = new Knight(false);
        $this->figures['h'][1] = new Rook(false);

        $this->figures['a'][2] = new Pawn(false);
        $this->figures['b'][2] = new Pawn(false);
        $this->figures['c'][2] = new Pawn(false);
        $this->figures['d'][2] = new Pawn(false);
        $this->figures['e'][2] = new Pawn(false);
        $this->figures['f'][2] = new Pawn(false);
        $this->figures['g'][2] = new Pawn(false);
        $this->figures['h'][2] = new Pawn(false);

        $this->figures['a'][7] = new Pawn(true);
        $this->figures['b'][7] = new Pawn(true);
        $this->figures['c'][7] = new Pawn(true);
        $this->figures['d'][7] = new Pawn(true);
        $this->figures['e'][7] = new Pawn(true);
        $this->figures['f'][7] = new Pawn(true);
        $this->figures['g'][7] = new Pawn(true);
        $this->figures['h'][7] = new Pawn(true);

        $this->figures['a'][8] = new Rook(true);
        $this->figures['b'][8] = new Knight(true);
        $this->figures['c'][8] = new Bishop(true);
        $this->figures['d'][8] = new Queen(true);
        $this->figures['e'][8] = new King(true);
        $this->figures['f'][8] = new Bishop(true);
        $this->figures['g'][8] = new Knight(true);
        $this->figures['h'][8] = new Rook(true);
    }

    public function move($move)
    {
        if (!preg_match('/^([a-h])(\d)-([a-h])(\d)$/', $move, $match)) {
            throw new \Exception("Incorrect move");
        }

        $xFrom = $match[1];
        $yFrom = $match[2];
        $xTo = $match[3];
        $yTo = $match[4];

        if (!isset($this->figures[$xFrom][$yFrom])) {
            throw new \Exception("Нельзя двигать несуществующую фигуру.");
        }

        /** @var Figure $figure_to_move */
        $figure_to_move = $this->figures[$xFrom][$yFrom];

        if ($this->is_turn_white != $figure_to_move->getIsWhite()) {
            throw new \Exception('Нельзя двигать фигуру, чей ход не подошел.');
        }

        /** @var Figure|null $figure_to_eat */
        $figure_to_eat = $this->figures[$xTo][$yTo];

        if (!empty($figure_to_eat) && $figure_to_move->getIsWhite() == $figure_to_eat->getIsWhite()) {
            // TODO рокировка?
            throw new UnavailableTurnException($figure_to_move, $move);
        }


        $xMove = abs(ord($xTo) - ord($xFrom));
        $yMove = ($yTo - $yFrom) * ($figure_to_move->getIsWhite() ? 1 : -1);

        if ($xMove == 0 && $yMove == 0) {
            throw new UnavailableTurnException($figure_to_move, $move);
        }

        if ($figure_to_move instanceof Pawn) {

            if (abs($xMove) > 1) {
                throw new UnavailableTurnException($figure_to_move, $move);
            }

            switch ($yMove) {
                case 1:
                    if ($xMove == 0 && !empty($figure_to_eat)) {
                        throw new UnavailableTurnException($figure_to_move, $move);
                    }

                    if ($xMove == 1 && empty($figure_to_eat)) {
                        throw new UnavailableTurnException($figure_to_move, $move);
                    }
                    break;
                case 2:
                    if (!$figure_to_move->getIsFirstTurn()) {
                        throw new UnavailableTurnException($figure_to_move, $move);
                    }

                    if ($xMove != 0) {
                        throw new UnavailableTurnException($figure_to_move, $move);
                    }

                    if (!empty($this->figures[$xFrom][$yFrom - ($figure_to_move->getIsWhite() ? -1 : 1)])) {
                        throw new UnavailableTurnException($figure_to_move, $move);
                    }

                    break;
                default:
                    throw new UnavailableTurnException($figure_to_move, $move);
                    break;
            }


            $figure_to_move->makeTurn();
        }

        $this->figures[$xTo][$yTo] = $this->figures[$xFrom][$yFrom];
        unset($this->figures[$xFrom][$yFrom]);

        $this->is_turn_white = !$this->is_turn_white;
    }

    public function dump()
    {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 'a'; $x <= 'h'; $x++) {
                if (isset($this->figures[$x][$y])) {
                    echo $this->figures[$x][$y];
                } else {
                    echo '-';
                }
            }
            echo "\n";
        }
        echo "  abcdefgh\n";
    }
}
