<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const hoy = new Date()
const mes = ref(hoy.getMonth())
const year = ref(hoy.getFullYear())

const diasSemana = ['L', 'M', 'X', 'J', 'V', 'S', 'D']

const sesionesMes = ref([])
const diasBloqueados = ref([])

const cargarDatos = async () => {
  const mesStr = `${year.value}-${String(mes.value + 1).padStart(2, '0')}`
  const [sesionesRes, bloqueadosRes] = await Promise.all([
    axios.get(`/sesiones-mes?month=${mesStr}`),
    axios.get(`/dias-bloqueados?month=${mesStr}`)
  ])
  sesionesMes.value = sesionesRes.data
  diasBloqueados.value = bloqueadosRes.data.map(d => d.fecha)
}

onMounted(cargarDatos)

const nombreMes = computed(() =>
  new Date(year.value, mes.value).toLocaleString('es-ES', { month: 'long' })
)

const diasDelMes = computed(() => {
  const fecha = new Date(year.value, mes.value, 1)
  const primerDiaSemana = fecha.getDay() === 0 ? 6 : fecha.getDay() - 1
  const totalDias = new Date(year.value, mes.value + 1, 0).getDate()
  const dias = []

  for (let i = 0; i < primerDiaSemana; i++) dias.push({ vacio: true })

  for (let d = 1; d <= totalDias; d++) {
    const fechaStr = `${year.value}-${String(mes.value + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`
    const dow = new Date(year.value, mes.value, d).getDay()
    const pasado = new Date(year.value, mes.value, d) < new Date(hoy.toDateString())
    dias.push({
      numero: d,
      fecha: fechaStr,
      esDomingo: dow === 0,
      esSabado: dow === 6,
      pasado,
      ocupado: (() => {
        const sesionesDelDia = sesionesMes.value.filter(s => 
          s.fecha === fechaStr && (s.estado === 'confirmada' || s.estado === 'pendiente')
        )
        return sesionesDelDia.length >= 3
      })(),
      bloqueado: diasBloqueados.value.includes(fechaStr)
    })
  }
  return dias
})

const mesAnterior = () => {
  if (mes.value === 0) { mes.value = 11; year.value-- } else mes.value--
  cargarDatos()
}

const mesSiguiente = () => {
  if (mes.value === 11) { mes.value = 0; year.value++ } else mes.value++
  cargarDatos()
}

const diaSeleccionado = ref('')

const emit = defineEmits(['diaSeleccionado'])

const seleccionarDia = (dia) => {
  if (dia.vacio || dia.esDomingo || dia.esSabado || dia.bloqueado || dia.pasado) return
  diaSeleccionado.value = dia.fecha
  emit('diaSeleccionado', dia.fecha)
}
</script>

<template>
  <div class="w-80">
    <div class="flex justify-between items-center mb-4">
      <button @click="mesAnterior" class="p-1 hover:bg-slate-100 rounded-lg text-xl">‹</button>
      <h2 class="font-semibold capitalize">{{ nombreMes }} {{ year }}</h2>
      <button @click="mesSiguiente" class="p-1 hover:bg-slate-100 rounded-lg text-xl">›</button>
    </div>

    <div class="grid grid-cols-7 gap-1 mb-2">
      <div v-for="d in diasSemana" :key="d" class="text-center text-xs font-semibold text-gray-400 py-1">
        {{ d }}
      </div>
    </div>

    <div class="grid grid-cols-7 gap-1">
      <div v-for="(dia, index) in diasDelMes" :key="index"
        class="h-10 flex items-center justify-center rounded-lg text-sm"
        :class="{
          'ring-2 ring-green-300': dia.fecha === diaSeleccionado,
          'cursor-pointer hover:ring-2 hover:ring-blue-300 transition': !dia.vacio && !dia.esDomingo && !dia.esSabado && !dia.bloqueado && !dia.pasado,
          'bg-red-100 text-red-400 cursor-not-allowed': dia.ocupado && !dia.bloqueado && !dia.esDomingo && !dia.esSabado,
          'bg-slate-100 text-gray-300 cursor-not-allowed': dia.vacio || dia.esDomingo || dia.esSabado || dia.bloqueado || dia.pasado,
          'bg-green-100 text-green-800': !dia.ocupado && !dia.bloqueado && !dia.esDomingo && !dia.esSabado && !dia.pasado && !dia.vacio,
        }"
        @click="seleccionarDia(dia)"
      >
        {{ dia.numero }}
      </div>
    </div>

    <div class="flex gap-4 mt-4 text-xs text-gray-500">
      <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-green-100 inline-block"></span> Disponible</span>
      <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-100 inline-block"></span> Ocupado</span>
    </div>
  </div>
</template>