<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import NavLink from "@/Components/NavLink.vue";
</script>

<template>
    <Head title="Добро пожаловать" />

    <AuthenticatedLayout>
        <template #header>
            <div class="bg-gradient-to-r from-green-400 to-green-600 py-8 px-6 rounded-b-xl shadow-lg">
                <h1 class="text-3xl font-bold text-white text-center animate-fade-in">
                    Добро пожаловать в наш сервис!
                </h1>
                <p class="text-green-100 text-center mt-2 text-lg">
                    Удобная платформа для управления вашими заказами
                </p>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- Статистика заказов -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
                        <div class="bg-green-500 px-6 py-4 flex items-center">
                            <svg class="h-8 w-8 text-white mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h2 class="text-xl font-semibold text-white">Ваши заказы</h2>
                        </div>
                        <div class="px-6 py-4 grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-4xl font-bold text-green-600"></div>
                                <div class="text-green-800 mt-2">Открытых {{ordersCount}}</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-4xl font-bold text-green-600"></div>
                                <div class="text-green-800 mt-2">Завершенных</div>
                            </div>
                        </div>
                    </div>

                    <!-- Быстрые действия -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="bg-green-600 px-6 py-4 flex items-center">
                            <svg class="h-8 w-8 text-white mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <h2 class="text-xl font-semibold text-white">Быстрые действия</h2>
                        </div>
                        <div class="px-6 py-4 grid grid-cols-2 gap-4">
                            <NavLink :href="route('order_create')" class="bg-green-100 hover:bg-green-200 text-green-800 font-medium py-3 px-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-200">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Новый заказ
                            </Navlink>
                            <NavLink :href="route('orders')" class="bg-green-100 hover:bg-green-200 text-green-800 font-medium py-3 px-4 rounded-lg flex flex-col items-center justify-center transition-colors duration-200">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Все заказы
                            </Navlink>
                        </div>
                    </div>
                </div>

                <!-- Блог новостей -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-green-700 px-6 py-4 flex items-center">
                        <svg class="h-8 w-8 text-white mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-white">Новости и обновления</h2>
                    </div>
                    <div class="divide-y divide-green-100">
                        <div v-for="(item, index) in news" :key="index" class="p-6 hover:bg-green-50 transition-colors duration-200">
                            <div class="flex items-start">
                                <div class="bg-green-100 p-2 rounded-full mr-4 flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-green-800">{{ item.title }}</h3>
                                    <p class="text-green-600 text-sm mb-2">{{ new Date(item.created_at).toLocaleDateString() }}</p>
                                    <p class="text-gray-700">{{ item.excerpt || item.content.substring(0, 150) + '...' }}</p>
                                    <a v-if="item.content.length > 150" href="#" class="text-green-600 hover:text-green-800 text-sm font-medium inline-flex items-center mt-2">
                                        Читать далее
                                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-green-100 text-center">
                        <a href="/news" class="text-green-600 hover:text-green-800 font-medium inline-flex items-center">
                            Все новости
                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
export default {
    props: [
        'ordersCount'
    ],
    mounted() {
        console.log('Получено ordersCount:', this.ordersCount) // Проверьте консоль
    }
}
</script>
<style>
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

::-webkit-scrollbar {
    width: 8px;
    background-color: #f1f8e9;
}

::-webkit-scrollbar-thumb {
    background-color: #a5d6a7;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background-color: #81c784;
}
</style>
