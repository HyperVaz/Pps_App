<template>
    <AuthenticatedLayout>
        <Head title="Заявки"/>
        <template #header>
            <h2 class="font-sans text-2xl font-semibold text-gray-800 leading-tight">Мои заявки</h2>
        </template>

        <transition
            enter-active-class="transition ease-out duration-300 transform"
            enter-from-class="translate-y-2 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-2 opacity-0"
        >
            <div
                v-if="$page.props.flash.success"
                class="fixed bottom-6 right-6 z-50 flex items-center justify-between p-4 max-w-sm w-full bg-green-50 border border-green-200 rounded-lg shadow-lg"
            >
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium text-green-800">
                        {{ $page.props.flash.success }}
                    </p>
                </div>
                <button
                    @click="$page.props.flash.success = null"
                    class="text-green-500 hover:text-green-700 focus:outline-none"
                >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </transition>

        <div class="px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8 lg:mt-10">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <!-- Мобильная версия (карточки) -->
                <div class="grid grid-cols-1 gap-5 p-5 sm:hidden">
                    <div v-for="order in orders" :key="order.id" class="bg-white border border-gray-200 rounded-xl p-5 space-y-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div v-if="is_admin" class="text-sm font-medium text-gray-900 font-sans">Пользователь: {{order.user.name}}</div>
                        <div class="text-base font-semibold text-gray-900 font-sans">Название: {{order.name}}</div>
                        <div class="text-sm text-gray-600 font-sans">Описание: {{order.description}}</div>
                        <div class="text-sm text-gray-500 font-sans">Создана: {{formatDate(order.created_at)}}</div>
                        <div class="text-sm font-sans">
                            Статус:
                            <span :class="{
                                'bg-yellow-100 text-yellow-800': order.status.includes('В процессе'),
                                'bg-green-100 text-green-800': order.status === 'Готов',
                                'bg-red-100 text-red-800': order.status === 'Отменено'
                            }" class="px-3 py-1 rounded-full text-xs font-semibold inline-block ml-2">
                                {{order.status}}
                            </span>
                        </div>
                        <div v-if="is_admin" class="text-sm font-sans">
                            Изменить статус:
                            <select v-model="status" name="stats" id="stats" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border">
                                <option value="В процессе">В процессе</option>
                                <option value="Принят в работу">Принят в работу</option>
                                <option value="Готов">Готов</option>
                            </select>
                        </div>
                        <div class="text-sm text-gray-500 font-sans">Телефон: {{order.phone}}</div>
                        <div class="text-sm text-blue-600 hover:text-blue-800 font-sans transition-colors duration-150">
                            Telegram:
                            <a v-if="order.tg" :href="'https://t.me/' + order.tg.substring(1)" target="_blank" class="underline hover:no-underline">
                                {{order.tg}}
                            </a>
                            <span v-else>-</span>
                        </div>
                        <div class="text-sm font-sans">
                            Изображения:
                            <div class="flex flex-wrap gap-3 mt-2">
                                <img
                                    v-for="picture in order.pictures"
                                    :key="picture.id"
                                    :src="'/storage/' + picture.path"
                                    alt="Изображение заказа"
                                    class="w-14 h-14 object-cover rounded-lg border border-gray-200 hover:scale-105 transition-transform duration-200 cursor-pointer shadow-sm"
                                    @click="openImage('/storage/' + picture.path)"
                                >
                            </div>
                        </div>
                        <div v-if="order.complete_time != null" class="text-sm text-gray-500 font-sans">Время сдачи: {{formatDate(order.complete_time)}}</div>
                        <div v-if="order.complete_time == null && !is_admin" class="text-sm text-gray-500 font-sans">Время сдачи: Неизвестно</div>
                        <div v-if="is_admin" class="text-sm font-sans">
                            Установить дату:
                            <input v-model="complete_time" name="date" type="date" id="date" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border" :placeholder="order.complete_time ? formatDate(order.complete_time) : 'Выберите дату'">
                        </div>
                        <div class="text-sm pt-3 font-sans">
                            Действия:
                            <div class="flex items-center gap-3 mt-2">
                                <button @click="deleteOrder(order.id)"
                                        class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors duration-200"
                                        title="Удалить"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <button v-if="is_admin" :disabled="!complete_time || !status" @click="updateOrder(order.id)"
                                        class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition-colors duration-200 disabled:opacity-60 disabled:cursor-not-allowed shadow-sm hover:shadow-md"
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
                        <thead class="bg-gradient-to-r from-blue-600 to-cyan-700">
                        <tr>
                            <th v-if="is_admin" class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Имя пользователя</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Название заявки</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Описание</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Создана</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Статус</th>
                            <th v-if="is_admin" class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Изменить статус</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Телефон</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Telegram</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Изображения</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Время сдачи</th>
                            <th v-if="is_admin" class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Установить дату</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap font-sans">Действия</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                        <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition-colors duration-150">
                            <td v-if="is_admin" class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-sans">{{order.user.name}}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-sans">{{order.name}}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 max-w-xs font-sans">{{order.description}}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-sans">{{formatDate(order.created_at)}}</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span :class="{
                                    'bg-yellow-100 text-yellow-800': order.status === 'В процессе',
                                    'bg-green-100 text-green-800': order.status === 'Готов',
                                    'bg-red-100 text-red-800': order.status === 'Отменено'
                                }" class="px-3 py-1 rounded-full text-xs font-semibold inline-block">
                                    {{order.status}}
                                </span>
                            </td>
                            <td v-if="is_admin" class="px-5 py-4 whitespace-nowrap">
                                <select v-model="status" name="stats" id="stats" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border">
                                    <option value="В процессе">В процессе</option>
                                    <option value="Принят в работу">Принят в работу</option>
                                    <option value="Готов">Готов</option>
                                </select>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-sans">{{order.phone}}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-800 text-center font-sans transition-colors duration-150">
                                <a v-if="order.tg" :href="'https://t.me/' + order.tg.substring(1)" target="_blank" class="underline hover:no-underline">
                                    {{order.tg}}
                                </a>
                                <span v-else>-</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-3 justify-center">
                                    <img
                                        v-for="picture in order.pictures"
                                        :key="picture.id"
                                        :src="'/storage/' + picture.path"
                                        alt="Изображение заказа"
                                        class="w-14 h-14 object-cover rounded-lg border border-gray-200 hover:scale-105 transition-transform duration-200 cursor-pointer shadow-sm"
                                        @click="openImage('/storage/' + picture.path)"
                                    >
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500 text-center font-sans">
                                {{order.complete_time ? formatDate(order.complete_time) : 'Неизвестно'}}
                            </td>
                            <td v-if="is_admin" class="px-5 py-4 whitespace-nowrap">
                                <input v-model="complete_time" name="date" type="date" id="date" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border" :placeholder="order.complete_time ? formatDate(order.complete_time) : 'Выберите дату'">
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-3">
                                    <button @click="deleteOrder(order.id)"
                                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors duration-200"
                                            title="Удалить"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                    <button v-if="is_admin" :disabled="!complete_time || !status" @click="updateOrder(order.id)"
                                            class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition-colors duration-200 disabled:opacity-60 disabled:cursor-not-allowed shadow-sm hover:shadow-md"
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

        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 transition-opacity duration-300" @click.self="showModal = false">
            <div class="bg-white rounded-xl max-w-4xl max-h-[90vh] mx-4 overflow-hidden shadow-2xl transform transition-all duration-300">
                <button @click="showModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img :src="currentImage" class="max-w-full max-h-[80vh] mx-auto p-1" alt="Увеличенное изображение">
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

