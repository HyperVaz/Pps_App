<template>
    <AuthenticatedLayout>
        <form @submit.prevent="storeOrder" enctype="multipart/form-data" class="content">
            <div class="bg-white border border-4 rounded-lg shadow relative m-10">
                <div class="flex items-start justify-between p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold">
                        Создать заявку
                    </h3>
                    <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            data-modal-toggle="product-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div>

                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="product-name" class="text-sm font-medium text-gray-900 block mb-2">Название
                                    заказа</label>
                                <input v-model="name" type="text" name="product-name" id="product-name"
                                       class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                       placeholder="Впишите краткое название заказа" required="">
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="category"
                                       class="text-sm font-medium text-gray-900 block mb-2">Описание</label>
                                <textarea v-model="description" type="text" name="category" id="category"
                                          class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                          placeholder="Опишите кратко необходимый ремонт" required></textarea>
                                <input
                                    ref="fileInput"
                                    @change="handleFileUpload"
                                    name="pictures[]"
                                    multiple type="file"
                                    placeholder="Загрузите изображение"
                                    class="my-4">

                                <div class="flex flex-wrap" v-if="pictures">
                                    <div class="mx-2 my-1 relative" v-for="(picture, index) in pictures" :key="index">
                                        <p>{{ picture.name }}</p>
                                        <img :src="picture.url" alt="" class="w-30">
                                        <button class="text-red-600 font-bold top-1 right-3 text-5xl absolute" @click="deletePicture(index)">x</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 rounded-b">
                    <button
                        class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                        type="submit">Отправить
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
        }
    },
    methods: {
        handleFileUpload(e){
            const newFiles = Array.from(e.target.files).map(file =>({
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
            const formData = new FormData();
            formData.append('name', this.name);
            formData.append('description', this.description);
            this.pictures.forEach((value, index) => {
                formData.append(`pictures[${index}]` ,value.file);
            })
            this.$inertia.post('/store', formData, {
                onSuccess: ()=>{
                    this.resetForm();
                }
            })
        },
        resetForm(){
            this.name = '';
            this.description = '';
            this.pictures = [];
        }
    }
}
</script>
