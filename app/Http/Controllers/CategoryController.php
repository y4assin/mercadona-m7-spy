<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }

    public function importarCategories()
    {
        // Realizamos la solicitud HTTP a la API de Mercadona
        $client = new Client();
        $response = $client->request('GET', 'https://tienda.mercadona.es/api/categories/');

        // Verificamos si la solicitud fue exitosa (código de estado 200)
        if ($response->getStatusCode() == 200) {
            // Obtenemos los datos de la respuesta y los decodificamos
            $data = json_decode($response->getBody()->getContents(), true);

            // Procesamos y almacenamos las categorías en la base de datos
            foreach ($data['results'] as $section) {
                if (isset($section['categories'])) {

                    // Recibimos el id y el nombre de la categoría padre
                    foreach ($section['categories'] as $category) {

                        $parent_id = $section['id'];
                        $parent_name = $section['name'];
                        var_dump($parent_id);
                        var_dump($parent_name);
                        // Creamos o actualizamos la categoría en la base de datos
                        Category::updateOrCreate(
                            ['external_id' => $category['id']], // Buscamos por ID externo
                            [
                                'name' => $category['name'], // Asignamos el nombre de la categoría
                                'parent_id' => $parent_id, // Asignamos el ID de la categoría padre
                                'parent_name' => $parent_name // Asignamos el nombre de la categoría padre
                            ]
                        );
                    }
                }
            }

            return response()->json(['message' => 'Categories importades correctament']);
        } else {
            // En caso de error en la solicitud, retornamos un mensaje de error
            return response()->json(['error' => 'Error al obtindre les categories de la API de Mercadona'], 500);
        }
    }
}
