<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  paciente: Object
})

const form = useForm({
  nombre: props.paciente.nombre,
  apellido: props.paciente.apellido,
  email: props.paciente.email,
  prefijo: props.paciente.prefijo,
  telefono: props.paciente.telefono
})

function submit() {
  form.put(`/pacientes/${props.paciente.id}`)
}
</script>

<template>
  <AppLayout>
    <div class="max-w-lg mx-auto px-6 py-8 space-y-6">

      <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
        <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
        <span>/</span>
        <Link href="/pacientes" class="hover:text-gray-600">Pacientes</Link>
        <span>/</span>
        <span class="text-gray-600">Editar</span>
      </div>

      <h1 class="text-2xl font-bold">Editar Paciente</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div class="grid gap-2">
          <label class="block mb-1">Nombre</label>
          <input v-model="form.nombre" type="text" class="border px-3 py-2 w-full rounded" />
          <p v-if="form.errors.nombre" class="text-red-600 text-sm">{{ form.errors.nombre }}</p>
        </div>
        <div class="grid gap-2">
          <label class="block mb-1">Apellido</label>
          <input v-model="form.apellido" type="text" class="border px-3 py-2 w-full rounded" />
          <p v-if="form.errors.apellido" class="text-red-600 text-sm">{{ form.errors.apellido }}</p>
        </div>
        <div class="grid gap-2">
          <label class="block mb-1">Email</label>
          <input v-model="form.email" type="email" class="border px-3 py-2 w-full rounded" />
          <p v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</p>
        </div>
        <div class="grid gap-2">
          <label class="block mb-1">Prefijo</label>
          <input v-model="form.prefijo" type="text" class="border px-3 py-2 w-full rounded" />
          <p v-if="form.errors.prefijo" class="text-red-600 text-sm">{{ form.errors.prefijo }}</p>
        </div>
        <div class="grid gap-2">
          <label class="block mb-1">Teléfono</label>
          <input v-model="form.telefono" type="text" class="border px-3 py-2 w-full rounded" />
          <p v-if="form.errors.telefono" class="text-red-600 text-sm">{{ form.errors.telefono }}</p>
        </div>
        <div class="flex gap-4 mt-4">
          <button type="submit" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:opacity-90 disabled:opacity-50" :disabled="form.processing">
            Guardar Cambios
          </button>
          <Link href="/pacientes" class="px-4 py-2 rounded-md border hover:bg-gray-50">Cancelar</Link>
        </div>
      </form>
    </div>
  </AppLayout>
</template>