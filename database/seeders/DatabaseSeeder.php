<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@paladinsucre.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'phone'    => '+591 70000000',
            'address'  => 'Calle Bolívar #456, Sucre, Bolivia',
        ]);

        // Cliente de prueba
        User::create([
            'name'     => 'Cliente Prueba',
            'email'    => 'cliente@test.com',
            'password' => Hash::make('password123'),
            'role'     => 'client',
            'phone'    => '+591 71111111',
            'address'  => 'Av. Hernando Siles #123, Sucre',
        ]);

        // Categorías
        $chorizos   = Category::create(['name' => 'Chorizos',   'description' => 'Variedad de chorizos artesanales elaborados con las mejores carnes seleccionadas.', 'is_active' => true]);
        $salchichas = Category::create(['name' => 'Salchichas', 'description' => 'Salchichas frescas y ahumadas de primera calidad para toda ocasión.', 'is_active' => true]);
        $jamones    = Category::create(['name' => 'Jamones',    'description' => 'Jamones curados y ahumados con recetas tradicionales bolivianas.', 'is_active' => true]);
        $mortadelas = Category::create(['name' => 'Mortadelas', 'description' => 'Mortadelas especiales elaboradas con ingredientes seleccionados.', 'is_active' => true]);
        $combos     = Category::create(['name' => 'Combos',     'description' => 'Packs especiales con los mejores productos para toda la familia.', 'is_active' => true]);

        // Productos
        Product::create([
            'category_id' => $chorizos->id,
            'name'        => 'Chorizo Criollo Premium',
            'description' => 'Chorizo artesanal elaborado con carne de cerdo seleccionada y especias naturales de la región. Sabor auténtico boliviano.',
            'price'       => 35.00,
            'weight'      => '500g',
            'stock'       => 80,
            'is_active'   => true,
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $salchichas->id,
            'name'        => 'Salchicha Frankfurt',
            'description' => 'Salchichas estilo Frankfurt, perfectas para hot dogs y parrilladas familiares. Textura suave y sabor inigualable.',
            'price'       => 28.00,
            'weight'      => '400g',
            'stock'       => 100,
            'is_active'   => true,
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $jamones->id,
            'name'        => 'Jamón del País',
            'description' => 'Jamón curado artesanalmente siguiendo la tradición boliviana. Sabor único e inigualable, ideal para cualquier ocasión.',
            'price'       => 45.00,
            'weight'      => '300g',
            'stock'       => 60,
            'is_active'   => true,
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $mortadelas->id,
            'name'        => 'Mortadela Especial',
            'description' => 'Mortadela elaborada con ingredientes de primera calidad. Ideal para sándwiches y picadas.',
            'price'       => 22.00,
            'weight'      => '500g',
            'stock'       => 90,
            'is_active'   => true,
            'is_featured' => true,
        ]);

        Product::create([
            'category_id' => $chorizos->id,
            'name'        => 'Chorizo Picante',
            'description' => 'Chorizo con un toque picante especial, para los amantes del sabor intenso y la buena parrillada.',
            'price'       => 38.00,
            'weight'      => '500g',
            'stock'       => 70,
            'is_active'   => true,
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $salchichas->id,
            'name'        => 'Salchicha Cocktail',
            'description' => 'Mini salchichas ideales para eventos, fiestas y reuniones. Tamaño perfecto para aperitivos.',
            'price'       => 25.00,
            'weight'      => '300g',
            'stock'       => 85,
            'is_active'   => true,
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $jamones->id,
            'name'        => 'Jamón Ahumado',
            'description' => 'Jamón ahumado con madera de quebracho, sabor ahumado natural intenso que enamora al paladar.',
            'price'       => 52.00,
            'weight'      => '300g',
            'stock'       => 50,
            'is_active'   => true,
            'is_featured' => false,
        ]);

        Product::create([
            'category_id' => $combos->id,
            'name'        => 'Pack Familiar',
            'description' => 'Selección especial de nuestros mejores embutidos para toda la familia. Incluye chorizos, salchichas y jamón.',
            'price'       => 89.00,
            'weight'      => '1.5kg',
            'stock'       => 40,
            'is_active'   => true,
            'is_featured' => true,
        ]);
    }
}
