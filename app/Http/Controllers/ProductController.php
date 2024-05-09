<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use GuzzleHttp\Client;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Muestra una lista de todos los productos.
     */
    public function index()
    {
        // Lógica para listar todos los productos
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        // Lógica para mostrar el formulario de creación
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     */
    public function store(StoreProductRequest $request)
    {
        // Lógica para procesar y almacenar un nuevo producto
    }

    /**
     * Muestra un producto específico.
     */
    public function show(Product $product)
    {
        // Lógica para mostrar un producto específico
    }

    /**
     * Muestra el formulario para editar un producto específico.
     */
    public function edit(Product $product)
    {
        // Lógica para mostrar el formulario de edición
    }

    /**
     * Actualiza un producto específico en la base de datos.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Lógica para procesar y actualizar un producto existente
    }

    /**
     * Elimina un producto específico de la base de datos.
     */
    public function destroy(Product $product)
    {
        // Lógica para eliminar un producto
    }
    public function importarProductePerCategoria()
    {
        // Obtenemos todas las categorías locales
        $categories = Category::all();
        $client = new Client();

        // Iteramos sobre cada categoría local
        foreach ($categories as $category) {
            // Realizamos una solicitud HTTP para obtener los datos de la categoría desde la API de Mercadona
            $response = $client->request('GET', 'https://tienda.mercadona.es/api/categories/' . $category->external_id);

            // Verificamos si la solicitud fue exitosa (código de estado 200)
            if ($response->getStatusCode() == 200) {
                // Decodificamos los datos obtenidos de la respuesta
                $data = json_decode($response->getBody()->getContents(), true);

                // Recorremos cada subcategoría dentro de la categoría
                foreach ($data['categories'] as $subcategory) {
                    // Recorremos los productos dentro de la subcategoría
                    foreach ($subcategory['products'] as $product) {
                        // Verificamos si el producto ya existe
                        $productExists = Product::where('external_id', $product['id'])->first();

                        // Si no existe, lo creamos en la base de datos
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
                // Si la solicitud falla, devolvemos un mensaje de error
                return response()->json(['error' => 'Error al obtener las categorías de la API de Mercadona'], 500);
            }
        }

        // Si todas las categorías se procesan correctamente, devolvemos un mensaje de éxito
        return response()->json(['message' => 'Productos importados correctamente']);
    }
}