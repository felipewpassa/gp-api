<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class ProductController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $productModel = Product::select(
            'id',
            'name',
            'description',
            'price',
            'expiry_date',
            'image',
            'category_id'
        );

        $name = $request->get('name');
        if (!empty($name)) {
            $productModel->where('name', 'like', "%$name%");
        }

        $description  = $request->get('description');
        if (!empty($description)) {
            $productModel->where('description', 'like', "%$description%");
        }

        $perPage = 5;
        $currentPage = (int) $request->get('page');
        $productModel = $productModel->forPage($currentPage, $perPage);
        $totalRegisters = Product::count();
        $totalPages = ceil($totalRegisters / $perPage);

        $productsCollection = $productModel->get();

        return $this->response('', 200, $productsCollection, $totalPages);
    }


    public function store(Request $request)
    {

        $validator = $validator = self::getValidator($request->all());

        if ($validator->fails()) {
            return $this->error('Dados invalidos', 422, $validator->errors());
        }

        $created = Product::create($validator->validated());

        if ($created) {
            return $this->response('Produto salvo com sucesso!', 201, $created);
        }

        return $this->error('Erro ao salvar o produto!', 400);
    }


    public function show(string $id)
    {

        $product = Product::find($id);

        if (empty($product)) {
            return $this->error('Produto não encontrado', 404);
        }

        return $this->response('', 200, $product->load('category'));
    }


    public function update(Request $request, string $id)
    {

        $validator = self::getValidator($request->all());;

        if ($validator->fails()) {
            return $this->error('Dados invalidos', 422, $validator->errors());
        }

        $product = Product::find($id);
        if (empty($product)) {
            return $this->error('Falha ao atualizar produto', 404, [
                'O produto informado não foi encontrado'
            ]);
        }

        $validated = $validator->validated();

        $updated = $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'expiry_date' => $validated['expiry_date'],
            'image' => $validated['image'],
            'category_id' => $validated['category_id']
        ]);

        if (!$updated) {
            return $this->error('Erro inesperado', 500);
        }

        return $this->response(
            'Produto atualizado com sucesso!',
            201,
            $product->only(
                'name',
                'description',
                'price',
                'expiry_date',
                'image',
                'category_id'
            )
        );
    }


    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return $this->error('Produto não encontrado', 404);
        }

        $deleted = $product->delete();

        if (!$deleted) {
            return $this->error('Não foi possivel excluir o produto', 500);
        }

        return $this->response('Produto deletado com sucesso!', 200);
    }


    private static function getValidator($data): ValidationValidator
    {
        $currentDate = date('Y-m-d');

        $validator = Validator::make($data, [
            'name' => 'max:50',
            'description' => 'max:200',
            'price' => 'numeric|gt:0',
            'expiry_date' => "after:$currentDate",
            'image' => '',
            'category_id' => 'required|gt:0|exists:categories,id'
        ]);

        return $validator;
    }
}
