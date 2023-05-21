<?php
namespace App\Repositories\SliderRepository;

use App\Repositories\RepositoryInterface;

interface SliderRepositoryInterface extends RepositoryInterface
{
    public function getAllByPaginate($value);
    public function insert_slider($data, $get_image);
    public function update_slider($data, $get_image);  
    public function delete_slider($slider_id);
}
