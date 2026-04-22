<script setup>
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import CalendarioMensual from '@/components/Agenda/CalendarioMensual.vue'

const fechaSeleccionada = ref('')
const cargando = ref(false)
const horasDisponibles = ref([])
const cargandoHoras = ref(false)

const seleccionarFecha = async (fecha) => {
  fechaSeleccionada.value = fecha
  cargandoHoras.value = true

  const res = await fetch(`/sesiones-dia?fecha=${fecha}`)
  const sesiones = await res.json()

  const todasLasHoras = ['19:00', '20:00', '21:00']
  horasDisponibles.value = todasLasHoras.filter(hora =>
    !sesiones.some(s => s.hora === hora && (s.estado === 'confirmada' || s.estado === 'pendiente'))
  )

  cargandoHoras.value = false
}

const reservar = async (hora) => {
  cargando.value = true

  const token = document.head.querySelector('meta[name="csrf-token"]')?.content
    ?? document.cookie.split('; ').find(r => r.startsWith('XSRF-TOKEN='))?.split('=')[1]

  const response = await fetch('/sesiones', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': decodeURIComponent(token),
    },
    body: JSON.stringify({
      fecha: fechaSeleccionada.value,
      hora: hora,
    })
  })

  const data = await response.json()
  if (data.url) {
    window.location.href = data.url
  } else {
    cargando.value = false
  }
}
</script>

<template>
  <AppLayout>

    <div v-if="cargando" class="fixed inset-0 bg-white/70 flex flex-col items-center justify-center z-50">
      <svg class="animate-spin h-10 w-10 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
      </svg>
      <p class="text-gray-600 font-medium">Redirigiendo al pago...</p>
    </div>

    <div class="p-6">
      <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <Link href="/dashboard" class="hover:text-gray-600">Inicio</Link>
        <span>/</span>
        <span class="text-gray-600">Reservar sesión</span>
      </div>

      <h1 class="text-2xl font-bold mb-6">Reservar sesión</h1>

      <div class="flex gap-8 items-start">
        <CalendarioMensual @diaSeleccionado="seleccionarFecha" />

        <div class="flex-1">
          <div v-if="!fechaSeleccionada" class="text-gray-400 italic mt-16">
            Seleccioná un día para ver los horarios disponibles
          </div>

          <div v-else>
            <h3 class="font-semibold text-lg mb-4">{{ fechaSeleccionada }}</h3>

            <div v-if="cargandoHoras" class="text-gray-400 italic">Cargando horarios...</div>

            <div v-else-if="horasDisponibles.length === 0" class="text-gray-400 italic">
              No hay horarios disponibles para este día.
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="hora in horasDisponibles"
                :key="hora"
                class="flex items-center justify-between px-4 py-3 rounded-xl border bg-green-50 border-green-200 cursor-pointer hover:bg-green-100 transition"
                @click="reservar(hora)"
              >
                <span class="font-medium">{{ hora }}</span>
                <span class="text-sm text-green-600">Disponible — click para reservar</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>