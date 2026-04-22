<script setup>
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import CalendarioMensual from '@/components/Agenda/CalendarioMensual.vue'
import AgendaDia from '@/components/Agenda/AgendaDia.vue'

const fechaSeleccionada = ref('')
</script>

<template>
  <AppLayout>
    <div class="p-6">
      <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
        <span>/</span>
        <span class="text-gray-600">Agenda</span>
      </div>

      <h1 class="text-2xl font-bold mb-6">Agenda</h1>

      <div class="flex gap-8 items-start">
        <CalendarioMensual @diaSeleccionado="fechaSeleccionada = $event" />
        <div class="flex-1" v-if="fechaSeleccionada">
          <AgendaDia :fecha="fechaSeleccionada" />
        </div>
        <div v-else class="flex-1 flex items-center justify-center text-gray-400 italic mt-16">
          Seleccioná un día para ver la agenda
        </div>
      </div>
    </div>
  </AppLayout>
</template>