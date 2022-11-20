<template>
  <a
    class="relative block h-full w-full transition-transform delay-75 ease-in duration-300 hover:scale-110"
    :href="`https://www.imdb.com/title/${imdbId}`"
    target="_blank"
  >
    <div class="w-full h-full bg-gradient-to-t from-black to-transparent absolute z-10" />
    <img
      :src="imageUrl"
      onerror="this.src=`https://via.placeholder.com/300x444?text=Poster+not+found`"
      class="z-0 object-cover w-full h-full"
    >
    <div class="absolute flex flex-col z-20 p-6 bottom-0 text-white">
      <div class="font-bold text-4xl md:text-2xl lg:text-lg">
        {{ title }}
      </div>
      <div class="font-light text-2xl md:text-lg lg:text-base text-gray-400">
        {{ year }}
      </div>
    </div>
  </a>
</template>

<script setup>
import { defineProps } from 'vue';

defineProps({
  title: String,
  year: String,
  imageUrl: String,
  imdbId: String,
});
</script>
