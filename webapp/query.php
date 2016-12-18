<?php

class Query {

    static $database;

    static function decay($a) {
        return $a->id;
    }

    static function compare($a, $b) {
        return $a->weight == $b->weight
                ? ($a->id == $b->id ? 0 : ($a->id < $b->id ? -1 : 1))
                : ($a->weight > $b->weight ? -1 : 1);
    }

    static function singleQuery($query, $table) {
        $statement = Query::$database->prepare('select id from ' . $table . ' where term = ?');
        $statement->bind_param('s', $query);
        $statement->execute();
        $statement->bind_result($id);
        $array = [];
        while ($statement->fetch()) {
            $array[] = $id;
        }
        return $array;
    }

    static function getSinglePage($pageid) {
        $statement = Query::$database->prepare('select url, title, content from pages where id = ?');
        $statement->bind_param('i', $pageid);
        $statement->execute();
        $statement->bind_result($url, $title, $content);
        $statement->fetch();
        return ['url' => $url, 'title' => $title, 'content' => $content];
    }

    static function exec($query, $offset, $length) { 
        $seg_list = json_decode(file_get_contents("http://localhost:9228/?content=" . urlencode($query)));
        $result = $result2 = $merge = [];
        foreach ($seg_list as $word) {
            $result = array_merge($result, Query::singleQuery($word, 'query_id'));
        }
        foreach ($seg_list as $word) {
            $result2 = array_merge($result2, Query::singleQuery($word, 'query_id2'));
        }
        $time = 0;
        foreach ($result as $id) {
            if (array_key_exists($id, $merge)) {
                $merge[$id]->weight += 1;
            } else {
                $merge[$id] = new stdClass;
                $merge[$id]->id = $id;
                $merge[$id]->weight = 1;
            }
        }
        foreach ($result2 as $id) {
            if (array_key_exists($id, $merge)) {
                $merge[$id]->weight += 3;
            } else {
                $merge[$id] = new stdClass;
                $merge[$id]->id = $id;
                $merge[$id]->weight = 3;
            }
        }
        usort($merge, 'Query::compare');
        $result = array_slice($merge, $offset, $length);
        $result = array_map('Query::decay', $result);
        $result = array_map('Query::getSinglePage', $result);
        return ['segments' => $seg_list, 'result' => $result, 'size' => count($merge)];
    }
}

Query::$database = new Database;
