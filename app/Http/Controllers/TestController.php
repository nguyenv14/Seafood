<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository\ProductRepositoryInterface;

class TestController extends Controller
{
    /**
     * @var PostRepositoryInterface|\App\Repositories\Repository
     */
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $products = $this->productRepo->getAll();
        dd($products);
        return view('home.products', ['products' => $products]);
    }

    public function show($id = 2)
    {
        $product = $this->productRepo->find($id);
        echo   $product->product_id;
      //  dd($product);
        return view('home.product', ['product' => $product]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        //... Validation here

        $product = $this->productRepo->create($data);

        return view('home.products');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        //... Validation here

        $product = $this->productRepo->update($id, $data);

        return view('home.products');
    }

    public function destroy($id)
    {
        $this->productRepo->delete($id);
        
        return view('home.products');
    }
}
