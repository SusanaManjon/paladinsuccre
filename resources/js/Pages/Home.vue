<template>
    <div class="min-h-screen bg-stone-50">

        <!-- NAVBAR -->
        <nav class="nav-blur fixed top-0 w-full z-50 border-b border-amber-900/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">P</span>
                        </div>
                        <div>
                            <span class="font-playfair text-white text-xl font-bold">Paladín</span>
                            <span class="font-playfair text-amber-400 text-xl font-bold"> Sucre</span>
                        </div>
                    </div>
                    <!-- Menu -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="#inicio" class="text-stone-300 hover:text-amber-400 transition text-sm font-medium">Inicio</a>
                        <a href="#productos" class="text-stone-300 hover:text-amber-400 transition text-sm font-medium">Productos</a>
                        <a href="#nosotros" class="text-stone-300 hover:text-amber-400 transition text-sm font-medium">Nosotros</a>
                        <a href="#contacto" class="text-stone-300 hover:text-amber-400 transition text-sm font-medium">Contacto</a>
                    </div>
                    <!-- CTA -->
                    <div class="flex items-center gap-3">
                        <button class="relative p-2 text-stone-300 hover:text-amber-400 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </button>
                        <button class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Ordenar ahora
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- HERO -->
        <section id="inicio" class="hero-gradient min-h-screen flex items-center pt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-amber-900/40 border border-amber-700/50 rounded-full px-4 py-2 mb-6">
                            <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                            <span class="text-amber-300 text-sm font-medium">Artesanal · Natural · Sucre, Bolivia</span>
                        </div>
                        <h1 class="font-playfair text-5xl lg:text-7xl font-bold text-white leading-tight mb-6">
                            El sabor<br>
                            <span class="text-amber-400">auténtico</span><br>
                            de Bolivia
                        </h1>
                        <p class="text-stone-300 text-lg leading-relaxed mb-8 max-w-lg">
                            Embutidos artesanales elaborados con recetas tradicionales bolivianas.
                            Chorizos, salchichas y jamones de la más alta calidad, directo a tu mesa.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="#productos" class="bg-amber-600 hover:bg-amber-500 text-white px-8 py-4 rounded-xl font-semibold text-lg transition flex items-center gap-2">
                                Ver productos
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            <a href="#nosotros" class="border border-stone-500 hover:border-amber-400 text-stone-300 hover:text-amber-400 px-8 py-4 rounded-xl font-semibold text-lg transition">
                                Nuestra historia
                            </a>
                        </div>
                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-6 mt-12 pt-12 border-t border-stone-700/50">
                            <div>
                                <div class="font-playfair text-3xl font-bold text-amber-400">15+</div>
                                <div class="text-stone-400 text-sm mt-1">Años de experiencia</div>
                            </div>
                            <div>
                                <div class="font-playfair text-3xl font-bold text-amber-400">50+</div>
                                <div class="text-stone-400 text-sm mt-1">Productos únicos</div>
                            </div>
                            <div>
                                <div class="font-playfair text-3xl font-bold text-amber-400">1000+</div>
                                <div class="text-stone-400 text-sm mt-1">Clientes felices</div>
                            </div>
                        </div>
                    </div>
                    <!-- Hero imagen placeholder -->
                    <div class="relative hidden lg:block">
                        <div class="w-full h-96 bg-gradient-to-br from-amber-900/40 to-stone-900/60 rounded-3xl border border-amber-700/30 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-8xl mb-4">🥩</div>
                                <p class="text-amber-300 font-playfair text-2xl">Embutidos Premium</p>
                                <p class="text-stone-400 text-sm mt-2">Imagen del producto destacado</p>
                            </div>
                        </div>
                        <!-- Badge flotante -->
                        <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl p-4 shadow-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-stone-800">100% Natural</div>
                                    <div class="text-xs text-stone-500">Sin conservantes</div>
                                </div>
                            </div>
                        </div>
                        <!-- Badge flotante 2 -->
                        <div class="absolute -top-4 -right-4 bg-amber-600 rounded-2xl p-4 shadow-2xl">
                            <div class="text-white text-center">
                                <div class="font-playfair text-2xl font-bold">⭐ 4.9</div>
                                <div class="text-amber-200 text-xs">Calificación</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CATEGORÍAS -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div v-for="cat in categorias" :key="cat.nombre"
                        class="card-hover bg-stone-50 border border-stone-200 rounded-2xl p-6 text-center cursor-pointer">
                        <div class="text-4xl mb-3">{{ cat.emoji }}</div>
                        <div class="font-semibold text-stone-800 text-sm">{{ cat.nombre }}</div>
                        <div class="text-stone-400 text-xs mt-1">{{ cat.cantidad }} productos</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRODUCTOS DESTACADOS -->
        <section id="productos" class="py-20 bg-stone-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Encabezado -->
                <div class="text-center mb-14">
                    <span class="text-amber-600 font-semibold text-sm uppercase tracking-widest">Nuestros productos</span>
                    <h2 class="font-playfair text-4xl lg:text-5xl font-bold text-stone-900 mt-2">
                        Selección Premium
                    </h2>
                    <p class="text-stone-500 mt-4 max-w-xl mx-auto">
                        Elaborados con los mejores cortes de carne y especias seleccionadas de los valles bolivianos.
                    </p>
                </div>
                <!-- Grid productos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div v-for="producto in productos" :key="producto.nombre"
                        class="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-stone-100">
                        <!-- Imagen -->
                        <div class="h-48 flex items-center justify-center text-6xl"
                            :class="producto.bgColor">
                            {{ producto.emoji }}
                        </div>
                        <!-- Info -->
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
                                        {{ producto.categoria }}
                                    </span>
                                    <h3 class="font-semibold text-stone-800 mt-2 text-sm">{{ producto.nombre }}</h3>
                                </div>
                                <div v-if="producto.destacado" class="w-6 h-6 bg-amber-400 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-xs">★</span>
                                </div>
                            </div>
                            <p class="text-stone-400 text-xs mt-1 mb-4 leading-relaxed">{{ producto.descripcion }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-playfair text-xl font-bold text-stone-900">Bs. {{ producto.precio }}</span>
                                    <span class="text-stone-400 text-xs ml-1">/ {{ producto.unidad }}</span>
                                </div>
                                <button class="bg-amber-600 hover:bg-amber-500 text-white p-2 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-12">
                    <button class="border-2 border-amber-600 text-amber-600 hover:bg-amber-600 hover:text-white px-10 py-4 rounded-xl font-semibold transition">
                        Ver todos los productos
                    </button>
                </div>
            </div>
        </section>

        <!-- BANNER CALIDAD -->
        <section class="py-20 hero-gradient">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="p-8">
                        <div class="w-16 h-16 bg-amber-600/20 border border-amber-600/40 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🌿</span>
                        </div>
                        <h3 class="font-playfair text-xl font-bold text-white mb-3">100% Natural</h3>
                        <p class="text-stone-400 text-sm leading-relaxed">Sin conservantes artificiales. Solo ingredientes naturales seleccionados.</p>
                    </div>
                    <div class="p-8">
                        <div class="w-16 h-16 bg-amber-600/20 border border-amber-600/40 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🏆</span>
                        </div>
                        <h3 class="font-playfair text-xl font-bold text-white mb-3">Calidad Premium</h3>
                        <p class="text-stone-400 text-sm leading-relaxed">Certificados por SENASAG. Los más altos estándares de calidad boliviana.</p>
                    </div>
                    <div class="p-8">
                        <div class="w-16 h-16 bg-amber-600/20 border border-amber-600/40 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🚚</span>
                        </div>
                        <h3 class="font-playfair text-xl font-bold text-white mb-3">Envío a domicilio</h3>
                        <p class="text-stone-400 text-sm leading-relaxed">Entrega rápida en Sucre. Pedí hoy y recibí mañana en tu puerta.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- NOSOTROS -->
        <section id="nosotros" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="bg-stone-100 rounded-3xl h-80 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-7xl mb-4">👨‍🍳</div>
                            <p class="text-stone-500 font-playfair text-xl">Maestros embutidores</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-amber-600 font-semibold text-sm uppercase tracking-widest">Nuestra historia</span>
                        <h2 class="font-playfair text-4xl font-bold text-stone-900 mt-2 mb-6">
                            Tradición familiar desde 2009
                        </h2>
                        <p class="text-stone-600 leading-relaxed mb-4">
                            Paladín Sucre nació de la pasión por recuperar las recetas tradicionales de embutidos bolivianos.
                            Desde nuestra planta en Sucre, elaboramos cada producto con el mismo cuidado artesanal de siempre.
                        </p>
                        <p class="text-stone-600 leading-relaxed mb-8">
                            Trabajamos directamente con ganaderos locales de Chuquisaca para garantizar la frescura
                            y calidad de cada corte que llega a nuestros productos.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2 text-stone-700">
                                <div class="w-5 h-5 bg-amber-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm">Certificado SENASAG</span>
                            </div>
                            <div class="flex items-center gap-2 text-stone-700">
                                <div class="w-5 h-5 bg-amber-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm">Cadena de frío garantizada</span>
                            </div>
                            <div class="flex items-center gap-2 text-stone-700">
                                <div class="w-5 h-5 bg-amber-100 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm">Ganaderos locales</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer id="contacto" class="bg-stone-900 text-stone-400 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">P</span>
                            </div>
                            <span class="font-playfair text-white text-xl font-bold">Paladín Sucre</span>
                        </div>
                        <p class="text-sm leading-relaxed max-w-xs">
                            Embutidos artesanales de calidad premium. Elaborados con tradición boliviana en el corazón de Sucre.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Productos</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-amber-400 transition">Chorizos</a></li>
                            <li><a href="#" class="hover:text-amber-400 transition">Salchichas</a></li>
                            <li><a href="#" class="hover:text-amber-400 transition">Jamones</a></li>
                            <li><a href="#" class="hover:text-amber-400 transition">Mortadelas</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Contacto</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2">
                                <span>📍</span> Sucre, Chuquisaca, Bolivia
                            </li>
                            <li class="flex items-center gap-2">
                                <span>📱</span> +591 70000000
                            </li>
                            <li class="flex items-center gap-2">
                                <span>✉️</span> info@paladinsucre.com
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-stone-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs">© 2025 Paladín Sucre. Todos los derechos reservados.</p>
                    <p class="text-xs">Hecho con ❤️ en Sucre, Bolivia</p>
                </div>
            </div>
        </footer>

    </div>
</template>

<script setup>
const categorias = [
    { nombre: 'Chorizos', emoji: '🌭', cantidad: 8 },
    { nombre: 'Salchichas', emoji: '🥩', cantidad: 6 },
    { nombre: 'Jamones', emoji: '🍖', cantidad: 5 },
    { nombre: 'Mortadelas', emoji: '🥓', cantidad: 4 },
]

const productos = [
    {
        nombre: 'Chorizo Criollo Premium',
        categoria: 'Chorizos',
        descripcion: 'Elaborado con carne de res y cerdo, especias andinas y ají colorado.',
        precio: '35.00',
        unidad: '500g',
        emoji: '🌭',
        bgColor: 'bg-red-50',
        destacado: true,
    },
    {
        nombre: 'Salchicha Frankfurt',
        categoria: 'Salchichas',
        descripcion: 'Suave textura y sabor ahumado. Ideal para parrillas y sandwiches.',
        precio: '28.00',
        unidad: '400g',
        emoji: '🥩',
        bgColor: 'bg-orange-50',
        destacado: false,
    },
    {
        nombre: 'Jamón del País',
        categoria: 'Jamones',
        descripcion: 'Receta tradicional boliviana. Curado en sal y especias naturales.',
        precio: '45.00',
        unidad: '300g',
        emoji: '🍖',
        bgColor: 'bg-amber-50',
        destacado: true,
    },
    {
        nombre: 'Mortadela Especial',
        categoria: 'Mortadelas',
        descripcion: 'Fina textura con aceitunas y pimientos. Perfecta para loncheras.',
        precio: '22.00',
        unidad: '500g',
        emoji: '🥓',
        bgColor: 'bg-rose-50',
        destacado: false,
    },
    {
        nombre: 'Chorizo Picante',
        categoria: 'Chorizos',
        descripcion: 'Con locoto y especias bolivianas para los amantes del picante.',
        precio: '38.00',
        unidad: '500g',
        emoji: '🌶️',
        bgColor: 'bg-red-50',
        destacado: false,
    },
    {
        nombre: 'Salchicha Cocktail',
        categoria: 'Salchichas',
        descripcion: 'Pequeñas y deliciosas. Perfectas para aperitivos y reuniones.',
        precio: '25.00',
        unidad: '300g',
        emoji: '🍡',
        bgColor: 'bg-yellow-50',
        destacado: false,
    },
    {
        nombre: 'Jamón Ahumado',
        categoria: 'Jamones',
        descripcion: 'Ahumado con madera de quebracho. Sabor intenso y duradero.',
        precio: '52.00',
        unidad: '300g',
        emoji: '🔥',
        bgColor: 'bg-stone-100',
        destacado: true,
    },
    {
        nombre: 'Pack Familiar',
        categoria: 'Combos',
        descripcion: 'Chorizo + Salchicha + Jamón. El combo perfecto para tu familia.',
        precio: '89.00',
        unidad: 'pack',
        emoji: '🎁',
        bgColor: 'bg-green-50',
        destacado: true,
    },
]
</script>
