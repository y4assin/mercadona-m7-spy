<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class CategoryController extends Controller
{
    /**
     * Muestra una lista de las categorías disponibles.
     */
    public function index()
    {
        // Lógica para listar todas las categorías
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        // Lógica para mostrar el formulario de creación
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Lógica para procesar y almacenar una nueva categoría
    }

    /**
     * Muestra una categoría específica.
     */
    public function show(Category $category)
    {
        // Lógica para mostrar una categoría en particular
    }

    /**
     * Muestra el formulario para editar una categoría específica.
     */
    public function edit(Category $category)
    {
        // Lógica para mostrar el formulario de edición
    }

    /**
     * Actualiza una categoría específica en la base de datos.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Lógica para procesar y actualizar una categoría existente
    }

    /**
     * Elimina una categoría específica de la base de datos.
     */
    public function destroy(Category $category)
    {
        // Lógica para eliminar una categoría
    }

    /**
     * Importa categorías desde una API externa.
     * Realiza una solicitud HTTP a la API de Mercadona para obtener las categorías.
     * Luego procesa y guarda las categorías en la base de datos.
     */
    public function importarCategories()
    {
        // Crear cliente HTTP
        $client = new Client();

        // Realizar una solicitud HTTP a la API de Mercadona
        $response = $client->request('GET', 'https://tienda.mercadona.es/api/categories/');

        // Verificar si la solicitud fue exitosa
        if ($response->getStatusCode() == 200) {
            // Decodificar datos de la respuesta
            $data = json_decode($response->getBody()->getContents(), true);

            // Procesar las categorías y almacenarlas
            foreach ($data['results'] as $section) {
                if (isset($section['categories'])) {
                    // Procesar cada categoría y almacenarla en la base de datos
                    foreach ($section['categories'] as $category) {
                        $parent_id = $section['id'];
                        $parent_name = $section['name'];
                        var_dump($parent_id);
                        var_dump($parent_name);
                        // Creamos o actualizamos la categoría
                        Category::updateOrCreate(
                            // Buscamos por ID externo
                            ['external_id' => $category['id']],
                            [
                                'name' => $category['name'],
                                'parent_id' => $parent_id,
                                'parent_name' => $parent_name
                            ]
                        );
                    }
                }
            }
        } else {
            // Devolver un mensaje de error si la solicitud falla
            return response()->json(['error' => 'Error al obtener las categorías de la API de Mercadona'], 500);
        }
    }
}
