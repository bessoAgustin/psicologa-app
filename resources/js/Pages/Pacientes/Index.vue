<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import pacientes from '@/routes/pacientes';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    pacientes: Object,
    filters: Object,
});

defineOptions({
    layout: {
        'breadcrumbs' : [
    ['title' , 'Inicio', 'href' , '/'],
    ['title' , 'Pacientes', 'href' , '/pacientes'],
    ],
    },
});

const search = ref(props.filters?.search ?? '');

watch(search, (value) => {
    router.get(
        pacientes.index(),
        { search: value },
        {
            preserveState: true,
            replace: true,
        }
    );
});

function destroy(id: number) {
    if (confirm('¿Seguro que deseas eliminar este paciente?')) {
        router.delete(pacientes.destroy(id));
    }
}
</script>

<template>
    <appLayout>
        <Head title="Pacientes" />

        <div class="max-w-5xl mx-auto px-6 py-7 space-y-6">
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
                <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
                <span>/</span>
                <span class="text-gray-600">Pacientes</span>
            </div>
            <Heading
                variant="small"
                title="Pacientes"
                description="Gestiona tus pacientes registrados"
            />

            <!-- Buscador + botón -->
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <Label for="search" class="py-3">Buscar</Label>
                    <Input
                        id="search"
                        v-model="search"
                        placeholder="Buscar por nombre, apellido o email"
                    />
                </div>

                <Link :href="pacientes.create()">
                    <Button>Nuevo paciente</Button>
                </Link>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border shadow-sm dark:shadow-slate-900/50">
                <table class="w-full text-left">
                    <thead class="bg-muted/40">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Apellido</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Teléfono</th>
                            <th class="px-4 py-3 w-32">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="paciente in props.pacientes?.data ?? []"
                            :key="paciente.id"
                            class="border-t border-sidebar-border/40"
                        >
                            <td class="px-4 py-3">{{ paciente.nombre }}</td>
                            <td class="px-4 py-3">{{ paciente.apellido }}</td>
                            <td class="px-4 py-3">{{ paciente.email }}</td>
                            <td class="px-4 py-3">
                                {{ paciente.prefijo }} {{ paciente.telefono }}
                            </td>

                            <td class="px-4 py-3 flex gap-2">
                                <Link :href="pacientes.edit(paciente.id)">
                                    <Button variant="outline" size="sm">Editar</Button>
                                </Link>

                                <Button
                                    variant="destructive"
                                    size="sm"
                                    @click="destroy(paciente.id)"
                                >
                                    Eliminar
                                </Button>
                            </td>
                        </tr>

                        <tr v-if="(props.pacientes?.data ?? []).length === 0">
                            <td colspan="5" class="px-4 py-6 text-center text-muted-foreground">
                                No hay pacientes registrados.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="flex gap-2 mt-4 justify-center">
                <button
                    v-for="link in props.pacientes?.links ?? []"
                    :key="link.url"
                    :disabled="!link.url"
                    @click="router.visit(link.url)"
                    class="px-3 py-1 border rounded"
                    :class="{ 'bg-slate-200': link.active }"
                    v-html="link.label"
                />
            </div>
        </div>
    </appLayout>
</template>
