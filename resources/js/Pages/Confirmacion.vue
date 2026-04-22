<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen text-center">
    <h1 class="text-2xl font-bold mb-4">Reserva confirmada ✅</h1>
    <p class="mb-6">Tu sesión ha sido reservada correctamente.</p>
    <div class="flex gap-4">
      <Link href="/mis-citas" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Ver mis citas
      </Link>
      <Link href="/dashboard" class="bg-slate-300 text-gray-800 px-4 py-2 rounded hover:bg-slate-400">
        Ir al inicio
      </Link>
    </div>
  </div>
</template>