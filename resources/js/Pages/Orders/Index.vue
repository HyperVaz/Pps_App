<template>
    <AuthenticatedLayout>
        <Head title="Заявки"/>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Заявки</h2>
        </template>
        <div>
            <table class="w-full border-collapse border border-blue-500 max-w-5xl mt-16 mx-auto">
                <thead>
                <tr class="bg-blue-500 text-black">
                    <th class="py-2 px-4 text-center w-9">Название заявки</th>
                    <th class="py-2 px-4 text-center w-20">Описание</th>
                    <th class="py-2 px-4 text-center w-15">Создана</th>
                    <th class="py-2 px-4 text-center w-9">Статус</th>
                    <th class="py-2 px-4 text-center w-30">Изображение</th>
                </tr>
                </thead>
                <tbody v-for="order in orders">
                <tr class="bg-white border-b border-blue-500">
                    <td class="py-2 px-4">{{order.name}}</td>
                    <td class="py-2 px-4">{{order.description}}</td>
                    <td class="py-2 px-4">{{order.created_at}}</td>
                    <td class="py-2 px-4">{{order.status}}</td>
                    <td class="py-2 px-4">
                        <img
                            v-for="picture in order.pictures"
                            :key="picture.id"
                            :src="'/storage/' + picture.path"
                            alt="Изображение заказа"
                            class="w-full"
                        >
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";

export default {
    props: [
        'orders',
        'pictures'
    ],
    components: {
        AuthenticatedLayout,
        Head
    }
}
</script>
