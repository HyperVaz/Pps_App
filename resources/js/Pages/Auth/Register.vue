<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    privacy_agreed: false,
});

const showPrivacyModal = ref(false);
const privacyText = ref(`
<h2 class="text-xl font-bold mb-4">Политика конфиденциальности</h2>

<p><strong>Последнее обновление: ${new Date().toLocaleDateString()}</strong></p>

<h3 class="font-semibold mt-4">1. Сбор и использование данных</h3>
<p>Сервис собирает минимально необходимые данные для предоставления функционала:</p>
<ul class="list-disc pl-5">
    <li>Имя или псевдоним (рекомендуем не использовать реальное имя)</li>
    <li>Адрес электронной почты</li>
    <li>Хэшированный пароль</li>
</ul>

<h3 class="font-semibold mt-4">2. Важные предупреждения</h3>
<p class="text-red-600 font-bold">СЕРВИС НЕ ГАРАНТИРУЕТ АБСОЛЮТНУЮ БЕЗОПАСНОСТЬ ВАШИХ ДАННЫХ</p>
<p class="text-red-600">• Настоятельно рекомендуем не указывать реальное имя, адрес или иную личную информацию</p>
<p class="text-red-600">• Мы не заинтересованы в сборе и анализе ваших персональных данных</p>
<p class="text-red-600">• Данные могут быть утеряны в результате сбоев или внешних атак</p>

<h3 class="font-semibold mt-4">3. Хранение данных</h3>
<p>Все данные хранятся на защищенных серверах, однако:</p>
<ul class="list-disc pl-5">
    <li>Мы не используем шифрование данных end-to-end</li>
    <li>Рекомендуем использовать уникальный пароль, не применяемый на других сервисах</li>
</ul>

<h3 class="font-semibold mt-4">4. Права пользователей</h3>
<p>Вы можете в любой момент:</p>
<ul class="list-disc pl-5">
    <li>Запросить удаление всех ваших данных через раздел настроек</li>
    <li>Отозвать согласие на обработку данных</li>
    <li>Запросить выгрузку ваших данных в машиночитаемом формате</li>
</ul>

<p class="mt-4 text-sm text-gray-500">Полная версия политики доступна по <a href="/privacy-policy" class="underline">ссылке</a></p>
`);

const submit = () => {
    if (!form.privacy_agreed) {
        showPrivacyModal.value = true;
        return;
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const acceptPrivacy = () => {
    form.privacy_agreed = true;
    showPrivacyModal.value = false;
};

onMounted(() => {
    showPrivacyModal.value = true;
});
</script>

<template>
    <GuestLayout>
        <Head title="Регистрация" />

        <!-- Модальное окно политики -->
        <div v-if="showPrivacyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
                <div class="p-6 overflow-y-auto max-h-[80vh]">
                    <div v-html="privacyText"></div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            v-model="form.privacy_agreed"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2"
                            required
                        />
                        <span class="text-sm">Я принимаю условия политики конфиденциальности</span>
                    </label>
                    <button
                        @click="acceptPrivacy"
                        :disabled="!form.privacy_agreed"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md disabled:opacity-50"
                    >
                        Принять и продолжить
                    </button>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <InputLabel for="name" value="Имя (рекомендуем псевдоним)" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Не используйте реальное имя"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mb-4">
                <InputLabel for="email" value="Электронная почта" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mb-4">
                <InputLabel for="password" value="Пароль" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <p class="text-xs text-gray-500 mt-1">Используйте сложный уникальный пароль</p>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mb-4">
                <InputLabel for="password_confirmation" value="Подтверждение пароля" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                    Уже есть аккаунт?
                </Link>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing || !form.privacy_agreed"
                >
                    Зарегистрироваться
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
