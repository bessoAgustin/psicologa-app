<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import pacientes from '@/routes/pacientes';
import AppLayout from '@/layouts/AppLayout.vue'

const form = useForm({
    nombre: '',
    apellido: '',
    email: '',
    prefijo: '',
    telefono: '',
});

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Nuevo paciente',
                href: pacientes.create(),
            },
        ],
    },
}); 

function submit() {
    form.post(pacientes.store().url, {
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <appLayout>
        <Head title="Nuevo paciente" />

        <div class="max-w-lg mx-auto px-6 py-8 space-y-6">
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
                <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
                <span>/</span>
                <Link href="/pacientes" class="hover:text-gray-600">Pacientes</Link>
                <span>/</span>
                <span class="text-gray-600">Nuevo paciente</span>
            </div>
            <Heading
                variant="small"
                title="Nuevo paciente"
                description="Registra un nuevo paciente en el sistema"
            />

            <form @submit.prevent="submit" class="space-y-6 max-w-xl">
                <!-- Nombre -->
                <div class="grid gap-2">
                    <Label for="nombre">Nombre</Label>
                    <Input
                        id="nombre"
                        v-model="form.nombre"
                        type="text"
                        placeholder="Nombre"
                    />
                    <p v-if="form.errors.nombre" class="text-red-600 text-sm">
                        {{ form.errors.nombre }}
                    </p>
                </div>

                <!-- Apellido -->
                <div class="grid gap-2">
                    <Label for="apellido">Apellido</Label>
                    <Input
                        id="apellido"
                        v-model="form.apellido"
                        type="text"
                        placeholder="Apellido"
                    />
                    <p v-if="form.errors.apellido" class="text-red-600 text-sm">
                        {{ form.errors.apellido }}
                    </p>
                </div>

                <!-- Email -->
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="correo@ejemplo.com"
                    />
                    <p v-if="form.errors.email" class="text-red-600 text-sm">
                        {{ form.errors.email }}
                    </p>
                </div>

                <!-- Prefijo -->
                <div class="grid gap-2">
                    <Label for="prefijo">Prefijo</Label>
                    <Input
                        id="prefijo"
                        v-model="form.prefijo"
                        type="text"
                        placeholder="+34"
                    />
                    <p v-if="form.errors.prefijo" class="text-red-600 text-sm">
                        {{ form.errors.prefijo }}
                    </p>
                </div>

                <!-- Teléfono -->
                <div class="grid gap-2">
                    <Label for="telefono">Teléfono</Label>
                    <Input
                        id="telefono"
                        v-model="form.telefono"
                        type="text"
                        placeholder="612345678"
                    />
                    <p v-if="form.errors.telefono" class="text-red-600 text-sm">
                        {{ form.errors.telefono }}
                    </p>
                </div>

                <!-- Botones -->
                <div class="flex items-center gap-4">
                    <Button type="submit" :disabled="form.processing">
                        Guardar
                    </Button>

                    <Link :href="pacientes.index()">
                        <Button variant="outline">Cancelar</Button>
                    </Link>
                </div>
            </form>
        </div>
    </appLayout>
</template>
