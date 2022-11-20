<template>
  <div class="min-h-full">
    <div class="py-10">
      <header>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col space-y-3 md:flex-row md:space-y-0 md:space-x-12 justify-between">
          <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">
            Get started with OMDB
          </h1>
          <div class="w-full md:w-1/3">
            <div class="relative mt-1 flex items-center">
              <input
                v-model="searchForm.query"
                type="text"
                placeholder="13 Reasons Why"
                name="search"
                class="block w-full rounded-md border-gray-300 pr-12 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :disabled="searching"
                @keyup.enter="search"
              >
              <button
                class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5"
                @click="search"
              >
                <kbd class="inline-flex items-center rounded border border-gray-200 px-2 font-sans text-sm font-medium text-gray-400">⏎</kbd>
              </button>
            </div>
          </div>
        </div>
      </header>
      <main>
        <div class="mx-auto max-w-7xl mt-5 sm:px-6 lg:px-8">
          <!--- ACTION MESSAGE -->
          <div
            v-if="searchResults.length === 0"
            class="px-4 py-8 sm:px-0"
          >
            <div
              type="button"
              class="relative block w-full rounded-lg border-2 border-dashed border-opacity-50 hover:border-opacity-50 border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              <div class="text-5xl">
                {{ error ? '😢' : '🔎' }}
              </div>
              <span class="mt-4 text-4xl block font-medium text-gray-900">
                {{ searchText }}
              </span>
            </div>
          </div>
          <!--- ACTION MESSAGE -->
          <!--- MOVIES FOUND LIST -->
          <div
            v-else
            class="w-full flex flex-wrap space-y-3 md:space-y-0"
          >
            <div
              v-for="movie in searchResults"
              :key="movie.imdbID"
              class="w-full md:w-1/2 lg:w-1/4 p-3"
            >
              <MoviePoster
                :title="movie.Title"
                :image-url="movie.Poster"
                :imdb-id="movie.imdbID"
                :year="movie.Year"
                class="w-full border rounded-lg"
              />
            </div>
          </div>
          <!--- MOVIES FOUND LIST -->
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

import axios from 'axios';
import MoviePoster from '@/Components/MoviePoster.vue';

const searching = ref(false);
const searchResults = ref([]);
const error = ref(null);

const searchForm = useForm({
  query: '13 Reasons Why',
});

const search = () => {
  if (searching.value === true) {
    return;
  }

  searching.value = true;
  error.value = null;
  searchResults.value = [];

  axios.post('/api/omdb/query', { q: searchForm.query })
    .then(res => {
      if (res.data.status === 'error') {
        error.value = res.data.message;
        return;
      }

      searchResults.value = res.data.data;
    })
    .finally(() => {
      searching.value = false;
    });
};

const searchText = computed(() => {
  if (error.value) {
    return error.value;
  }

  return searching.value
    ? 'We\'re searching it for you...'
    : 'Use the top search bar to find your fav. movies.';
});
</script>
