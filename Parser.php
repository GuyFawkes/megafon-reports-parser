<?php


class Parser
{
    private $dom;

    public function __construct()
    {
        $this->dom = new DOMDocument();
    }


    public function getData($fileName)
    {
        $this->dom->loadHTMLFile($fileName);
        $path = new DOMXPath($this->dom);
        $data = [];
        $list = $path->query('//td[@colspan=3 and (@class="s16" or @class="s19")]');
        foreach ($list as $node) {
            $columns = $path->query('td', $node->parentNode);
            $temp    = [];
            foreach ($columns as $column) {
                $temp[] = $column->nodeValue;
            }
            $data[$temp[5]][] = [
                'date' => $temp[0],
                'time' => sprintf('%s %s', $temp[0], $temp[1]),
                'size' => (int) $temp[3],
                'unit' => $temp[4],
                'cost' => (float)$temp[7]
            ];
        }
        return $data;
    }
}