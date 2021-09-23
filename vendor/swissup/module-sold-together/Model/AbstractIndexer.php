<?php

namespace Swissup\SoldTogether\Model;

abstract class AbstractIndexer
{
    /**
     * Factory for soldtogether data model
     */
    protected $modelFactory;

    public function reindex($pageNumber, $pageSize)
    {
        $data = $this->collectData($pageNumber, $pageSize);
        // add data to db
        foreach ($data as $item) {
            $model = $this->modelFactory->create();
            $model->addData($item)
                ->loadRelation(
                    $item['product_id'],
                    $item['related_id'],
                    $item['store_id']
                )
                ->setWeight($model->getWeight() + 1);
            try {
                $model->save();
            } catch (\Zend_Db_Statement_Exception $e) {
                if ($e->getCode() == 23000) {
                    // SQLSTATE[23000]: Integrity constraint violation
                    // either `product_id` doesn't exists or `relation_id`
                    // or maybe even `store_id` - so we can skip this record
                    continue;
                }

                // throw this exception further
                throw new \Zend_Db_Statement_Exception(
                    $e->getMessage(),
                    (int) $e->getCode(),
                    $e
                );
            }
        }
    }

    /**
     * Collect data
     *
     * @param  int $pageNumber
     * @param  int $pageSize
     * @return array
     */
    abstract protected function collectData($pageNumber, $pageSize);

    /**
     * Delete autogenerated relations
     */
    public function deleteAutogeneratedRelations()
    {
        $this->modelFactory->create()->deleteAutogeneratedRelations();
    }

    /**
     * Get number of items that will be processed
     *
     * @return int
     */
    abstract public function getItemsToProcessCount();
}