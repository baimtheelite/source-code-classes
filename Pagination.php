<?php

namespace App\Repositories;

class Pagination {

    private $data;

    public function __construct(\Illuminate\Pagination\LengthAwarePaginator $data)
    {
        $this->data = $data;
    }

    public function getPagination()
    {
            return [
                'total' => $this->data->total(),
                'per_page' => $this->data->perPage(),
                'current_page' => $this->data->currentPage(),
                'last_page' => $this->data->lastPage(),
                'next_page_url' => $this->data->nextPageUrl(),
                'prev_page_url' => $this->data->previousPageUrl(),
                'from' => $this->data->firstItem(),
                'to' => $this->data->lastItem(),
            ];
    }
}
