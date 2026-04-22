<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  sesiones: Array
})
</script>

<template>
  <AppLayout>
    <div class="p-6">
      <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
        <span>/</span>
        <span class="text-gray-600">Panel de citas</span>
      </div>
      <h1 class="text-2xl font-bold mb-6">Panel de citas</h1>

      <table class="min-w-full border">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-4 py-2 text-left">Paciente</th>
            <th class="border px-4 py-2 text-left">Fecha</th>
            <th class="border px-4 py-2 text-left">Hora</th>
            <th class="border px-4 py-2 text-left">Estado</th>
            <th class="border px-4 py-2 text-left">Precio</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sesion in sesiones" :key="sesion.id">
            <td class="border px-4 py-2">
              {{ sesion.paciente?.nombre }} {{ sesion.paciente?.apellido }}
            </td>
            <td class="border px-4 py-2">{{ sesion.fecha }}</td>
            <td class="border px-4 py-2">{{ sesion.hora }}</td>
            <td class="border px-4 py-2">
              <span
                :class="{
                  'text-green-600 font-semibold': sesion.estado === 'confirmada',
                  'text-yellow-600': sesion.estado === 'pendiente',
                  'text-red-500': sesion.estado === 'cancelada',
                }"
              >
                {{ sesion.estado }}
              </span>
            </td>
            <td class="border px-4 py-2">{{ sesion.precio }}€</td>
          </tr>
          <tr v-if="sesiones.length === 0">
            <td colspan="5" class="text-center py-4 text-gray-500">
              No hay citas registradas
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>