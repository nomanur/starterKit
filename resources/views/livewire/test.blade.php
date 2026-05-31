<div class="max-w-4xl mx-auto my-8 p-6 bg-slate-900/40 backdrop-blur-md rounded-2xl border border-slate-800 text-slate-200 shadow-2xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-800">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-white bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                Spatie Media Library Demo
            </h2>
            <p class="text-sm text-slate-400 mt-1">
                Demonstrating file uploads, association, and retrieval using Spatie Media Library with Livewire.
            </p>
        </div>
        <div class="px-3 py-1 text-xs font-semibold bg-indigo-500/20 text-indigo-400 rounded-full border border-indigo-500/30">
            Livewire + Spatie
        </div>
    </div>

    @if ($successMessage)
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center gap-3 text-emerald-400 transition-all duration-300">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium">{{ $successMessage }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left Column: User details and upload form -->
        <div class="md:col-span-1 space-y-6">
            <!-- User Card -->
            <div class="p-4 bg-slate-950/50 rounded-xl border border-slate-800/80">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">
                    Target Model Association
                </h3>
                @if ($user)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-bold border border-slate-700">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-white">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-amber-400">No user found. Seeding user...</div>
                @endif
            </div>

            <!-- Upload Area -->
            <!-- Upload Area with Alpine JS Image Cropper -->
            <form wire:submit.prevent="save" class="space-y-4">
                <div 
                    x-data="imageCropper({ cropping: true, aspectRatio: 1 })" 
                    @image-cropped.window="@this.upload('photo', $event.detail.file)"
                    @photo-saved.window="imageUrl = null"
                    wire:ignore
                >
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        Upload Image
                    </label>

                    <!-- Drag & Drop container -->
                    <div
                        class="relative group cursor-pointer flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-xl transition-all duration-300 border-slate-800 hover:border-slate-700 bg-slate-950/20"
                        :class="imageUrl ? 'border-indigo-500/50 bg-indigo-950/10' : 'border-slate-800 hover:border-slate-700 bg-slate-950/20'"
                    >
                        <!-- Alpine handles the file selection rather than Livewire directly -->
                        <input
                            type="file"
                            x-ref="fileInput"
                            @change="onFileChange"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            id="photo-upload"
                        />

                        <div class="flex flex-col items-center justify-center text-center space-y-2 pointer-events-none">
                            <div class="p-3 bg-slate-800/80 group-hover:bg-slate-800 rounded-lg text-slate-400 group-hover:text-slate-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <template x-if="imageUrl">
                                <div class="text-sm font-semibold text-indigo-400">
                                    Image Selected & Cropped
                                </div>
                            </template>
                            <template x-if="!imageUrl">
                                <div>
                                    <div class="text-sm font-medium text-slate-300">
                                        Click or drag image here
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        PNG, JPG, WEBP up to 5MB
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <!-- Uploading Indicator -->
                        <div wire:loading wire:target="photo" class="absolute inset-0 bg-slate-950/80 rounded-xl flex flex-col items-center justify-center space-y-2 z-20">
                            <svg class="animate-spin h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs text-indigo-400 font-semibold">Uploading cropped image...</span>
                        </div>
                    </div>

                    @error('photo')
                        <span class="text-xs text-rose-500 mt-2 block">{{ $message }}</span>
                    @enderror

                    <!-- Beautiful Crop Modal Overlay -->
                    <x-cropping-modal />
                </div>

                <!-- Preview if exists in Livewire temporary storage -->
                @if ($photo && !$errors->has('photo'))
                    <div class="p-3 bg-slate-950/30 rounded-xl border border-slate-800">
                        <div class="text-xs font-semibold text-slate-500 mb-2">Livewire Temporary Preview:</div>
                        <img src="{{ $photo->temporaryUrl() }}" class="w-full max-h-48 object-cover rounded-lg border border-slate-800 shadow-md" alt="Preview">
                    </div>
                @endif

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full py-2.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 disabled:from-slate-800 disabled:to-slate-800 text-white rounded-xl font-medium text-sm transition-all duration-300 shadow-lg shadow-indigo-600/15 border border-indigo-500/20 flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span wire:loading.remove>Save to Media Library</span>
                    <span wire:loading>Processing...</span>
                </button>
            </form>
        </div>

        <!-- Right Column: Single Media View (Avatar) -->
        <div class="md:col-span-2 space-y-6">
            <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                Current Active Avatar (single file collection)
            </h3>

            @if (!$avatar)
                <div class="flex flex-col items-center justify-center p-8 bg-slate-950/20 border border-slate-800/80 rounded-xl text-center">
                    <div class="p-3 bg-slate-900/60 rounded-full text-slate-600 mb-3">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="text-sm font-semibold text-slate-400">No avatar uploaded</div>
                    <p class="text-xs text-slate-600 mt-1 max-w-xs">
                        Upload a profile picture on the left. The Spatie Media Library is set to <code>singleFile()</code>, meaning any new upload will automatically replace the old one!
                    </p>
                </div>
            @else
                <div class="p-6 bg-slate-950/40 rounded-xl border border-slate-800/80 hover:border-slate-700/80 transition-colors flex flex-col items-center text-center group">
                    <!-- Avatar Image Preview (Large Profile Format) -->
                    <div class="relative w-36 h-36 rounded-full overflow-hidden bg-slate-900 border-2 border-indigo-500/50 mb-4 shadow-lg shadow-indigo-500/10 group-hover:scale-105 transition-transform duration-500">
                        <img src="{{ $avatar->getUrl() }}" class="w-full h-full object-cover" alt="Avatar" onerror="this.src='https://placehold.co/150x150/1e293b/94a3b8?text=Avatar';">
                        <div class="absolute bottom-1 right-2 px-1.5 py-0.5 text-[8px] font-semibold bg-slate-950/90 text-slate-300 rounded backdrop-blur-sm border border-slate-800">
                            ID: {{ $avatar->id }}
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="space-y-1 w-full max-w-md">
                        <div class="text-sm font-bold text-white truncate" title="{{ $avatar->file_name }}">
                            {{ $avatar->file_name }}
                        </div>
                        <div class="flex items-center justify-center gap-3 text-xs text-slate-500">
                            <span>Size: {{ $avatar->human_readable_size }}</span>
                            <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                            <span>Mime: {{ $avatar->mime_type }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 pt-4 border-t border-slate-900 w-full flex items-center justify-between gap-3 max-w-sm">
                        <a
                            href="{{ $avatar->getUrl() }}"
                            target="_blank"
                            class="text-xs text-indigo-400 hover:text-indigo-300 font-medium inline-flex items-center gap-1"
                        >
                            Open Original
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>

                        <button
                            type="button"
                            wire:click="deleteAvatar"
                            class="p-1 px-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 rounded-lg border border-rose-500/20 text-xs font-semibold cursor-pointer transition-colors"
                        >
                            Remove Avatar
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
