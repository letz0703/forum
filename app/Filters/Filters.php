<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters {

    protected $request, $builder, $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
//
        foreach ($this->getFilters() as $filter=>$value)
        {
            if (method_exists($this,$filter)){
                $this->$filter($this->request->$filter);
            }

//            $this->$filter($this->request->$filter);
            $this->$filter($value);

        }

        return $this->builder;
    }

    /**
     * @return array
     */
    protected function getFilters()
    {
        return $this->request->only($this->filters);
    }
}