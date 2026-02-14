<form role="search" method="get" action="<?php echo home_url('/'); ?>" class="relative">

  <input 
    type="search"
    name="s"
    placeholder="Buscar..."
    value="<?php echo get_search_query(); ?>"
    class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-green-900 focus:border-green-900 transition"
  />

  <button type="submit" 
          class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-green-900 transition">
    
    <!-- Icono SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke-width="2" 
         stroke="currentColor" 
         class="w-4 h-4">
      <path stroke-linecap="round" stroke-linejoin="round" 
            d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
    </svg>

  </button>

</form>