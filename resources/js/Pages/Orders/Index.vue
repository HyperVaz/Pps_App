<template>
    <AuthenticatedLayout>
        <Head title="Заявки"/>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Заявки</h2>
        </template>
        <div>
            <table class="w-full border-collapse border border-blue-500 max-w-xl mt-16 mx-auto">
                <thead>
                <tr class="bg-blue-500 text-black">
                    <th class="py-2 px-4 text-left">Название заявки</th>
                    <th class="py-2 px-4 text-left">Описание</th>
                    <th class="py-2 px-4 text-left">Создана</th>
                    <th class="py-2 px-4 text-left">Статус</th>
                </tr>
                </thead>
                <tbody v-for="order in orders">
                <tr class="bg-white border-b border-blue-500">
                    <td class="py-2 px-4">{{order.name}}</td>
                    <td class="py-2 px-4">{{order.description}}</td>
                    <td class="py-2 px-4">{{order.order_created_at}}</td>
                    <td class="py-2 px-4">{{order.order_status}}</td>
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
        'orders'
    ],
    components: {
        AuthenticatedLayout,
        Head
    }
}
</script>
