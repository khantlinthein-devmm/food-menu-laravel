<template>
    <div class="flex flex-col min-h-screen">
      <!-- Main Welcome Section with Background Image -->
      <main class="relative flex flex-col items-center justify-center flex-1 px-4 py-16 text-center">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
          <div class="absolute inset-0 z-10 bg-black opacity-50"></div>
          <div 
            class="absolute inset-0 z-0 bg-center bg-cover" 
            :style="{ 
              backgroundImage: 'url(https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop)'
            }"
          ></div>
        </div>
  
        <!-- Content -->
        <div class="relative z-20 max-w-4xl mx-auto text-white">
          <!-- Logo/Restaurant Name -->
          <div class="mb-8">
            <h1 class="mb-2 font-serif text-6xl font-bold tracking-tight md:text-7xl">KEP</h1>
            <div class="flex items-center justify-center gap-4">
              <div class="w-16 h-px bg-amber-400"></div>
              <p class="text-xl font-light tracking-widest text-amber-300">RESTAURANT</p>
              <div class="w-16 h-px bg-amber-400"></div>
            </div>
          </div>
  
          <!-- Welcome Message -->
          <p class="max-w-2xl mx-auto mb-8 text-xl font-light md:text-2xl">
            Experience exquisite culinary delights in an elegant atmosphere. 
            Where every dish tells a story and every meal becomes a memory.
          </p>
  
          <!-- Call to Action Buttons -->
          <div class="flex flex-col justify-center gap-4 mb-16 sm:flex-row">
            <a href="/menu">
                <button class="px-8 py-3 font-medium text-white transition duration-300 rounded-sm bg-amber-600 hover:bg-amber-700">
                  View Our Menu
                </button>
            </a>
            <!-- <button class="px-8 py-3 font-medium text-white transition duration-300 border border-white rounded-sm hover:bg-white hover:text-amber-900">
              Reserve a Table
            </button> -->
          </div>
  
          <!-- Restaurant Info -->
          <div class="grid grid-cols-1 gap-8 text-center md:grid-cols-3">
            <div>
              <h3 class="mb-2 text-lg font-medium text-amber-300">Location</h3>
              <p>123 Culinary Avenue</p>
              <p>Gourmet City, GC 12345</p>
            </div>
            <div>
              <h3 class="mb-2 text-lg font-medium text-amber-300">Hours</h3>
              <p>Monday - Friday: 11am - 10pm</p>
              <p>Weekends: 10am - 11pm</p>
            </div>
            <div>
              <h3 class="mb-2 text-lg font-medium text-amber-300">Contact</h3>
              <p>Phone: (123) 456-7890</p>
              <p>Email: info@keprestaurant.com</p>
            </div>
          </div>
        </div>
      </main>
  
      <!-- Simple Footer -->
      <footer class="relative z-20 px-4 py-4 text-center text-white bg-black bg-opacity-70">
        <p>&copy; {{ new Date().getFullYear() }} KEP Restaurant. All rights reserved.</p>
      </footer>
    </div>
  </template>
  
  <script setup>
  // No complex logic needed for this simple welcome page
  </script>
  
  <style scoped>
  /* Custom font styling */
  .font-serif {
    font-family: 'Playfair Display', Georgia, serif;
  }
  
  /* Additional custom styles */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  main > div {
    animation: fadeIn 1.5s ease-out;
  }
  </style>