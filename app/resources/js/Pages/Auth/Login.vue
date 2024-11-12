<template>
    <GuestLayout>

        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <form @submit.prevent="submit">
            <div class="flex flex-col gap-2">
                <label for="username">Username</label>
                <InputText id="username" v-model="form.user_name" aria-describedby="username-help" required />
                <InputError :message="form.errors.user_name" />
            </div>

            <div class="flex flex-col gap-2 pt-4">
                <label for="password">Password</label>
                <InputText id="password" type="password" v-model="form.password" aria-describedby="password-help" required />
                <InputError :message="form.errors.password" />
            </div>
            <div class="mt-4 flex items-center justify-end">
                <Button label="Login" type="submit" severity="success" class="w-full"/>
            </div>
        </form>
    </GuestLayout>
</template>
<script setup lang="ts">
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    user_name: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>
