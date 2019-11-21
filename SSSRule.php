<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/20
 * Time: 13:45
 */

require_once("./Poker.php");

class SSSRule
{
    const POKER_FIVE_STRAIGHT_FLUSH_NO_A = 9;// 同花顺
    const POKER_FIVE_FOUR_ONE = 8;// 四带一张, 铁支
    const POKER_FIVE_THREE_DEOUBLE = 7;// 三条一对，葫芦
    const POKER_FIVE_FLUSH = 6;// 同花五牌，同花
    const POKER_FIVE_MIXED_FLUSH_NO_A = 5;// 顺子，顺子
    const POKER_THREE = 4;// 三张牌型，三条
    const POKER_FIVE_TWO_DOUBLE = 3;// 两对牌型，两对
    const POKER_ONE_DOUBLE = 2;// 只有一对，一对
    const POKER_SINGLE = 1;// 单牌类型 乌龙
    protected $poker_array;

    public function __construct()
    {
        $poker = new Poker();
        $poker->convert();
        $this->poker_array = $poker->getPoker();
    }

    /**
     * @param array $poker
     * @return int
     */
    public function pokerTypeOne(array $poker): int
    {
        return 1;
    }

    /**
     * @param array $poker
     * @return int
     */
    public function pokerTypeTwo(array $poker): int
    {
        return 1;
    }

    /**
     * @param array $poker
     * @return int
     */
    public function numberOfType(array $poker): int
    {
        if (3 == count($poker)) {
            return $this->pokerTypeOne($poker);
        } else {
            return $this->pokerTypeTwo($poker);
        }
    }

    public function singleCompareCard(array $poker1, array $poker2)
    {
    }

    public function test()
    {
        var_dump($this->poker_array);
    }
}

$pokerObject = new SSSRule();
$pokerObject->test();