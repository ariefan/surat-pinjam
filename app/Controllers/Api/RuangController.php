<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class RuangController extends ResourceController
{
    protected $modelName = 'App\Models\RuangModel';
    protected $format = 'json';

    public function index()
    {
        $page    = $this->request->getVar('page') ?? 1;
        $perPage = $this->request->getVar('per_page') ?? 10;

        // Get the sorting parameters from the request
        $sortBy    = $this->request->getVar('sort_by') ?? 'id';
        $sortOrder = $this->request->getVar('sort_order') ?? 'asc';

        // Perform sorting
        $this->model->orderBy($sortBy, $sortOrder);

        // Get paginated results
        $ruangData = $this->model->paginate($perPage, 'default', $page);

        // Create pagination links
        $pager = $this->model->pager;

        // Prepare data to return
        $responseData = [
            'data'  => $ruangData,
            'pager' => $pager->getDetails(),
        ];

        return $this->respond($responseData);
    }

    public function show($id = null)
    {
        //
    }

    public function new()
    {
        //
    }

    public function create()
    {
        //
    }

    public function edit($id = null)
    {
        //
    }

    public function update($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        //
    }
}