<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineOptions({
    layout: {
        title: 'Create an account',
        description: 'Enter your details below to create your account',
    },
});
</script>

<template>
    <Head title="Register" />

    <div class="mx-auto max-w-md px-4 py-8">
        <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
    >
        <div class="grid gap-6">
            <div class="grid gap-2">
                <Label for="name">Nombre</Label>
                <Input
                    id="name"
                    type="text"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="name"
                    name="name"
                    placeholder="Nombre"
                />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="apellido">Apellido</Label>
                <Input
                    id="apellido"
                    type="text"
                    required
                    :tabindex="2"
                    name="apellido"
                    placeholder="Apellido"
                />
                <InputError :message="errors.apellido" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    :tabindex="3"
                    autocomplete="email"
                    name="email"
                    placeholder="email@ejemplo.com"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <Label for="prefijo">Prefijo</Label>
                    <Input
                        id="prefijo"
                        type="text"
                        :tabindex="4"
                        name="prefijo"
                        placeholder="+34"
                    />
                </div>
                <div class="col-span-2">
                    <Label for="telefono">Teléfono</Label>
                    <Input
                        id="telefono"
                        type="text"
                        :tabindex="5"
                        name="telefono"
                        placeholder="612345678"
                    />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="password">Contraseña</Label>
                <PasswordInput
                    id="password"
                    required
                    :tabindex="6"
                    autocomplete="new-password"
                    name="password"
                    placeholder="Contraseña"
                />
                <InputError :message="errors.password" />
            </div>

            <div class="grid gap-2">
                <Label for="password_confirmation">Confirmar contraseña</Label>
                <PasswordInput
                    id="password_confirmation"
                    required
                    :tabindex="7"
                    autocomplete="new-password"
                    name="password_confirmation"
                    placeholder="Confirmar contraseña"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <Button
                type="submit"
                class="mt-2 w-full"
                tabindex="8"
                :disabled="processing"
                data-test="register-user-button"
            >
                <Spinner v-if="processing" />
                Crear cuenta
            </Button>
        </div>

        <div class="text-center text-sm text-muted-foreground">
            Ya tienes cuenta?
            <TextLink
                :href="login()"
                class="underline underline-offset-4"
                :tabindex="6"
                >Iniciar sesión</TextLink
            >
        </div>
    </Form>

    </div>
    </template>
