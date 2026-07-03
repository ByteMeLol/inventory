<x-layout>
   <x-slot:title>
        settings
    </x-slot:title>
   <x-success_alert />
   <x-settings :company="$company" :settings="$settings" />
</x-layout>