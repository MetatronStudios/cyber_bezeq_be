<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Utils\DateUtil;
use App\Utils\FileUtil;
use DB;

class ParentService
{
    protected $repository;
    
    /**
     * @codeCoverageIgnore
     */
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    /**
     * @codeCoverageIgnore
     */
    public function rollBack()
    {
        DB::rollBack();
    }

    /**
     * @codeCoverageIgnore
     */
    public function commit()
    {
        DB::commit();
    }

    public function getTotal($startDate=null, $endDate=null)
    {
        
        $startDate  = ($startDate) ? DateUtil::strdateToDate($startDate) : null;
        $endDate    = ($endDate) ? DateUtil::strdateToDate($endDate) : null;
    	return $this->repository->getTotal($startDate, $endDate);
    }

    public function getAllPaginate($data)
    {
    	return $this->repository->getAllPaginate($data);
    }
    
    public function getAll()
    {
    	return $this->repository->getAll();
    }

    public function getById($id)
    {
        $item = $this->repository->getById($id);
        if ( !$item ) {
            throw new BusinessException('Error, id not found');
        }
        return $item;
    }

    public function add($data)
    {
        $item = $this->repository->add($data);
        if ( !$item ) {
            throw new BusinessException('Error, on add to table'); // @codeCoverageIgnore
        }
        return $item;
    }

    public function update($id, $data)
    {
        $item = $this->getById($id);
        if ( !$this->repository->update($item, $data) ) {
            throw new BusinessException('Error, on update to table'); // @codeCoverageIgnore
        }
        return true;
    }

    public function delete($id)
    {
        $item = $this->getById($id);
        if ( !$this->repository->delete($item) ) {
            throw new BusinessException('Error, on delete from table'); // @codeCoverageIgnore
        }
        return true;
    }
    
    /**
     * @codeCoverageIgnore
     */
    public function export()
    {
        $items = $this->getAll();
        $out = '';
        foreach($items as $item) {
            $fields = [];
            $values = [];
            foreach($item->toArray() as $field => $value) {
                array_push($fields, $field);
                array_push($values, $value);
            }
            if ($out == '') {
                $out = '"'.implode('","', $fields).'"'."\r\n";
            }
            $out .= '"'.implode('","', $values).'"'."\r\n";
        }
        return FileUtil::addBom($out);
    }
}