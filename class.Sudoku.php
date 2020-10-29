<?php
class Sudoku
{
    private $loaded_array = array();
    private $grids = array();
    private $columns_begining = array();
    private $time_tracking = array();

    public function __construct()
    {
        $this->time_tracking['start'] = microtime(true);
    }

    private function set_grids()
    {
        //creating grid
        $grids = array();
        foreach ($this->loaded_array as $k => $row) {
            if ($k <= 2) {
                $row_num = 1;
            }
            if ($k > 2 && $k <= 5) {
                $row_num = 2;
            }
            if ($k > 5 && $k <= 8) {
                $row_num = 3;
            }

            foreach ($row as $kk => $r) {
                if ($kk <= 2) {
                    $col_num = 1;
                }
                if ($kk > 2 && $kk <= 5) {
                    $col_num = 2;
                }
                if ($kk > 5 && $kk <= 8) {
                    $col_num = 3;
                }
                $grids[$row_num][$col_num][] = $r;
            }
        }
        $this->grids = $grids;
    }

    private function set_columns()
    {
        //order columns
        $columns_begining = array();
        $i = 1;
        foreach ($this->loaded_array as $k => $rows) {
            $e = 1;
            foreach ($rows as $kk => $row) {
                $columns_begining[$e][$i] = $row;
                $e++;
            }
            $i++;
        }
        $this->columns_begining = $columns_begining;
    }

    private function get_possible_numbers($k, $kk)
    { //GET POSSIBILITIES FOR GIVEN CELL
        $values = array();
        if ($k <= 2) {
            $row_num = 1;
        }
        if ($k > 2 && $k <= 5) {
            $row_num = 2;
        }
        if ($k > 5 && $k <= 8) {
            $row_num = 3;
        }

        if ($kk <= 2) {
            $col_num = 1;
        }
        if ($kk > 2 && $kk <= 5) {
            $col_num = 2;
        }
        if ($kk > 5 && $kk <= 8) {
            $col_num = 3;
        }

        for ($n = 1; $n <= 9; $n++) {
            if (
                !in_array($n, $this->loaded_array[$k]) && !in_array($n, $this->columns_begining[$kk + 1])
                && !in_array($n, $this->grids[$row_num][$col_num])
            ) {
                $values[] = $n;
            }
        }
        shuffle($values);
        return $values;
    }

    public function solver($arr)
    {
        while (true) {
            $this->loaded_array = $arr;

            $this->set_columns();
            $this->set_grids();

            $ops = array();
            foreach ($arr as $k => $rows) {
                foreach ($rows as $kk => $row) {
                    if ($row == 0) {
                        $possible_vals = $this->get_possible_numbers($k, $kk);
                        $ops[] = array(
                            'row_index' => $k,
                            'column_index' => $kk,
                            'allowed' => $possible_vals
                        );
                    }
                }
            }

            if (empty($ops)) {
                return $arr;
            }

            usort($ops, array($this, 'sort_ops'));

            if (count($ops[0]['allowed']) == 1) {
                $arr[$ops[0]['row_index']][$ops[0]['column_index']] = current($ops[0]['allowed']);
                continue;
            }

            foreach ($ops[0]['allowed'] as $value) {
                $tmp = $arr;
                $tmp[$ops[0]['row_index']][$ops[0]['column_index']] = $value;
                if ($result = $this->solver($tmp)) {
                    return $this->solver($tmp);
                }
            }

            return false;
        }
    }

    private function sort_ops($a, $b)
    {
        $a = count($a['allowed']);
        $b = count($b['allowed']);
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    public function get_result()
    {
        $result = '';
        foreach ($this->loaded_array as $k => $row) {
            foreach ($row as $kk => $r) {
                if ($kk % 9 === 0) {
                    $result .= "\n";
                }
                $result .= $r;
            }
        }
        return $result;
    }
}
