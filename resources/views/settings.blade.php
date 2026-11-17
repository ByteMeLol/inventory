<x-layout>
   <x-slot:title>
        {{ __('Settings') }}
    </x-slot:title>
   <x-success_alert />
   <x-settings
      :company="$company"
      :settings="$settings"
      :inventory-categories="$inventoryCategories"
      :inventory-units="$inventoryUnits"
   />
</x-layout>
