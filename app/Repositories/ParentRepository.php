<?php

namespace App\Repositories;

use Exception;

class ParentRepository
{
    protected $model;

    public function getByFirst($conditions)
    {
        $query = $this->model::query();
        foreach($conditions as $condition) {
            foreach($condition as $column => $value) {
                $query = $query->where($column, $value);
            }
        }
        return $query->first();
    }

    public function getBy($conditions)
    {
        $query = $this->model::query();
        foreach($conditions as $condition) {
            foreach($condition as $column => $value) {
                $query = $query->where($column, $value);
            }
        }
        return $query->get();
    }

    public function getTotal($startDate=null, $endDate=null)
    {
        $query = $this->model::get();
        if ( $startDate != null ) {
            $query = $query->where('created_at', '>=', $startDate.' 00:00:00');
        }
        if ( $endDate != null ) {
            $query = $query->where('created_at', '<=', $endDate.' 23:59:59');
        }
        return $query->count();
    }

	public function getAll()
    {
        return $this->model::get();
    }

    public function getAllPaginate($data)
    {
        $sort = $data && $data['sort'] ? $data['sort'] : 'id';
        $order = $data && $data['order'] ? $data['order'] : 'desc';
        return $this->model::orderBy($sort, $order)->paginate(10);
    }

	public function getById($id)
    {
        return $this->model::find($id);
    }

    public function add($data)
    {
        try {
            return $this->model::create($data);
        } catch (Exception $e) {  // @codeCoverageIgnore
            return false; // @codeCoverageIgnore
        }
    }

    public function update($item, $data)
    {
        $item->update($data);
        return $item->save();
    }

    public function delete($item)
    {
        return $item->delete();
    }
}