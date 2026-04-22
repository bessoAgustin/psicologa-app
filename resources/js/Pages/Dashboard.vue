<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps<{
  rol: string
  totalPacientes?: number
  citasHoy?: number
  citasPendientes?: number
  proximasCitas?: any[]
  proximaCita?: any
  totalCitas?: number
}>()
</script>

<template>
  <AppLayout>
    <Head title="Dashboard" />
    <div class="p-6">

      <!-- Admin -->
      <div v-if="rol === 'admin'">
        <h1 class="text-2xl font-bold mb-6">Panel de la psicóloga</h1>
        <div class="grid grid-cols-3 gap-4 mb-8">
          <div class="bg-white rounded-xl border p-4 text-center shadow-sm">
            <p class="text-3xl font-bold text-indigo-600">{{ totalPacientes }}</p>
            <p class="text-gray-500 mt-1">Pacientes</p>
          </div>
          <div class="bg-white rounded-xl border p-4 text-center shadow-sm">
            <p class="text-3xl font-bold text-green-600">{{ citasHoy }}</p>
            <p class="text-gray-500 mt-1">Citas hoy</p>
          </div>
          <div class="bg-white rounded-xl border p-4 text-center shadow-sm">
            <p class="text-3xl font-bold text-yellow-500">{{ citasPendientes }}</p>
            <p class="text-gray-500 mt-1">Pendientes de pago</p>
          </div>
        </div>

        <h2 class="text-lg font-semibold mb-3">Próximas citas confirmadas</h2>
        <div v-if="proximasCitas?.length === 0" class="text-gray-400 italic">No hay citas próximas.</div>
        <div v-else class="space-y-2">
          <div
            v-for="cita in proximasCitas"
            :key="cita.id"
            class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 flex justify-between items-center"
          >
            <span class="font-medium">{{ cita.paciente?.nombre }} {{ cita.paciente?.apellido }}</span>
            <span class="text-gray-500">{{ cita.fecha }} · {{ cita.hora }}</span>
          </div>
        </div>
      </div>

      <!-- Paciente -->
      <div v-else>
        <h1 class="text-2xl font-bold mb-6">Bienvenida</h1>
        <div class="grid grid-cols-2 gap-4 mb-8">
          <div class="bg-white rounded-xl border p-4 text-center shadow-sm">
            <p class="text-3xl font-bold text-green-600">{{ totalCitas }}</p>
            <p class="text-gray-500 mt-1 font-medium">Sesiones realizadas</p>
          </div>
          <div class="bg-white rounded-xl border p-4 text-center shadow-sm">
            <div v-if="proximaCita">
              <p class="text-xl font-bold text-green-600">{{ proximaCita.fecha }}</p>
              <p class="text-gray-500">{{ proximaCita.hora }}</p>
              <p class="text-gray-500 text-sm mt-1 font-medium">Próxima sesión</p>
            </div>
            <div v-else>
              <p class="text-gray-400 italic text-sm mt-2">Sin sesiones próximas</p>
            </div>
          </div>
        </div>
        <a href="/disponibilidad" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-800 mx-auto block w-max font-bold">
          Reservar nueva sesión
        </a>
      </div>

    </div>
  </AppLayout>
</template>