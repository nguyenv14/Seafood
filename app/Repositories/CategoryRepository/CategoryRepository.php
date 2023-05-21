<?php
namespace App\Repositories\CategoryRepository;

use App\Repositories\BaseRepository;
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Category::class;
    }
    public function getAllByPaginate($value){
        return $this->model->paginate($value);
    }
}