<?php

function securisationSortie($data)
{

    if ($data instanceof MongoDB\Model\BSONDocument || $data instanceof MongoDB\Model\BSONArray) {
        $data = $data->getArrayCopy();
    } elseif ($data instanceof stdClass) {
        $data = (array) $data;
    }

    if ($data instanceof MongoDB\BSON\ObjectId) {
        return (string) $data;
    }
    if ($data instanceof MongoDB\BSON\UTCDateTime) {
        return $data->toDateTime()->getTimestamp() * 1000; // ms
    }
    if ($data instanceof MongoDB\BSON\Decimal128) {
        return (string) $data;
    }


    if (is_array($data)) {
        $sortie = [];
        foreach ($data as $key => $value) {
            if ($key === '_id' && $value instanceof MongoDB\BSON\ObjectId) {
                $sortie['id'] = (string) $value;
                continue;
            }
            $sortie[$key] = securisationSortie($value);
        }
        return $sortie;
    }


    if (is_string($data)) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    return $data;
}
