<?php


class Utils
{
    private function pluralForm($n, $forms)
    {
        return $n % 10 == 1 && $n % 100 != 11 ?
            $forms[0] :
            ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
    }

    public function convertUnit($n, $unit)
    {
        $types = [
            'шт.' => 'штука',
            'мб.' => 'мегабайт'
        ];
        $forms = [
            'штука'    => ['штуки', 'штук'],
            'мегабайт' => ['мегабайта', 'мегабайт'],
            'минута'   => ['минуты', 'минут'],
            'рубль'    => ['рубля', 'рублей']
        ];
        if (isset($types[$unit])) {
            $unit = $types[$unit];
        }
        if (isset($forms[$unit])) {
            $forms = $forms[$unit];
            array_unshift($forms, $unit);
            return $this->pluralForm($n, $forms);
        }
        return $unit;
    }

    public function convertData($unit, $size)
    {
        $c = ['Килобайт' => ['title' => 'Мегабайт', 'div' => 1024], 'Секунда' => ['title' => 'Минута', 'div' => 60]];
        if (is_string($size) && strpos($size, ':') !== false) {
            $timeParts = explode(':', $size, 2);
            $size = (int) $timeParts[0] + (int) $timeParts[1] / 60;
            $unit = 'Минута';
        }
        if (isset($c[$unit])) {
            if (!empty($c[$unit]['div'])) {
                $size /= $c[$unit]['div'];
            } elseif (isset($c[$unit]['mul'])) {
                $size *= $c[$unit]['mul'];
            }
            $unit = $c[$unit]['title'];
        }
        $unit = mb_strtolower($unit);
        return [$unit, $size];
    }
}