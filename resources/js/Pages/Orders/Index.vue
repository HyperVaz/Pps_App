<template>
    <AuthenticatedLayout>
        <Head title="Заявки"/>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Мои заявки</h2>
        </template>
        <div class="px-2 sm:px-4 lg:px-8 mt-4 sm:mt-6 lg:mt-10">
            <div class="shadow-md sm:shadow-xl rounded-lg sm:rounded-xl overflow-hidden border border-gray-200">
                <!-- Мобильная версия (карточки) -->
                <div class="grid grid-cols-1 gap-4 p-4 sm:hidden">
                    <div v-for="order in orders" :key="order.id" class="border rounded-lg p-4 space-y-3">
                        <div v-if="is_admin" class="text-sm font-medium text-gray-900">Пользователь: {{order.user.name}}</div>
                        <div class="text-sm font-medium text-gray-900">Название: {{order.name}}</div>
                        <div class="text-sm text-gray-500">Описание: {{order.description}}</div>
                        <div class="text-sm text-gray-500">Создана: {{formatDate(order.created_at)}}</div>
                        <div class="text-sm">
                            Статус:
                            <span :class="{
                                'bg-yellow-100 text-yellow-800': order.status === 'В процессе',
                                'bg-green-100 text-green-800': order.status === 'Готов',
                                'bg-red-100 text-red-800': order.status === 'Отменено'
                            }" class="px-2 py-1 rounded-full text-xs font-semibold">
                                {{order.status}}
                            </span>
                        </div>
                        <div v-if="is_admin" class="text-sm">
                            Изменить статус:
                            <select v-model="status" name="stats" id="stats" class="text-xs w-full p-1 border rounded mt-1">
                                <option value="В процессе">В процессе</option>
                                <option value="Принят в работу">Принят в работу</option>
                                <option value="Готов">Готов</option>
                            </select>
                        </div>
                        <div class="text-sm text-gray-500">Телефон: {{order.phone}}</div>
                        <div class="text-sm text-blue-500 hover:text-blue-700">
                            Telegram:
                            <a v-if="order.tg" :href="'https://t.me/' + order.tg.substring(1)" target="_blank" class="underline">
                                {{order.tg}}
                            </a>
                            <span v-else>-</span>
                        </div>
                        <div class="text-sm">
                            Изображения:
                            <div class="flex flex-wrap gap-2 mt-1">
                                <img
                                    v-for="picture in order.pictures"
                                    :key="picture.id"
                                    :src="'/storage/' + picture.path"
                                    alt="Изображение заказа"
                                    class="w-12 h-12 object-cover rounded-md border border-gray-200 hover:scale-105 transition-transform cursor-pointer"
                                    @click="openImage('/storage/' + picture.path)"
                                >
                            </div>
                        </div>
                        <div v-if="order.complete_time != null" class="text-sm text-gray-500">Время сдачи: {{formatDate(order.complete_time)}}</div>
                        <div v-if="order.complete_time == null && !is_admin" class="text-sm text-gray-500">Время сдачи: Неизвестно</div>
                        <div v-if="is_admin" class="text-sm">
                            Установить дату:
                            <input v-model="complete_time" name="date" type="date" id="date" class="text-xs w-full p-1 border rounded mt-1" :placeholder="order.complete_time ? formatDate(order.complete_time) : 'Выберите дату'">
                        </div>
                        <div class="text-sm pt-2">
                            Действия:
                            <div class="flex items-center gap-2 mt-1">
                                <button @click="deleteOrder(order.id)"
                                        class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors"
                                        title="Удалить"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <button v-if="is_admin" :disabled="!complete_time || !status" @click="updateOrder(order.id)"
                                        class="text-xs text-white bg-blue-500 hover:bg-blue-600 px-2 py-1 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Обновить заказ"
                                >
                                    Готово
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Десктопная версия (таблица) -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-blue-500 to-cyan-600">
                        <tr>
                            <th v-if="is_admin" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Имя пользователя</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Название заявки</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Описание</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Создана</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Статус</th>
                            <th v-if="is_admin" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Изменить статус</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Телефон</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Telegram</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Изображения</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Время сдачи</th>
                            <th v-if="is_admin" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Установить дату</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">Действия</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors">
                            <td v-if="is_admin" class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{order.user.name}}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{order.name}}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 max-w-xs">{{order.description}}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">{{formatDate(order.created_at)}}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span :class="{
                                    'bg-yellow-100 text-yellow-800': order.status === 'В процессе',
                                    'bg-green-100 text-green-800': order.status === 'Готов',
                                    'bg-red-100 text-red-800': order.status === 'Отменено'
                                }" class="px-2 py-1 rounded-full text-xs font-semibold">
                                    {{order.status}}
                                </span>
                            </td>
                            <td v-if="is_admin" class="px-4 py-3 whitespace-nowrap">
                                <select v-model="status" name="stats" id="stats" class="text-xs sm:text-sm p-1 border rounded">
                                    <option value="В процессе">В процессе</option>
                                    <option value="Принят в работу">Принят в работу</option>
                                    <option value="Готов">Готов</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">{{order.phone}}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-500 hover:text-blue-700 text-center">
                                <a v-if="order.tg" :href="'https://t.me/' + order.tg.substring(1)" target="_blank" class="underline">
                                    {{order.tg}}
                                </a>
                                <span v-else>-</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2 justify-center">
                                    <img
                                        v-for="picture in order.pictures"
                                        :key="picture.id"
                                        :src="'/storage/' + picture.path"
                                        alt="Изображение заказа"
                                        class="w-12 h-12 object-cover rounded-md border border-gray-200 hover:scale-105 transition-transform cursor-pointer"
                                        @click="openImage('/storage/' + picture.path)"
                                    >
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{order.complete_time ? formatDate(order.complete_time) : 'Неизвестно'}}
                            </td>
                            <td v-if="is_admin" class="px-4 py-3 whitespace-nowrap">
                                <input v-model="complete_time" name="date" type="date" id="date" class="text-xs sm:text-sm p-1 border rounded" :placeholder="order.complete_time ? formatDate(order.complete_time) : 'Выберите дату'">
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="deleteOrder(order.id)"
                                            class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors"
                                            title="Удалить"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button v-if="is_admin" :disabled="!complete_time || !status" @click="updateOrder(order.id)"
                                            class="text-xs text-white bg-blue-500 hover:bg-blue-600 px-2 py-1 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Обновить заказ"
                                    >
                                        Готово
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" @click.self="showModal = false">
            <div class="bg-white p-2 sm:p-4 rounded-lg max-w-4xl max-h-[90vh] mx-2 sm:mx-4">
                <button @click="showModal = false" class="absolute top-2 right-2 sm:top-4 sm:right-4 text-white hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            currentImage: '',
            status: '',
            complete_time: null,
        }
    },
    props: [
        'orders',
        'pictures',
        'is_admin',
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
        updateOrder(orderId){
            if (new Date(this.date) < new Date()) {
                alert('Дата не может быть в прошлом!');
                return;
            }
            return this.$inertia.patch(`orders/${orderId}`, {
                'status': this.status,
                'complete_time': this.complete_time,
                'method': 'PATCH',
            })
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
