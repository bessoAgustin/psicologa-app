<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import axios from 'axios'

const props = defineProps({ fecha: String })

const sesiones = ref([])
const pacientes = ref([])
const horas = ['19:00', '20:00', '21:00']

const cargarSesiones = async () => {
  const res = await axios.get(`/sesiones-dia?fecha=${props.fecha}`)
  sesiones.value = res.data
}

const cargarPacientes = async () => {
  const res = await axios.get('/pacientes/json')
  pacientes.value = res.data
}

onMounted(() => { cargarSesiones(); cargarPacientes() })
watch(() => props.fecha, cargarSesiones)

const modalAbierto = ref(false)
const horaSeleccionada = ref('')
const pacienteSeleccionado = ref('')
const precio = ref(60)

const abrirModal = (hora) => {
  if (sesiones.value.some(s => s.hora === hora)) return
  horaSeleccionada.value = hora
  modalAbierto.value = true
}

const cerrarModal = () => {
  modalAbierto.value = false
  pacienteSeleccionado.value = ''
}

const crearSesion = async () => {
  await axios.post('/sesiones', {
    paciente_id: pacienteSeleccionado.value,
    fecha: props.fecha,
    hora: horaSeleccionada.value,
    estado: 'pendiente',
    precio: precio.value
  })
  cerrarModal()
  cargarSesiones()
}

const pagarSesion = async (sesionId) => {
  try {
    const res = await axios.post('/pago/crear', { sesion_id: sesionId })
    window.location.href = res.data.url
  } catch (e) {
    alert('Error al iniciar el pago.')
  }
}

const esFinDeSemana = computed(() => {
  const d = new Date(props.fecha).getDay()
  return d === 0 || d === 6
})
</script>

<template>
  <div>
    <h3 class="text-lg font-semibold mb-4">{{ fecha }}</h3>

    <div v-if="esFinDeSemana" class="text-gray-400 italic">
      Este día no está disponible para sesiones.
    </div>

    <div v-else class="space-y-3">
      <div
        v-for="hora in horas"
        :key="hora"
        class="flex items-center justify-between px-4 py-3 rounded-xl border"
        :class="sesiones.some(s => s.hora === hora && s.estado !== 'cancelada')
          ? 'bg-red-50 border-red-200 cursor-not-allowed'
          : 'bg-green-50 border-green-200 cursor-pointer hover:bg-green-100 transition'"
        @click="abrirModal(hora)"
      >
        <span class="font-medium">{{ hora }}</span>
        <div v-if="sesiones.some(s => s.hora === hora && s.estado !== 'cancelada')" class="flex items-center gap-3">
          <span class="text-sm text-gray-500">
            {{ sesiones.find(s => s.hora === hora && s.estado !== 'cancelada')?.paciente?.nombre }}
            {{ sesiones.find(s => s.hora === hora && s.estado !== 'cancelada')?.paciente?.apellido }}
          </span>
          <span class="text-xs px-2 py-0.5 rounded-full"
            :class="sesiones.find(s => s.hora === hora && s.estado !== 'cancelada')?.estado === 'confirmada'
              ? 'bg-green-100 text-green-700'
              : 'bg-yellow-100 text-yellow-700'">
            {{ sesiones.find(s => s.hora === hora && s.estado !== 'cancelada')?.estado }}
          </span>
        </div>
        <span v-else class="text-sm text-green-600">Disponible</span>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="modalAbierto" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl p-6 w-80 shadow-xl space-y-4">
        <h4 class="font-semibold text-lg">Nueva sesión a las {{ horaSeleccionada }}</h4>

        <div>
          <label class="block text-sm text-gray-500 mb-1">Paciente</label>
          <select v-model="pacienteSeleccionado" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option disabled value="">Seleccionar paciente</option>
            <option v-for="p in pacientes" :key="p.id" :value="p.id">
              {{ p.nombre }} {{ p.apellido }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm text-gray-500 mb-1">Precio (€)</label>
          <input type="number" v-model="precio" class="w-full border rounded-lg px-3 py-2 text-sm" />
        </div>

        <div class="flex gap-3 pt-2">
          <button @click="crearSesion" class="flex-1 bg-blue-600 text-white rounded-lg py-2 text-sm hover:bg-blue-700">
            Guardar
          </button>
          <button @click="cerrarModal" class="flex-1 border rounded-lg py-2 text-sm hover:bg-slate-50">
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>