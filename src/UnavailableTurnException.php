<?php
/**
 * Created by PhpStorm.
 * User: dudarev
 * Date: 23/11/2017
 * Time: 11:46
 */

class UnavailableTurnException extends \Exception
{
    /**
     * UnavailableTurnException constructor.
     * @param Figure $figure
     * @param string $move
     */
    public function __construct($figure, $move)
    {
        $class = get_class($figure);
        parent::__construct("Недопустимый ход: {$class} {$move}.");
    }

}