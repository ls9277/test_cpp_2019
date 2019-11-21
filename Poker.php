<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/20
 * Time: 13:47
 */

class Poker
{
    protected $poker_map;
    protected $POKER_NUM = 52;

    public function __construct()
    {
        for ($i = 0; $i < $this->POKER_NUM; ++$i) {
            $this->poker_map[$i] = $i + 1;
        }
        shuffle($this->poker_map);
    }

    public function convert()
    {
        $newPoker = array_map(function ($args) {
            $num = ($tmpNum = $args % 13) == 0 ? 13 : (int)$tmpNum;
            return ['num' => $num, 'cardNum' => $args, 'type' => (int)ceil($args / 13)];
        }, $this->poker_map);
        $this->poker_map = $newPoker;
        return $newPoker;
    }

    /**
     * @return array 返回扑克数组
     */
    public function getPoker()
    {
        $result_map = array();
        for ($i = 0; $i < 4; ++$i) {
            $result_map[$i]['head'] = array_splice($this->poker_map, 0, 3);
            $result_map[$i]['mid'] = array_splice($this->poker_map, 0, 5);
            $result_map[$i]['end'] = array_splice($this->poker_map, 0, 5);
        }
        return $result_map;
    }

}


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

    const POKER_THREE_SAMETYPE = 10;// 三同花
    const POKER_THREE_STRAIGHT = 11;// 三顺子
    const POKER_SIX_AND_ONE = 12;// 六对半
    const POKER_FIVE_AND_THREE = 13;// 五对三
    const POKER_FOUR_AND_THREE = 14;// 四套三
    const POKER_SAME_COLOR = 15;// 凑一色
    const POKER_ALL_MIN = 16;// 全小
    const POKER_ALL_MAX = 17;// 全大
    const POKER_FOUR_THREE_ONE = 18;// 三分天下
    const POKER_THREE_SAMETYPE_STRAIGHT = 19;// 三同花顺
    const POKER_TEM_KIM = 20;// 十二皇族
    const POKER_ONE_LONG = 21;// 一条龙
    const POKER_MASTER_LONG = 22;// 至尊青龙

    protected $poker_array;
    protected $isSpecialName = false;

    public function __construct()
    {
        $poker = new Poker();
        $poker->convert();
        $this->poker_array = $poker->getPoker();
    }

    /**
     * @license 判断是覅为特殊牌
     * @param array $poker
     * @return int
     */
    public function pokerTypeOne(array $poker): int
    {
        if ($this->isMasterQinglong($poker)) {
            return self::POKER_MASTER_LONG;
        } else if ($this->isLong($poker)) {
            return self::POKER_ONE_LONG;
        } else if ($this->isRoyal($poker)) {
            return self::POKER_TEM_KIM;
        } else if ($this->isThreeSameTypeAndStraight($poker)) {
            return self::POKER_THREE_SAMETYPE_STRAIGHT;
        } else if ($this->isThreePart($poker)) {
            return self::POKER_FOUR_THREE_ONE;
        } else if ($this->isAllMax($poker)) {
            return self::POKER_ALL_MAX;
        } else if ($this->isALLMin($poker)) {
            return self::POKER_ALL_MIN;
        } else if ($this->isSameColor($poker)) {
            return self::POKER_SAME_COLOR;
        } else if ($this->isFourAndThree($poker)) {
            return self::POKER_FOUR_AND_THREE;
        } else if ($this->isFiveAndThree($poker)) {
            return self::POKER_FIVE_AND_THREE;
        } else if ($this->isSixAndOne($poker)) {
            return self::POKER_SIX_AND_ONE;
        } else if ($this->isThreeStraight($poker)) {
            return self::POKER_THREE_STRAIGHT;
        } else if ($this->isThreeSameType($poker)) {
            return self::POKER_THREE_SAMETYPE;
        } else {
            return -1;
        }
    }

    /**
     * @license 获取数据
     * @param $poker
     * @param string $item
     * @return array
     */
    public function getMapValue($poker, $item = 'type'): array
    {
        $newList = array();
        foreach ($poker as $key => $value) {
            foreach ($value as $v) {
                array_push($newList, (int)$v[$item]);
            }
        }
        return $newList;
    }

    /**
     * @param array $poker
     * @return int
     */
    public function pokerTypeTwo(array $poker): int
    {
        if ($this->isFiveStraight($poker)) {
            return self::POKER_FIVE_STRAIGHT_FLUSH_NO_A;
        } else if ($this->isFourOne($poker)) {
            return self::POKER_FIVE_FOUR_ONE;
        } else if ($this->isGourd($poker)) {
            return self::POKER_FIVE_THREE_DEOUBLE;
        } else if ($this->isSameType($poker)) {
            return self::POKER_FIVE_FLUSH;
        } else if ($this->isStraight($poker)) {
            return self::POKER_FIVE_MIXED_FLUSH_NO_A;
        } else if ($this->isThree($poker)) {
            return self::POKER_THREE;
        } else if ($this->isTwoPairs($poker)) {
            return self::POKER_FIVE_TWO_DOUBLE;
        } else if ($this->isOnePairs($poker)) {
            return self::POKER_ONE_DOUBLE;
        } else if ($this->isSingleCard($poker)) {
            return self::POKER_SINGLE;
        } else {
            return -1;
        }
    }

    /**
     * @license : 判断是否是至尊青龙
     * @param $poker
     * @return bool
     */
    public function isMasterQinglong($poker): bool
    {
        $typeMap = $this->getMapValue($poker, 'type');
        $numMap = $this->getMapValue($poker, 'num');
        $typeCount = array_values(array_count_values($typeMap));
        sort($numMap);
        $res = array_diff($numMap, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);
        return (1 == count($typeCount) && empty($res));
    }

    /**
     * @license : 判断是否为一条龙，如果是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isLong($poker): bool
    {
        $numMap = $this->getMapValue($poker, 'num');
        sort($numMap);
        $res = array_diff([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], $numMap);
        return empty($res);
    }

    /**
     * @license  判断是否是十二皇族，如果是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isRoyal($poker): bool
    {
        $numMap = array_map(function ($card) {
            return array_map(function ($singleCard) {
                return ($singleCard['num'] >= 2 && $singleCard['num'] <= 10) ? 0 : 1;
            }, $card);
        }, $poker);
        return (13 == array_sum($numMap)) ? true : false;
    }

    /**
     * @license 是否是三同花，如果是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isThreeSameType($poker): bool
    {
        $sameTypeCount = array_map(function ($card) {
            return $this->isSameType($card, 2) ? 1 : 0;
        }, $poker);
        return (3 == array_sum($sameTypeCount)) ? true : false;
    }

    /**
     * @license 是否是三同花顺，如果是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isThreeSameTypeAndStraight($poker): bool
    {
        $sameTypeCount = array_map(function ($card) {
            return ($this->isSameType($card, 2) && $this->isStraight($card, 2)) ? 1 : 0;
        }, $poker);
        return (3 == array_sum($sameTypeCount)) ? true : false;
    }


    /**
     * @license 是否是三分天下，如果是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isThreePart($poker): bool
    {
        $numMap = $this->getMapValue($poker, 'num');
        $numValue = array_values(array_count_values($numMap));
        if (4 == count($numValue)) {
            sort($numValue);
            return (1 == $numValue[0] && 3 == $numValue[1] && 3 == $numValue[2] && 3 == $numValue[3]);
        }
        return false;
    }


    /**
     * @license 是否是三顺子，如果是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isThreeStraight($poker): bool
    {
        $sameTypeCount = array_map(function ($card) {
            return $this->isStraight($card, 2) ? 1 : 0;
        }, $poker);
        return (3 == array_sum($sameTypeCount)) ? true : false;
    }

    /**
     * @license  判断是否为全大，是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isAllMax($poker): bool
    {
        $numMap = array_map(function ($card) {
            return array_map(function ($singleCard) {
                return ($singleCard['num'] >= 2 && $singleCard['num'] <= 8) ? 0 : 1;
            }, $card);
        }, $poker);
        return (13 == array_sum($numMap)) ? true : false;
    }


    /**
     * @license  判断是否为全小，是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isAllMin($poker): bool
    {
        $numMap = array_map(function ($card) {
            return array_map(function ($singleCard) {
                return ($singleCard['num'] >= 2 && $singleCard['num'] <= 8) ? 1 : 0;
            }, $card);
        }, $poker);
        return (13 == array_sum($numMap)) ? true : false;
    }


    /**
     * @license  判断是否为凑一色，是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isSameColor($poker): bool
    {
        $typeMap = array_map(function ($card) {
            return array_map(function ($singleCard) {
                return (1 == $singleCard['type'] || 3 == $singleCard['type']) ? 0 : 1;
            }, $card);
        }, $poker);
        return (13 == array_sum($typeMap) || 0 == array_sum($typeMap)) ? false : true;
    }

    /**
     * @license 判断是否为4套3条，如果是则返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isFourAndThree($poker): bool
    {
        $numMap = $this->getMapValue($poker, 'num');
        $numValue = array_values(array_count_values($numMap));
        if (3 == count($numValue)) {
            sort($numValue);
            return (1 == $numValue[0] && 3 == $numValue[1] && 3 == $numValue[2]);
        }
        return false;
    }

    /**
     * @license 判断是否为五对三条，如果是返回true,否则返回false
     * @param $poker
     * @return bool
     */
    public function isFiveAndThree($poker): bool
    {
        $numMap = $this->getMapValue($poker, 'num');
        $numValue = array_values(array_count_values($numMap));
        if (6 == count($numValue)) {
            sort($numValue);
            return (2 == $numValue[0] && 2 == $numValue[1] && 2 == $numValue[2] && 2 == $numValue[3] && 2 == $numValue[4] && 3 == $numValue[5]);
        }
        return false;
    }

    /**
     * @license 判断是否为六对半，如果是返回true,否返回false
     * @param $poker
     * @return bool
     */
    public function isSixAndOne($poker): bool
    {
        $numMap = $this->getMapValue($poker, 'num');
        $numValue = array_values(array_count_values($numMap));
        if (7 == count($numValue)) {
            $tmpValue = array_values(array_count_values($numMap));
            if (2 == count($tmpValue)) {
                return (1 == $tmpValue[0] && 6 == $tmpValue[1]);
            }
        }
        return false;
    }


    /**
     * @license : 单牌
     * @param array $poker
     * @return bool
     */
    public function isSingleCard(array $poker): bool
    {
        $numMap = array_map(function ($args) {
            return (int)$args['num'];
        }, $poker);
        $numCount = array_values(array_count_values($numMap));
        if (3 == count($poker)) {
            return (3 == count($numCount));
        } else if (5 == count($poker)) {
            return (5 == count($numCount));
        }
        return false;
    }

    /**
     * @license : 判断一副扑克是否是对子，如果是返回true,否则，返回false
     * @param array $poker
     * @return bool
     */
    public function isOnePairs(array $poker): bool
    {
        $numMap = array_map(function ($args) {
            return (int)$args['num'];
        }, $poker);
        $numCount = array_values(array_count_values($numMap));
        if (2 == count($numCount) && 3 == count($poker)) {
            sort($numCount);
            return (1 == $numCount[0] && 2 == $numCount[1]);
        } else if (4 == count($numCount) && 5 == count($poker)) {
            sort($numCount);
            return (1 == $numCount[0] && 1 == $numCount[1] && 1 == $numCount[2] && 2 == $numCount[3]);
        }
        return false;
    }

    /**
     * @license : 判断一副扑克是否是两对，如果是返回true,否则，返回false
     * @param array $poker
     * @return bool
     */
    public function isTwoPairs(array $poker): bool
    {
        $numMap = array_map(function ($args) {
            return (int)$args['num'];
        }, $poker);
        $numCount = array_values(array_count_values($numMap));
        if (3 == count($numCount) && 5 == count($poker)) {
            sort($numCount);
            return (1 == $numCount[0] && 2 == $numCount[1] && 2 == $numCount[2]);
        }
        return false;
    }

    /**
     * @param array $poker
     * @return int
     */
    public function numberOfType(array $poker): int
    {
        if (array_key_exists('head', $poker)
            && array_key_exists('mid', $poker)
            && array_key_exists('end', $poker)) {
            return $this->pokerTypeOne($poker);
        } else {
            return $this->pokerTypeTwo($poker);
        }
    }

    /**
     * @license : 判断是否是同花，如果是同花则返回true,否则返回false,同花就是五张牌的花色一样
     * @param array $poker
     * @return bool
     */
    public function isSameType(array $poker, $type = 1)
    {
        if (3 == count($poker) && 1 == $type) {
            return false;
        }
        $typeMap = array_map(function ($args) {
            return (int)$args['type'];
        }, $poker);
        $typeCount = array_values(array_count_values($typeMap));
        return (1 === count($typeCount)) ? true : false;
    }


    /**
     * @license : 判断是否是三条，如果是返回true,否则返回false,葫芦即是三张点数一样的扑克牌
     * @param array $poker
     * @return bool
     */
    public function isThree(array $poker): bool
    {
        $numMap = array_map(function ($args) {
            return (int)$args['num'];
        }, $poker);
        $numCount = array_values(array_count_values($numMap));
        if (1 == count($numCount) && 5 != count($poker)) {
            return true;
        } else if (3 == count($numCount) && 5 == count($poker)) {
            sort($numCount);
            return (1 == $numCount[0] && 1 == $numCount[1] && 3 == $numCount[2]);
        }
        return false;
    }

    /**
     * @license : 判断是否是葫芦，如果是返回true,否则返回false,葫芦即是三带一对
     * @param array $poker
     * @return bool
     */
    public function isGourd(array $poker): bool
    {
        $numMap = array_map(function ($args) {
            return (int)$args['num'];
        }, $poker);
        $numCount = array_values(array_count_values($numMap));
        if (2 == count($numCount)) {
            sort($numCount);
            return (2 == $numCount[0] && 3 == $numCount[1]) ? true : false;
        }
        return false;
    }

    /**
     * @license : 判断是否是4带1（即铁支）
     * @param array $poker
     * @return bool
     */
    public function isFourOne(array $poker): bool
    {
        $numMap = array_map(function ($args) {
            return (int)$args['num'];
        }, $poker);
        $numCount = array_values(array_count_values($numMap));
        if (2 == count($numCount)) {
            sort($numCount);
            return (1 == $numCount[0] && 4 == $numCount[1]) ? true : false;
        }
        return false;
    }

    /**
     * @license : 判断是否是顺子，如果是顺子返回true,否则返回false,顺子即点数为连续的五张扑克
     * @param array $poker
     * @return bool
     */
    public function isStraight(array $poker, $type = 1): bool
    {
        $numMap = array_map(function ($args) {
            return (int)$args['num'];
        }, $poker);
        sort($numMap);

        if (1 == $numMap[0] && $numMap[count($numMap) - 1] == 13) {
            $numMap[0] = 14;
            sort($numMap);
        }
        if (5 == count($poker)) {
            if ($numMap[0] == $numMap[1] - 1
                && $numMap[0] == $numMap[2] - 2
                && $numMap[0] == $numMap[3] - 3
                && $numMap[0] == $numMap[4] - 4
            ) {
                return true;
            }
        } else if (3 == count($poker) && 2 == $type) {
            if ($numMap[0] == $numMap[1] - 1
                && $numMap[0] == $numMap[2] - 2
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @license 判断是否是同花顺
     * @param array $poker
     * @return bool
     */
    public function isFiveStraight(array $poker): bool
    {
        return ($this->isSameType($poker) && $this->isStraight($poker));
    }

    public function singleCompareCard(array $poker1, array $poker2)
    {
    }

    public function test()
    {
        $poker = [
            ['num' => 1, 'type' => 1], ['num' => 1, 'type' => 2], ['num' => 1, 'type' => 3], ['num' => 2, 'type' => 4], ['num' => 5, 'type' => 1]
        ];
        $res = $this->isThree($poker);
        for ($i = 0; $i < count($this->poker_array); $i++) {
            if ($this->numberOfType($this->poker_array[$i]) == -1) {
                foreach ($this->poker_array[$i] as $key => &$v) {
                    if ('head' == $key) {
                        $v['head_name'] = $this->numberOfType($v);
                    } else if ('mid' == $key) {
                        $v['mid_name'] = $this->numberOfType($v);
                    } else {
                        $v['end_name'] = $this->numberOfType($v);
                    }
                }
            } else {
                $this->poker_array[0]['special_name'] = $this->numberOfType($this->poker_array[$i]);
            }
        }
        var_dump(json_encode($this->poker_array, JSON_UNESCAPED_UNICODE));
    }
}

$pokerObject = new SSSRule();
$pokerObject->test();