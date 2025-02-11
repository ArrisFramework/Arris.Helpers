<?php

/**
 * User: Karel Wintersky
 * Library: https://github.com/KarelWintersky/Arris.Helpers
 */

namespace Arris\Helpers;

/**
 * Class DB
 */
class DB
{
    /**
     * Строит INSERT-запрос на основе массива данных для указанной таблицы.
     * В массиве допустима конструкция 'key' => 'NOW()'
     * В этом случае она будет добавлена в запрос и удалена из набора данных (он пере).
     *
     * @param $table    -- таблица
     * @param $dataset      -- передается по ссылке, мутабелен
     * @return string       -- результирующая строка запроса
     */
    public static function makeInsertQuery(string $table, &$dataset):string
    {
        if (empty($dataset)) {
            return "INSERT INTO {$table} () VALUES (); ";
        }

        $set = [];

        $query = "INSERT INTO {$table} SET ";

        foreach ($dataset as $index => $value) {
            if (\strtoupper(trim($value)) === 'NOW()') {
                $set[] = "\r\n `{$index}` = NOW()";
                unset($dataset[ $index ]);
                continue;
            }

            $set[] = "\r\n `{$index}` = :{$index}";
        }

        $query .= \implode(', ', $set) . ' ;';

        return $query;
    }

    /**
     * Build UPDATE query by dataset for given table
     *
     * @param $tablename
     * @param $dataset
     * @param $where_condition
     * @return bool|string
     */
    public static function makeUpdateQuery(string $table, &$dataset, $where_condition):string
    {
        $crlf = ''; // '\r\n';
        $set = [];

        if (empty($dataset)) {
            return false;
        }

        $query = "UPDATE `{$table}` SET";

        foreach ($dataset as $index => $value) {
            if (\strtoupper(\trim($value)) === 'NOW()') {
                $set[] = "{$crlf} `{$index}` = NOW()";
                unset($dataset[ $index ]);
                continue;
            }

            $set[] = "{$crlf}`{$index}` = :{$index}";
        }

        $query .= \implode(', ', $set);

        if (\is_array($where_condition)) {
            $where_condition = key($where_condition) . ' = ' . current($where_condition);
        }
        if ( \is_string($where_condition ) && (false == strpos($where_condition, 'WHERE')) ) {
            $where_condition = " WHERE {$where_condition}";
        }
        if (\is_null($where_condition)) {
            $where_condition = '';
        }

        $query .= " {$crlf} $where_condition ;";

        return $query;
    }

    public static function makeReplaceQuery(string $table, array &$dataset, string $where = '')
    {
        $fields = [];

        if (empty($dataset))
            return false;

        $query = "REPLACE `{$table}` SET ";

        foreach ($dataset as $index => $value) {
            if (\strtoupper(trim($value)) === 'NOW()') {
                $fields[] = "`{$index}` = NOW()";
                unset($dataset[ $index ]);
                continue;
            }

            $fields[] = " `{$index}` = :{$index} ";
        }

        $query .= \implode(', ', $fields);

        $query .= " \r\n" . $where . " ;";

        return $query;
    }

    /**
     * Применять как:
     *
     * list($update_query, $newdataset) = BuildReplaceQueryMVA($table, $original_dataset, $mva_attributes_list);
     * $update_statement = $sphinx->prepare($update_query);
     * $update_statement->execute($newdataset);
     *
     *
     * @param string $table             -- имя таблицы
     * @param array $dataset            -- сет данных.
     * @param array $mva_attributes     -- массив с именами ключей MVA-атрибутов (они вставятся как значения, а не как placeholder-ы)
     * @return array                    -- возвращает массив с двумя значениями. Первый ключ - запрос, сет данных, очищенный от MVA-атрибутов.
     */
    public static function buildReplaceQueryMVA(string $table, array $dataset, array $mva_attributes):array
    {
        $query = "REPLACE INTO `{$table}` (";

        $dataset_keys = array_keys($dataset);

        $query .= \implode(', ', array_map(function ($i){
            return "`{$i}`";
        }, $dataset_keys));

        $query .= " ) VALUES ( ";

        $query .= \implode(', ', array_map(function ($i) use ($mva_attributes, $dataset){
            return \in_array($i, $mva_attributes) ? "({$dataset[$i]})" : ":{$i}";
        }, $dataset_keys));

        $query .= " ) ";

        $new_dataset = array_filter($dataset, function ($value, $key) use ($mva_attributes) {
            return !\in_array($key, $mva_attributes);
        }, ARRAY_FILTER_USE_BOTH);

        return [
            $query, $new_dataset
        ];
    }

}

# -eof-
