<script setup>
import { ref, onMounted } from 'vue'
import { Network } from '@capacitor/network'
import { Camera, CameraResultType } from '@capacitor/camera'

const API = import.meta.env.VITE_API_URL

const items = ref([])
const estado = ref('Cargando...')
const actualizado = ref(null)

// Variables para Capacitor
const enLinea = ref(true)
const foto = ref(null)

async function cargar() {
  try {
    const res = await fetch(`${API}/productos`)
    if (!res.ok) throw new Error(`HTTP ${res.status}`)
    
    const json = await res.json()
    items.value = json.data ?? json 
    
    actualizado.value = new Date().toLocaleString()
    estado.value = ''
  } catch (e) {
    estado.value = 'No se pudo conectar con la API'
  }
}

async function tomarFoto() {
  try {
    const img = await Camera.getPhoto({
      resultType: CameraResultType.Uri,
      quality: 80
    })
    foto.value = img.webPath
  } catch (error) {
    console.log('Cámara cancelada o error', error)
  }
}

onMounted(async () => {
  cargar() // Cargar los productos
  
  // Lógica de Network
  enLinea.value = (await Network.getStatus()).connected
  Network.addListener('networkStatusChange', (status) => {
    enLinea.value = status.connected
  })
})
</script>

<template>
  <main style="font-family: sans-serif; padding: 20px;">
    <!-- Aviso de conexión -->
    <p v-if="!enLinea" style="background-color: #ffcccc; color: #cc0000; padding: 10px; border-radius: 5px;">
      Sin conexión. Se muestran los últimos datos guardados.
    </p>

    <h1>Mi app - Lista de Productos</h1>
    
    <!-- Botón de cámara y foto -->
    <div style="margin-bottom: 20px;">
      <button @click="tomarFoto" style="padding: 10px; cursor: pointer;">Tomar foto</button>
      <div v-if="foto" style="margin-top: 10px;">
        <img :src="foto" width="240" alt="Foto tomada" style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" />
      </div>
    </div>

    <p v-if="estado" style="color: red;">{{ estado }}</p>
    
    <ul>
      <li v-for="item in items" :key="item.id">
        <strong>{{ item.nombre }}</strong> - S/ {{ item.precio }}
      </li>
    </ul>
    
    <small v-if="actualizado">Última actualización: {{ actualizado }}</small>
  </main>
</template>