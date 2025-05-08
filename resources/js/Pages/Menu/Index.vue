<script setup>
import {ref} from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps(['tabs', 'menuItems', 'currentTime']);

const activeTab = ref(null);

const setTab = (tabId) => {
   activeTab.value = tabId;
   router.get('/menu', {tab : tabId}, {preserveState : true, preserveScroll: true});
}

const parseTime = (timeStr) => {
  // Convert time string like "08:00" to minutes since midnight
  if (!timeStr) return 0;
  
  const [hours, minutes] = timeStr.split(':').map(Number);
  return hours * 60 + minutes;
};

const isAvailable = (item, currentTime) => {
  // Convert all times to minutes for proper numeric comparison
  const currentMinutes = parseTime(currentTime);
  let fromMinutes = parseTime(item.available_from);
  let untilMinutes = parseTime(item.available_until);

  // handle midnight crossing
  if (untilMinutes === 0) {
   untilMinutes = 1440; // Treat 00:00 as 24:00 (end of day)
  }
  
  // Debugging logs
  console.log(`Item: ${item.name}`);
  console.log(`Current: ${currentTime} (${currentMinutes}) minutes`);
  console.log(`From: ${item.available_from} (${fromMinutes}) minutes`);
  console.log(`Until: ${item.available_until} (${untilMinutes}) minutes`);
  console.log(`Is Available: ${currentMinutes >= fromMinutes && currentMinutes <= untilMinutes}`);


  // If untilMinutes is less than fromMinutes, it crosses midnight
  if (untilMinutes < fromMinutes) {
    // Check if current time is either after fromMinutes (today) or before untilMinutes (next day)
    return currentMinutes >= fromMinutes || currentMinutes <= untilMinutes;
  }


  return currentMinutes >= fromMinutes && currentMinutes <= untilMinutes;
};
</script>

<template>
  <div class="min-h-screen bg-[#fcf9f2]">
    <!-- Header with decorative elements -->
    <div class="relative py-12 bg-[#2c2420] text-white">
      <div class="px-6 mx-auto max-w-7xl">
        <div class="flex items-center justify-center">
          <div class="text-center">
            <h1 class="font-serif text-3xl font-bold tracking-wide md:text-4xl">KEP Restaurant Menu</h1>
            <div class="flex items-center justify-center mt-3">
              <div class="w-12 h-px bg-amber-400"></div>
              <div class="mx-3">
                <svg class="w-6 h-6 text-amber-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6.75 2C6.75 2 6 2.75 6 4.25C6 5.75 7 6.5 7 6.5M17.25 2C17.25 2 18 2.75 18 4.25C18 5.75 17 6.5 17 6.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M11 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16V12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12V16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M12 14V18M12 14L10 16M12 14L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="w-12 h-px bg-amber-400"></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Decorative wave -->
      <div class="absolute left-0 right-0 -bottom-1">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" class="fill-[#fcf9f2]">
          <path d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
      </div>
    </div>

    <div class="px-4 py-8 mx-auto max-w-7xl md:px-6">
      <!-- Tab Navigation -->
      <div class="relative mb-12">
        <div class="flex pb-2 overflow-x-auto hide-scrollbar md:justify-center">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="setTab(tab.id)"
            :class="[
              'px-6 py-3 mx-2 text-sm font-medium transition-all duration-300 whitespace-nowrap',
              activeTab === tab.id 
                ? 'bg-[#2c2420] text-white rounded-full shadow-md' 
                : 'bg-white text-[#2c2420] hover:bg-amber-100 rounded-full border border-amber-200'
            ]"
          >
            {{ tab.name }}
          </button>
        </div>
      </div>

      <!-- Menu Items Grid -->
      <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="item in menuItems"
          :key="item.id"
          class="overflow-hidden transition-all duration-300 bg-white shadow-md group rounded-xl hover:shadow-xl"
          :class="{
            'opacity-100': isAvailable(item, currentTime),
            'opacity-50 pointer-events-none': !isAvailable(item, currentTime)
          }"
        >
          <!-- Image Container with Overlay -->
          <div class="relative h-56 overflow-hidden">
            <img 
              :src="item.photo" 
              class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110" 
              alt="Food item"
            />
            
            <!-- Category Badge -->
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-[#2c2420] text-xs font-medium px-3 py-1.5 rounded-full">
              {{ item.category.name }}
            </div>
            
            <!-- Availability Badge -->
            <div 
              v-if="!isAvailable(item, currentTime)" 
              class="absolute top-4 right-4 bg-red-500/90 backdrop-blur-sm text-white text-xs font-medium px-3 py-1.5 rounded-full"
            >
              Not Available
            </div>
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 transition-opacity duration-300 opacity-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent group-hover:opacity-100"></div>
          </div>
          
          <!-- Content -->
          <div class="p-6">
            <h2 class="text-xl font-serif font-semibold text-[#2c2420] mb-2">{{ item.name }}</h2>
            
            <div class="flex items-center mb-3 text-amber-600">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
              </svg>
              <p class="text-xs font-medium">
                Available: {{ item.available_from }} - {{ item.available_until }}
              </p>
            </div>
            
            <div class="mt-4">
              <button
                v-if="isAvailable(item, currentTime)"
                class="w-full px-4 py-3 bg-[#2c2420] text-white rounded-lg text-sm font-medium hover:bg-[#3a302b] transition-colors duration-300 flex items-center justify-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Order Now
              </button>
              <p v-else class="w-full py-3 text-sm italic text-center text-red-500 rounded-lg bg-red-50">
                Not available at this time
              </p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Empty State -->
      <div v-if="menuItems.length === 0" class="py-20 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <h3 class="text-xl font-medium text-[#2c2420] mb-2">No menu items available</h3>
        <p class="text-stone-500">Try selecting a different category or check back later.</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Custom font styling */
.font-serif {
  font-family: 'Playfair Display', Georgia, serif;
}

/* Hide scrollbar but allow scrolling */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}

.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Subtle animation for menu items */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.grid > div {
  animation: fadeIn 0.5s ease-out forwards;
  animation-delay: calc(var(--index, 0) * 0.1s);
}
</style>