<?php
/**
 * Created by PhpStorm.
 * User: ervin
 * Date: 11.03.2017
 * Time: 11:48
 */

namespace Libs\Admin;


class RepositoryHelper {

    public static function prepareDbField($field) {
        $pos = strrpos($field, '_');
        $table = substr($field, 0, $pos);
        $column = substr($field, $pos + 1);
        return "{$table}.{$column}";
    }

    public static function addWhereClause($queryBuilder, $languages, $search, $filterData) {
        if(!empty($search)) {
            $queryBuilder->where('1=1');
            foreach ($search as $key => $item) {
                $field = '';
                if (is_array($item)) {
                    $field = $key;
                } else {
                    $field = $item;
                }
                $tFields = Helper::transformFiled($field, $languages);
                foreach($tFields as $tField) {
                    if (isset($filterData['filter'][$tField]) && $filterData['filter'][$tField] !== '') {
                        if(is_array($item)) {
                            $comparison = $item['comparison'];
                        } else {
                            $comparison = 'eq';
                        }
                        $tableField = self::prepareDbField($tField);
                        $value = $filterData['filter'][$tField];
                        if ($comparison == 'eq') {
                            $queryBuilder->andWhere("{$tableField} = :{$tField}");
                            $queryBuilder->setParameter($tField, $value);
                        } elseif ($item['comparison'] == 'ge') {
                            $queryBuilder->andWhere("{$tableField} >= :{$tField}");
                            $queryBuilder->setParameter($tField, $value);
                        } elseif ($item['comparison'] == 'le') {
                            $queryBuilder->andWhere("{$tableField} <= :{$tField}");
                            $queryBuilder->setParameter($tField, $value);
                        }
                    }
                }
            }
        }
        return $queryBuilder;
    }

    public static function addOrdersClause($queryBuilder, $orders) {
        if (!empty($orders)) {
            foreach ($orders as $column => $order) {
                if ($order == '') continue;
                $tmpPos = strrpos($column, '_');
                $table = substr($column, 0, $tmpPos);
                $column = substr($column, $tmpPos + 1);
                $queryBuilder->addOrderBy("{$table}.{$column}", $order);
            }
        }
        return $queryBuilder;
    }

}