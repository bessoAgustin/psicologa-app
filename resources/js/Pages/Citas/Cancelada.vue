<script setup>
defineProps({
  sesion: Object
})
</script>

<template>
  <div class="max-w-lg mx-auto p-6 text-center">
    <h1 class="text-2xl font-bold mb-4">✅ Cita cancelada</h1>
    <p class="italic text-gray-600 mb-6">Has cancelado tu cita correctamente.</p>

    <div class="bg-green-50 rounded-xl p-4 border border-green-100 mb-6 text-left">
      <p><strong>📅 Fecha:</strong> {{ sesion.fecha }}</p>
      <p><strong>🕐 Hora:</strong> {{ sesion.hora }}</p>
    </div>

    <a href="/disponibilidad" class="bg-green-800 text-white px-6 py-2 rounded-lg block mb-4 hover:bg-green-900 mx-auto w-max">
      Agendar nueva cita
    </a>
    <a href="/mis-citas" class="text-green-700 underline hover:text-green-800">
      Ir a mis citas
    </a>
  </div>
</template>