<template>
  <nav class="bg-gray-900 text-gray-300 px-5 py-5 flex items-center justify-between">
    <div class="flex items-center gap-3 text-orange-500 font-bold text-2xl select-none">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-10 h-10">
        <path
          d="M2 7h20v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7zM1 5v2h22V5a1 1 0 0 0-1-1h-3l-2 3-2-3H7L5 7 3 4H2a1 1 0 0 0-1 1z"
        />
      </svg>
      <span class="text-white drop-shadow-md">Catálogo de filmes</span>
    </div>

    <div class="flex-1 max-w-lg mx-6 flex gap-2">
      <input
        type="search"
        v-model="searchQuery"
        placeholder="Buscar filmes..."
        class="flex-1 rounded-md px-4 py-2 bg-gray-800 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
        @keyup.enter="handleSearch"
      />
      <select
        v-model="selectedGenreId"
        class="rounded-md px-4 py-2 bg-gray-800 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
      >
        <option value="">Todos os gêneros</option>
        <option v-for="genre in genres" :key="genre.id" :value="genre.id">{{ genre.name }}</option>
      </select>
    </div>

    <div class="flex items-center space-x-4">
      <button
        @click="goToFavorites"
        class="flex items-center gap-2 px-4 py-2 rounded-md border border-gray-400 text-white transition-all duration-200 hover:border-orange-500 hover:shadow-[0_0_10px_4px_rgba(255,115,0,0.6)] hover:scale-105"
        aria-label="Ir para favoritos"
      >
        Favoritos
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
          class="w-5 h-5"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"
          />
        </svg>
      </button>
    </div>
  </nav>

  <div class="mt-4 px-4 flex gap-4 text-sm text-gray-300 select-none justify-center">
    <button
      v-for="tab in tabs"
      :key="tab.key"
      @click="activeTab = tab.key"
      :class="[
        'px-4 py-2 rounded-md cursor-pointer',
        activeTab === tab.key ? 'bg-orange-600 text-white shadow-md' : 'hover:bg-gray-700 hover:text-orange-500 transition',
      ]"
    >
      {{ tab.label }}
    </button>
  </div>

  <div v-if="filteredMovies.length" class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6 px-2">
    <div
      v-for="movie in filteredMovies"
      :key="movie.id"
      class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden shadow-lg transform transition-transform duration-300 hover:scale-105 hover:shadow-orange-500/50 relative flex flex-col items-center"
    >
      <img
        v-if="movie.poster_path"
        :src="`https://image.tmdb.org/t/p/w300${movie.poster_path}`"
        :alt="movie.title"
        class="w-full h-[400px] object-cover rounded-t-xl"
      />
      <button
        @click="toggleFavorite(movie)"
        class="absolute top-3 right-3 p-2 rounded-full transition-colors bg-gray-900 bg-opacity-50 backdrop-blur-sm"
        :class="isFavorite(movie.id) ? 'text-orange-500' : 'text-white hover:text-orange-500'"
        aria-label="Favoritar"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          :fill="isFavorite(movie.id) ? 'currentColor' : 'none'"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
          class="w-6 h-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            :d="isFavorite(movie.id)
              ? 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'
              : 'M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3z'"
          />
        </svg>
      </button>
      <div class="p-4 w-full text-center">
        <h2 class="text-white font-semibold text-base mb-1 line-clamp-2">{{ movie.title }}</h2>
        <div class="flex justify-center gap-4 text-beige text-sm">
          <span>{{ movie.release_date ? new Date(movie.release_date).getFullYear() : '—' }}</span>
          <span>⭐ {{ movie.vote_average.toFixed(1) }}</span>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="text-center text-gray-400 mt-6">
    Nenhum filme para exibir.
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

const tabs = [
  { key: 'now_playing', label: 'Em Cartaz' },
  { key: 'popular', label: 'Populares' },
  { key: 'top_rated', label: 'Mais Bem Avaliados' },
  { key: 'upcoming', label: 'Próximos Lançamentos' },
]

const activeTab = ref('now_playing')
const movies = ref([])
const favorites = ref(new Set())
const favoriteList = ref([])
const searchQuery = ref('')
const selectedGenreId = ref('')
const genres = ref([])

const loadGenres = async () => {
  try {
    const res = await axios.get('https://api.themoviedb.org/3/genre/movie/list', {
      params: { api_key: import.meta.env.VITE_TMDB_API_KEY, language: 'pt-BR' },
    })
    genres.value = res.data.genres
  } catch (error) {
    console.error('Erro ao carregar gêneros:', error)
  }
}

const fetchMovies = async (category) => {
  try {
    const res = await axios.get(`https://api.themoviedb.org/3/movie/${category}`, {
      params: { api_key: import.meta.env.VITE_TMDB_API_KEY, language: 'pt-BR', page: 1 },
    })
    movies.value = res.data.results
  } catch (error) {
    console.error('Erro ao buscar filmes:', error)
    movies.value = []
  }
}

const searchMovies = async (term) => {
  if (!term.trim()) {
    fetchMovies(activeTab.value)
    return
  }
  try {
    const res = await axios.get('https://api.themoviedb.org/3/search/movie', {
      params: { api_key: import.meta.env.VITE_TMDB_API_KEY, query: term, language: 'pt-BR' },
    })
    movies.value = res.data.results
  } catch {
    movies.value = []
  }
}

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    searchMovies(searchQuery.value.trim())
  } else {
    fetchMovies(activeTab.value)
  }
}

const filteredMovies = computed(() =>
  movies.value.filter((movie) => {
    const matchesGenre = selectedGenreId.value
      ? movie.genre_ids?.includes(Number(selectedGenreId.value))
      : true
    return matchesGenre
  })
)

const loadFavorites = async () => {
  try {
    const res = await axios.get('/api/favorites')
    favoriteList.value = res.data
    favorites.value = new Set(res.data.map((fav) => fav.movie_id))
  } catch {
    favorites.value = new Set()
    favoriteList.value = []
  }
}

const isFavorite = (id) => favorites.value.has(id)

const toggleFavorite = async (movie) => {
  const isFav = isFavorite(movie.id)
  try {
    if (isFav) {
      const fav = favoriteList.value.find((f) => f.movie_id === movie.id)
      if (!fav) return
      await axios.delete(`/api/favorites/${fav.id}`)
      favorites.value.delete(movie.id)
      favoriteList.value = favoriteList.value.filter((f) => f.movie_id !== movie.id)
      alert(`Filme "${movie.title}" removido dos favoritos.`)
    } else {
      const res = await axios.post('/api/favorites', {
        movie_id: movie.id,
        title: movie.title,
        poster_path: movie.poster_path,
      })
      favorites.value.add(movie.id)
      favoriteList.value.push(res.data)
      alert(`Filme "${movie.title}" adicionado aos favoritos!`)
    }
  } catch (error) {
    console.error('Erro ao atualizar favorito:', error.response || error)
    alert('Não foi possível atualizar o favorito.')
  }
}

const goToFavorites = () => {
  window.location.href = '/favorites'
}

onMounted(() => {
  loadGenres()
  fetchMovies(activeTab.value)
  loadFavorites()
})

watch(activeTab, (newTab) => {
  searchQuery.value = ''
  selectedGenreId.value = ''
  fetchMovies(newTab)
})
</script>