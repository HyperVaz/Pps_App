<template>
    <AuthenticatedLayout>
        <Head title="Заявки"/>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Мои заявки</h2>
        </template>
        <div class="px-4 sm:px-6 lg:px-8 mt-10">
            <div class="shadow-xl rounded-xl overflow-hidden border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-500 to-cyan-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Название заявки</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Описание</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Создана</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Телефон</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Telegram</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Изображения</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{order.name}}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{order.description}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{formatDate(order.created_at)}}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
            <span :class="{
              'bg-yellow-100 text-yellow-800': order.status === 'В процессе...',
              'bg-green-100 text-green-800': order.status === 'Завершено',
              'bg-red-100 text-red-800': order.status === 'Отменено'
            }" class="px-3 py-1 rounded-full text-xs font-semibold">
              {{order.status}}
            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{order.phone}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 hover:text-blue-700 text-center">
                            <a v-if="order.tg" :href="'https://t.me/' + order.tg.substring(1)" target="_blank" class="underline">
                                {{order.tg}}
                            </a>
                            <span v-else>-</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2 justify-center">
                                <img
                                    v-for="picture in order.pictures"
                                    :key="picture.id"
                                    :src="'/storage/' + picture.path"
                                    alt="Изображение заказа"
                                    class="w-16 h-16 object-cover rounded-md border border-gray-200 hover:scale-105 transition-transform cursor-pointer"
                                    @click="openImage('/storage/' + picture.path)"
                                >
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-3">
                                <button @click="deleteOrder(order.id)"
                                    class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-colors"
                                    title="Удалить"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" @click.self="showModal = false">
            <div class="bg-white p-4 rounded-lg max-w-4xl max-h-[90vh]">
                <button @click="showModal = false" class="absolute top-4 right-4 text-white hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img :src="currentImage" class="max-w-full max-h-[80vh] mx-auto" alt="Увеличенное изображение">
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";

export default {
    data(){
        return{
            showModal: false,
            currentImage: ''
        }
    },
    props: [
        'orders',
        'pictures',
    ],
    components: {
        Link,
        AuthenticatedLayout,
        Head
    },
    methods:{
        deleteOrder(orderId){
            return this.$inertia.delete(`orders/${orderId}`)
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('ru-RU')
        },
        openImage(url) {
            this.currentImage = url
            this.showModal = true
        }
    }
}
</script>
