<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  sesiones: Array
})
</script>

<template>
  <AppLayout>
    <div class="max-w-lg mx-auto p-6">

      <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
        <span>/</span>
        <span class="text-gray-600">Mis citas</span>
      </div>

      <h1 class="text-2xl font-bold mb-6">Mis citas</h1>

      <div v-if="sesiones.length === 0" class="text-center py-12">
        <p class="text-gray-500 italic mb-4">Aún no tienes ninguna cita reservada</p>
        <p class="text-gray-500 italic mb-6">¡Reserva tu siguiente sesión!</p>
        <Link href="/disponibilidad" class="bg-blue-600 text-white px-6 py-2 rounded-lg">
          Reservar sesión
        </Link>
      </div>

      <div v-else class="space-y-4">
        <div
          v-for="sesion in sesiones"
          :key="sesion.id"
          class="bg-slate-50 rounded-xl p-4 border border-slate-200 shadow-sm"
        >
          <p><strong>🗓️ Fecha:</strong> <em>{{ sesion.fecha }}</em></p>
          <p><strong>🕐 Hora:</strong> <em>{{ sesion.hora }}</em></p>
          <p><strong>📊 Estado:</strong> <em>{{ sesion.estado }}</em></p>
          <div class="text-center mt-3">
            <Link :href="`/mis-citas/${sesion.id}`" class="text-green-700 underline">
              Ver detalles
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>