@props([
    'title' => 'Crop Your Profile Photo',
    'cancelText' => 'Cancel',
    'applyText' => 'Apply Crop'
])

<!-- Beautiful Crop Modal Overlay -->
<template x-teleport="body">
    <div 
        x-show="cropping && imageUrl" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md"
        style="display: none;"
    >
        <div 
            x-show="cropping && imageUrl"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="bg-slate-900 border border-slate-800 rounded-2xl max-w-2xl w-full p-6 shadow-2xl flex flex-col max-h-[90vh]"
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
                <h3 class="text-lg font-bold text-white">{{ $title }}</h3>
                <button @click="cancelCrop" type="button" class="text-slate-400 hover:text-white transition-colors cursor-pointer">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body (Crop Workspace) -->
            <div class="flex-1 overflow-hidden bg-slate-950 rounded-xl border border-slate-800 flex items-center justify-center p-4 min-h-[300px]">
                <img x-ref="cropperImage" :src="imageUrl" class="max-w-full max-h-[50vh] block">
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-800">
                <button 
                    @click="cancelCrop" 
                    type="button" 
                    class="px-4 py-2 text-sm font-medium text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl border border-slate-700 transition-colors cursor-pointer"
                >
                    {{ $cancelText }}
                </button>
                <button 
                    @click="saveCrop" 
                    type="button" 
                    class="px-5 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 rounded-xl transition-all shadow-lg shadow-indigo-600/20 cursor-pointer"
                >
                    {{ $applyText }}
                </button>
            </div>
        </div>
    </div>
</template>
