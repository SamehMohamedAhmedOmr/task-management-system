<?php

namespace App\Helpers;

use App\Http\Resources\PaginationResource;

// Pagination Helper methods

class Pagination
{

    // prepare pagination for collections
    public function preparePagination($object)
    {
        $data = $object->toArray();
        return new PaginationResource($data);
    }

}
