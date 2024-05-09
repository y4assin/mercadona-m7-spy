<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use GuzzleHttp\Client;

class ProductController extends Controller
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
    public function show(Product $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Product $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $category)
    {
        //
    }

    public function importarProductePerCategoria()
    {
        // Recibimos las categorias
        $categories = Category::all();

        $client = new Client();
        // Recorremos las categorias
        foreach ($categories as $category) {

            $response = $client->request('GET', 'https://tienda.mercadona.es/api/categories/' . $category->external_id);
            // Verificamos si la solicitud fue exitosa (código de estado 200)
            if ($response->getStatusCode() == 200) {

                var_dump($category->external_id);
                // Obtenemos los datos de la respuesta y los decodificamos
                $data = json_decode($response->getBody()->getContents(), true);

                // Recorremos los productos de la categoria
                foreach ($categories as $category) {
                    $response = $client->request('GET', 'https://tienda.mercadona.es/api/categories/' . $category->external_id);

                    if ($response->getStatusCode() == 200) {
                        $data = json_decode($response->getBody()->getContents(), true);
                        foreach ($data['categories'] as $subcategory) { // Cambio de nombre de la variable aquí
                            foreach ($subcategory['products'] as $product) {
                                $productExists = Product::where('product_id', $product['id'])->first();

                                if (!$productExists) {
                                    Product::create([
                                        'product_id' => $product['id'],
                                        'name' => str_replace(',', ' ', $product['display_name']),
                                        'slug' => $product['slug'],
                                        'price' => $product['price_instructions']['unit_price'],
                                        'image_url' => $product['thumbnail'],
                                        'share_url' => $product['share_url'],
                                    ]);
                                }
                            }
                        }
                    } else {
                        return response()->json(['error' => 'Error al obtener las categorías de la API de Mercadona'], 500);
                    }
                }

                return response()->json(['message' => 'Categorías importadas correctamente']);
            } else {
                // En caso de error en la solicitud, retornamos un mensaje de error
                return response()->json(['error' => 'Error al obtindre les categories de la API de Mercadona'], 500);
            }
        }
    }
}
