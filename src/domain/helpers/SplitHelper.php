<?php

namespace yii2extension\ml\domain\helpers;

use yii2extension\ml\domain\dto\CollectionDto;
use yii2rails\domain\data\Query;
use yii2rails\extension\arrayTools\helpers\ArrayIterator;

class SplitHelper {

	public static function split($collection, $percent) : CollectionDto {
        $collection = self::cleanCollection($collection);
        $collectionDto = new CollectionDto;
		$collectionDto->all = $collection;

	    $offset = (count($collection) / 100) * $percent;
		
		$iterator = new ArrayIterator();
		$iterator->setCollection($collection);
		$query = Query::forge();
		$query->limit($offset);
		$collectionDto->train = $iterator->all($query);
		
		$query = Query::forge();
		$query->offset($offset);
		$collectionDto->test = $iterator->all($query);
		return $collectionDto;
	}

	public static function cleanCollection($collection) {
        $newCollection = [];
        foreach ($collection as $testDocument) {
            if(count($testDocument) > 1) {
                $newCollection[] = $testDocument;
            }
        }
        return $newCollection;
    }
}
