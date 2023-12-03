<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    use HttpResponses;

    public function index(Request $request)
    {
        $categoriesModel = Category::select('id', 'name');

        $name = $request->get('name');
        if (!empty($name)) {
            $categoriesModel->where('name', 'like', "%$name%");
        }

        $categories = $categoriesModel->get();

        return $this->response('', 200, $categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return $this->error('Dados invalidados', 422, $validator->errors());
        }

        $created = Category::create($validator->validated());

        if ($created) {
            return $this->response('Categoria salva com sucesso!', 201, $created->only('id', 'name'));
        }

        return $this->error('Erro ao salvar a categoria!', 400);
    }


    public function show(string $id)
    {

        $category = Category::find($id)->only('id', 'name');

        if (empty($category)) {
            return $this->error('Categoria não encontrada', 404);
        }

        return $this->response('', 200, $category);
    }


    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return $this->error('Dados invalidados', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $category = Category::find($id);

        $updated = $category->update([
            'name' => $validated['name']
        ]);

        if ($updated) {
            return $this->response('Categoria atualizada com sucesso!', 201, $category->only('id', 'name'));
        }

        return $this->error('Erro ao atualizar a categoria!', 400);
    }


    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            return $this->error('Categoria não encontrada!', 404, ['Verifique o ID informado']);
        }

        try {
            if ($category->delete()) {
                return $this->response('Categoria deletada com sucesso!', 200);
            }
        } catch (Exception $ex) {
            return $this->error('Erro ao deletar categoria', 500);
        }
    }
}
