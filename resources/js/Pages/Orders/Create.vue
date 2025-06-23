<template>
    <AuthenticatedLayout>
        <form @submit.prevent="storeOrder" enctype="multipart/form-data" class="content">
            <div class="bg-white border-2 border-red-100 rounded-lg shadow-lg relative m-10">
                <!-- Заголовок с красным акцентом -->
                <div class="flex items-start justify-between p-5 border-b border-red-50 rounded-t bg-gradient-to-r from-white to-red-50">
                    <h3 class="text-xl font-semibold text-red-800">
                        Создать заявку
                    </h3>
                    <button type="button"
                            class="text-red-400 hover:text-red-600 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Левая колонка -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="product-name" class="text-sm text-red-700 block mb-2 font-bold">
                                Название заказа
                                <span class="text-red-500">*</span>
                            </label>
                            <input v-model="name" type="text" id="product-name"
                                   class="shadow-sm bg-red-50 border border-red-200 text-red-900 sm:text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 placeholder-red-300"
                                   placeholder="Ремонт iPhone 12 Pro">

                            <label for="category" class="text-sm my-2.5 text-red-700 block mb-2 font-bold">
                                Описание
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea v-model="description" id="category"
                                      class="shadow-sm bg-red-50 border border-red-200 text-red-900 sm:text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 placeholder-red-300 h-32"
                                      placeholder="Экран не включается после падения..."></textarea>
                        </div>

                        <!-- Правая колонка -->
                        <div class="col-span-6 sm:col-span-3">
                            <div class="space-y-4">
                                <!-- Блок телефона -->
                                <div>
                                    <label for="phone" class="text-sm font-bold text-red-700 block mb-2">
                                        Номер телефона
                                        <span class="text-gray-500 text-xs">(формат: 111-111-1111)</span>
                                    </label>
                                    <div class="relative">
                                        <input v-model="phone" type="tel" id="phone"
                                               class="shadow-sm bg-red-50 border border-red-200 text-red-900 sm:text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 placeholder-red-300"
                                               placeholder="123-456-7890">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Разделитель -->
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-red-200"></div>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <span class="px-2 bg-white text-sm text-red-500">ИЛИ</span>
                                    </div>
                                </div>

                                <!-- Блок Telegram -->
                                <div>
                                    <label for="tg" class="text-sm font-bold text-red-700 block mb-2">
                                        Ваш телеграмм-аккаунт
                                        <span class="text-gray-500 text-xs">(формат: @username)</span>
                                    </label>
                                    <div class="relative">
                                        <input v-model="tg" type="text" id="tg"
                                               class="shadow-sm bg-red-50 border border-red-200 text-red-900 sm:text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 placeholder-red-300"
                                               placeholder="@pavelDurov">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l16.5-6.33c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Загрузка файлов -->
                                <div class="mt-6">
                                    <label class="block text-sm font-bold text-red-700 mb-2">
                                        Изображения
                                        <span class="text-gray-500 text-xs">(макс. 5 файлов)</span>
                                    </label>
                                    <div class="flex items-center justify-center w-full">
                                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-red-300 border-dashed rounded-lg cursor-pointer bg-red-50 hover:bg-red-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-red-500"><span class="font-semibold">Нажмите для загрузки</span></p>
                                                <p class="text-xs text-red-400">PNG, JPG (макс. 2MB)</p>
                                            </div>
                                            <input
                                                ref="fileInput"
                                                @change="handleFileUpload"
                                                name="pictures[]"
                                                multiple
                                                type="file"
                                                class="hidden"
                                            >
                                        </label>
                                    </div>

                                    <!-- Превью изображений -->
                                    <div class="flex flex-wrap gap-3 mt-4" v-if="pictures">
                                        <div v-for="(picture, index) in pictures" :key="index" class="relative group">
                                            <img :src="picture.url" class="w-24 h-24 object-cover rounded-md border-2 border-red-200 shadow-sm">
                                            <button @click="deletePicture(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md hover:bg-red-600">
                                                &times;
                                            </button>
                                            <span class="absolute bottom-0 left-0 right-0 bg-red-500/90 text-white text-xs p-1 truncate">
                    {{ picture.name }}
                  </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Футер формы -->
                <div class="p-6 border-t border-red-100 rounded-b bg-red-50">
                    <button
                        class="w-full text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-4 focus:ring-red-200 font-medium rounded-lg text-sm px-5 py-3 text-center shadow-md transition-all"
                        type="submit">
                        Отправить заявку
                    </button>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import {router, usePage} from '@inertiajs/vue3'

const page = usePage()

export default {
    components: {
        AuthenticatedLayout,
        Head
    },
    data() {
        return {
            name: '',
            description: '',
            pictures: [],
            files: [],
            phone: '',
            tg: '',
        }
    },
    methods: {
        handleFileUpload(e) {
            const newFiles = Array.from(e.target.files).map(file => ({
                file,
                url: URL.createObjectURL(file)
            }))
            this.pictures = [...this.pictures, ...newFiles]
        },
        deletePicture(index) {
            URL.revokeObjectURL(this.pictures[index].url)
            this.pictures.splice(index, 1)
            this.$refs.fileInput.value = '';
        },
        beforeUnmount() {
            this.pictures.forEach(p => URL.revokeObjectURL(p.url));
        },
        storeOrder() {
            if (!this.validateContacts()){
                alert("Вы не указали хотя бы один из способов контакта с вами");
                return
            }
            const formData = new FormData();
            formData.append('name', this.name);
            formData.append('description', this.description);
            formData.append('phone', this.phone);
            formData.append('tg', this.tg);
            this.pictures.forEach((value, index) => {
                formData.append(`pictures[${index}]`, value.file);
            })
            this.$inertia.post('/store', formData, {
                onSuccess: () => {
                    this.resetForm();
                }
            })
        },
        validateContacts(){
            return this.phone || this.tg;
        },
        resetForm() {
            this.name = '';
            this.description = '';
            this.pictures = [];
        }
    }
}
</script>
