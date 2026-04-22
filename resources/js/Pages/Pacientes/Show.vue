<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import pacientes from '@/routes/pacientes';

const props = defineProps<{
    paciente: {
        id: number;
        nombre: string;
        apellido: string;
        email: string;
        prefijo: string;
        telefono: string;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Pacientes',
                href: pacientes.index(),
            },
            {
                title: 'Detalle del paciente',
                href: '#',
            },
        ],
    },
});

function destroy() {
    if (confirm('¿Seguro que deseas eliminar este paciente?')) {
        router.delete(pacientes.destroy(props.paciente.id).url);
    }
}
</script>

<template>
    <Head :title="`Paciente: ${props.paciente.nombre}`" />

    <div class="max-w-lg mx-auto px-6 py-8 space-y-6">
        <Heading
            variant="small"
            :title="props.paciente.nombre"
            description="Información detallada del paciente"
        />

        <div class="rounded-xl border p-6 space-y-4">
            <div>
                <p class="text-sm text-muted-foreground">Nombre</p>
                <p class="text-lg font-medium">{{ props.paciente.nombre }}</p>
            </div>

            <div>
                <p class="text-sm text-muted-foreground">Apellido</p>
                <p class="text-lg font-medium">{{ props.paciente.apellido }}</p>
            </div>

            <div>
                <p class="text-sm text-muted-foreground">Email</p>
                <p class="text-lg font-medium">{{ props.paciente.email }}</p>
            </div>

            <div>
                <p class="text-sm text-muted-foreground">Teléfono</p>
                <p class="text-lg font-medium">
                    {{ props.paciente.prefijo }} {{ props.paciente.telefono }}
                </p>
            </div>
        </div>

        <div class="flex gap-4">
            <Link :href="pacientes.edit(props.paciente.id).url">
                <Button>Editar</Button>
            </Link>

            <Button variant="destructive" @click="destroy">
                Eliminar
            </Button>

            <Link :href="pacientes.index()">
                <Button variant="outline">Volver</Button>
            </Link>
        </div>
    </div>
</template>
