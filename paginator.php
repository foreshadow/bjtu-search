<?php

class Paginator {

    public $page;
    public $maxpage;
    public $pagesize;

    function __construct($page, $maxpage, $pagesize) {
        $this->page = $page > 0 ? $page : 1;
        $this->maxpage = ceil($maxpage / $pagesize);
        $this->pagesize = $pagesize;
    }

    function begin() {
        return $this->pagesize * ($this->page - 1);
    }

    function nav($link = "?p=") {
        $a = array_values(array_unique([
            1, 
            2, 
            $this->page - 2, 
            $this->page - 1, 
            $this->page, 
            $this->page + 1, 
            $this->page + 2, 
            $this->maxpage - 1, 
            $this->maxpage
        ])); 
        sort($a); 
        $n = count($a);
        echo '<nav><ul class="pagination"><li';
        if ($this->page - 1 < 1) { 
            echo ' class="disabled"><a>'; 
        } else { 
            echo '><a href="', $link, $this->page - 1, '">'; 
        } 
        echo '上一页</a></li>';
        for ($i = 0; $i < $n; $i++) {
            $p = $a[$i]; 
            if (1 <= $p && $p <= $this->maxpage) { 
                if ($i > 0 && $a[$i] != $a[$i - 1] + 1) {
                    echo '<li><span>…</span></li>';
                }
                if ($p == $this->page) {
                    echo '<li class="active"><a href="#">', $p, '<span class="sr-only">(current)</span></a></li>';
                } else {
                    echo '<li><a href="', $link, $p, '">' , $p, '</a></li>';
                }
            }
        }
        echo '<li';
        if ($this->page + 1 > $this->maxpage) { 
            echo ' class="disabled"><a>'; 
        } else { 
            echo '><a href="', $link, $this->page + 1, '">'; 
        } 
        echo '下一页</a></li></ul></nav>';
    }
}
