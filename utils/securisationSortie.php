<?php

function securisationSortie($data)
{
    if (is_array($data)) {
        return array_map('securisationSortie', $data);
    } elseif (is_string($data)) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    } else {
        return $data;
    }
}
