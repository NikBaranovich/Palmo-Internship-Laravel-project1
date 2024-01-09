<?php
if(!function_exists('sortOrder')){

    function sortOrder($sortBy)
    {
        return request('sort_by') === $sortBy && request('sort_order') === 'asc' ? 'desc' : 'asc';
    }
}
