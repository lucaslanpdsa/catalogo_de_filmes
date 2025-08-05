<template>
  <nav class="bg-gray-900 text-gray-300 px-5 py-5 flex items-center justify-between">
    <div class="flex items-center gap-3 text-orange-500 font-bold text-2xl select-none">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-10 h-10">
        <path
          d="M2 7h20v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7zM1 5v2h22V5a1 1 0 0 0-1-1h-3l-2 3-2-3H7L5 7 3 4H2a1 1 0 0 0-1 1z"
        />
      </svg>
      <span class="text-white drop-shadow-md">Meus Favoritos</span>
    </div>

    <div class="flex-1 max-w-lg mx-6">
      <input
        type="search"
        v-model="searchQuery"
        placeholder="Pesquisar favoritos..."
        class="w-full rounded-md px-4 py-2 bg-gray-800 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
      />
    </div>

    <button
      @click="goHome"
      class="flex items-center gap-2 px-4 py-2 rounded-md border border-gray-400 text-white transition-all duration-200 hover:border-orange-500 hover:shadow-[0_0_10px_4px_rgba(255,115,0,0.6)] hover:scale-105"
      aria-label="Voltar para a lista de filmes"
    >
      Voltar
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
        class="w-5 h-5"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
  </nav>

  <div v-if="filteredFavorites.length" class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-4 px-2">
    <div
      v-for="fav in filteredFavorites"
      :key="fav.id"
      class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-orange-500/50 relative flex flex-col items-center"
    >
      <img
        v-if="fav.poster_path"
        :src="`https://image.tmdb.org/t/p/w300${fav.poster_path}`"
        :alt="fav.title"
        class="w-full h-[400px] object-cover rounded-t-xl"
      />
      <button
        @click="removeFavorite(fav.id)"
        class="absolute top-3 right-3 p-2 rounded-full transition-colors bg-gray-900 bg-opacity-50 backdrop-blur-sm text-orange-500"
        aria-label="Remover dos favoritos"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="currentColor"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
          class="w-6 h-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
          />
        </svg>
      </button>
      <div class="p-4 w-full text-center">
        <h2 class="text-white font-semibold text-base mb-1 line-clamp-2">{{ fav.title }}</h2>
        <div class="flex justify-center gap-4 text-beige text-sm">
          <span>{{ fav.release_date ? new Date(fav.release_date).getFullYear() : '—' }}</span>
          <span>⭐ {{ fav.vote_average?.toFixed(1) ?? '—' }}</span>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="text-center text-gray-400 mt-6">
    Nenhum favorito encontrado.
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const favorites = ref([])
const searchQuery = ref('')

const loadFavorites = async () => {
  try {
    const response = await axios.get('/api/favorites')
    favorites.value = response.data
  } catch (error) {
    console.error('Erro ao carregar favoritos:', error)
  }
}

const removeFavorite = async (id) => {
  try {
    await axios.delete(`/api/favorites/${id}`)
    favorites.value = favorites.value.filter(fav => fav.id !== id)
  } catch (error) {
    console.error('Erro ao remover favorito:', error)
  }
}

const goHome = () => {
  window.location.href = '/search'
}

const filteredFavorites = computed(() => {
  if (!searchQuery.value.trim()) return favorites.value
  return favorites.value.filter(fav =>
    fav.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

onMounted(() => {
  loadFavorites()
})
</script>