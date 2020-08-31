<?php

namespace WhereSearch;
use Illuminate\Support\Arr;

trait SearchTrait{
	
	/**
     * WHERE $column LIKE %$value% query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $column
     * @param $value
     * @param string                                $boolean
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereLike($query, $column, $value, $boolean = 'and')
    {
        return $query->where($column, 'LIKE', "%$value%", $boolean);
    }

    /**
     * WHERE $column LIKE $value% query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $column
     * @param $value
     * @param string                                $boolean
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereLeftLike($query, $column, $value, $boolean = 'and')
    {
        return $query->where($column, 'LIKE', "$value%", $boolean);
    }

    /**
     * WHERE $column LIKE %$value query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $column
     * @param $value
     * @param string                                $boolean
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function whereRightLike($query, $column, $value, $boolean = 'and')
    {
        return $query->where($column, 'LIKE', "%$value", $boolean);
    }
	
	/*
	 * @param array                                 
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder $params
     */
	public function scopeSearch($query,$params=null){


        $new_params = [];
        foreach ($params as $key => $_param) {
            $param = [];
            if (is_array($_param)) {

                $param['column'] = Arr::get($_param, 'column', $key);
                $param['operator'] = Arr::get($_param, 'operator', '=');
                $param['value'] = Arr::get($_param, 'value', null);

            } else {
                if ($_param == null) {
                    continue;
                }
                $param['column'] = $key;
                $param['operator'] = '=';
                $param['value'] = $_param;
            }

            if ($param['value'] == null) {
            	
            	
                continue;
            }

        }

        $params = [];
        $params = $new_params;
        unset($new_params);
         	
        if (is_array($params)) {
            foreach ($params as $key => $param) {

                switch (strtolower($param['operator'])) {
                    case 'between':
                        $query->whereBetween($param['column'], $param['value']);
                        break;
                    case 'not_between':
                        $query->whereNotBetween($param['column'], $param['value']);
                        break;
                    case 'is_null':
                        $query->whereNull($param['column']);
                        break;
                    case 'is_not_null':
                        $query->whereNotNull($param['column']);
                        break;
                    case 'like_all':
                        $query->whereLike($param['column'], $param['value']);
                        break;
                    case 'like_left':
                        $query->whereLeftLike($param['column'], $param['value']);
                        break;
                    case 'like_right':
                        $query->whereRightLike($param['column'], $param['value']);
                        break;
                    case 'in':
                        $query->whereIn($param['column'], $param['value']);
                        break;
                    default:
                    
                        $query->where($param['column'], $param['operator'], $param['value']);
                }
            }
        }
        return $query;
	}
	
}
