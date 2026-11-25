<script setup>
import { ref } from 'vue'
const API = import.meta.env.VITE_API_URL
const email = ref('ana@test.pe')
const password = ref('secreto123')
const pedidos = ref([])
// CORREGIDO (Paso 6): Token solo en memoria (no en localStorage)
const token = ref('') 

async function entrar() {
  const res = await fetch(`${API}/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify({ email: email.value, password: password.value })
  })
  const json = await res.json()
  token.value = json.token // Guardado en memoria
  cargar()
}

async function cargar() {
  const res = await fetch(`${API}/pedidos`, { 
    headers: { 
      Accept: 'application/json',
      Authorization: `Bearer ${token.value}` // Usa la variable en memoria
    } 
  })
  pedidos.value = await res.json()
}

async function salir() {
  // CORREGIDO (Paso 6): Revocar en el servidor
  await fetch(`${API}/logout`, {
    method: 'POST',
    headers: { Accept: 'application/json', Authorization: `Bearer ${token.value}` }
  })
  
  token.value = '' // Limpiar memoria
  pedidos.value = []
  
  // CORREGIDO (Paso 6): Borrar la caché de la PWA
  if ('caches' in window) {
    await caches.delete('api-cache')
  }
}
</script>

<template>
  <section style="padding: 20px;">
    <input v-model="email" /> <input v-model="password" type="password" />
    <button @click="entrar">Entrar</button> <button @click="salir">Salir</button>
    <ul>
      <!-- CORREGIDO (Paso 5): Usar llaves dobles para evitar XSS -->
      <li v-for="p in pedidos" :key="p.id">{{ p.descripcion }}</li> 
    </ul>
  </section>
</template>