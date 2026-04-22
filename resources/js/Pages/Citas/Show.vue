<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  sesion: Object
})

function cancelar() {
  if (confirm('¿Seguro que deseas cancelar esta cita?')) {
    router.post(`/mis-citas/${props.sesion.id}/cancelar`)
  }
}
</script>

<template>
  <AppLayout>
    <div class="p-6 max-w-lg mx-auto">

      <!-- Breadcrumbs + volver -->
      <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
        <span>/</span>
        <Link href="/mis-citas" class="hover:text-gray-600">Mis citas</Link>
        <span>/</span>
        <span class="text-gray-600">Detalle</span>
      </div>

      <h1 class="text-2xl font-bold mb-6">Detalle de la cita</h1>

      <div class="bg-slate-50 rounded-xl p-6 border border-slate-200 space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="font-bold">📅 Fecha</p>
            <p class="italic">{{ sesion.fecha }}</p>
          </div>
          <div>
            <p class="font-bold">🕐 Hora</p>
            <p class="italic">{{ sesion.hora }}</p>
          </div>
          <div>
            <p class="font-bold">📊 Estado</p>
            <p class="italic">{{ sesion.estado }}</p>
          </div>
          <div>
            <p class="font-bold">⏱ Duración</p>
            <p class="italic">1hr</p>
          </div>
        </div>

        <div class="text-center pt-4" v-if="sesion.estado !== 'cancelada'">
          <button @click="cancelar" class="text-red-500 underline">
            Cancelar cita
          </button>
        </div>
      </div>

    </div>
  </AppLayout>
</template>