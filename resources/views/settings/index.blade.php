@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-app-primary">Settings</h1>
        <p class="text-app-secondary mt-1">Customize your app experience</p>
    </div>
    
    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        {{-- Appearance Section --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center mb-4">
                <x-heroicon-o-paint-brush class="w-6 h-6 text-[var(--color-primary)] mr-3"/>
                <h2 class="text-xl font-semibold text-app-primary">Appearance</h2>
            </div>
            
            {{-- Theme Selection --}}
            <div class="space-y-3">
                <label class="text-sm font-medium text-app-secondary">Theme</label>
                <input type="hidden" id="theme-input" name="theme" value="{{ $currentTheme }}">
                <div class="grid grid-cols-3 gap-3">
                    <button type="button"
                            data-theme-option="light"
                            onclick="selectAppTheme('light')"
                            class="theme-option relative flex flex-col items-center p-4 border-2 rounded-lg transition-all hover:border-[var(--color-primary)] {{ $currentTheme === 'light' ? 'border-[var(--color-primary)] bg-[var(--color-primary-light)] shadow-sm' : 'border-app' }}"
                            aria-pressed="{{ $currentTheme === 'light' ? 'true' : 'false' }}">
                        <x-heroicon-o-sun class="theme-option-icon w-8 h-8 mb-2 {{ $currentTheme === 'light' ? 'text-[var(--color-primary)]' : 'text-app-secondary' }}"/>
                        <span class="text-sm font-medium text-app-primary">Light</span>
                    </button>

                    <button type="button"
                            data-theme-option="dark"
                            onclick="selectAppTheme('dark')"
                            class="theme-option relative flex flex-col items-center p-4 border-2 rounded-lg transition-all hover:border-[var(--color-primary)] {{ $currentTheme === 'dark' ? 'border-[var(--color-primary)] bg-[var(--color-primary-light)] shadow-sm' : 'border-app' }}"
                            aria-pressed="{{ $currentTheme === 'dark' ? 'true' : 'false' }}">
                        <x-heroicon-o-moon class="theme-option-icon w-8 h-8 mb-2 {{ $currentTheme === 'dark' ? 'text-[var(--color-primary)]' : 'text-app-secondary' }}"/>
                        <span class="text-sm font-medium text-app-primary">Dark</span>
                    </button>

                    <button type="button"
                            data-theme-option="auto"
                            onclick="selectAppTheme('auto')"
                            class="theme-option relative flex flex-col items-center p-4 border-2 rounded-lg transition-all hover:border-[var(--color-primary)] {{ $currentTheme === 'auto' ? 'border-[var(--color-primary)] bg-[var(--color-primary-light)] shadow-sm' : 'border-app' }}"
                            aria-pressed="{{ $currentTheme === 'auto' ? 'true' : 'false' }}">
                        <x-heroicon-o-computer-desktop class="theme-option-icon w-8 h-8 mb-2 {{ $currentTheme === 'auto' ? 'text-[var(--color-primary)]' : 'text-app-secondary' }}"/>
                        <span class="text-sm font-medium text-app-primary">Auto</span>
                    </button>
                </div>
                <p class="text-xs text-app-tertiary">Auto mode follows your system preference</p>
            </div>
        </div>
        
        {{-- Display Preferences --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center mb-4">
                <x-heroicon-o-adjustments-horizontal class="w-6 h-6 text-[var(--color-primary)] mr-3"/>
                <h2 class="text-xl font-semibold text-app-primary">Display</h2>
            </div>
            
            <div class="space-y-4">
                {{-- Compact Mode --}}
                <label class="flex items-center justify-between p-4 rounded-lg hover:bg-app-secondary cursor-pointer transition-colors">
                    <div class="flex-1">
                        <div class="font-medium text-app-primary">Compact Mode</div>
                        <div class="text-sm text-app-secondary">Reduce spacing between elements</div>
                    </div>
                    <div class="ml-4">
                        <input type="hidden" name="compact_mode" value="0">
                        <input type="checkbox" name="compact_mode" value="1" 
                               {{ $compactMode ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-app-dark text-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)] focus:ring-offset-0">
                    </div>
                </label>
                
                {{-- Animations --}}
                <label class="flex items-center justify-between p-4 rounded-lg hover:bg-app-secondary cursor-pointer transition-colors">
                    <div class="flex-1">
                        <div class="font-medium text-app-primary">Enable Animations</div>
                        <div class="text-sm text-app-secondary">Show transitions and effects</div>
                    </div>
                    <div class="ml-4">
                        <input type="hidden" name="animations_enabled" value="0">
                        <input type="checkbox" name="animations_enabled" value="1" {{ $animationsEnabled ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-app-dark text-[var(--color-primary)] focus:ring-2 focus:ring-[var(--color-primary)] focus:ring-offset-0">
                    </div>
                </label>
            </div>
        </div>
        
        {{-- Data Management --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center mb-4">
                <x-heroicon-o-circle-stack class="w-6 h-6 text-[var(--color-primary)] mr-3"/>
                <h2 class="text-xl font-semibold text-app-primary">Data</h2>
            </div>
            
            <div class="space-y-3">
                <button type="button" 
                        class="w-full flex items-center justify-between p-4 rounded-lg border border-app-dark hover:bg-app-secondary transition-colors text-left">
                    <div class="flex items-center">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-app-secondary mr-3"/>
                        <div>
                            <div class="font-medium text-app-primary">Export Data</div>
                            <div class="text-sm text-app-secondary">Download all your data as JSON</div>
                        </div>
                    </div>
                    <x-heroicon-o-chevron-right class="w-5 h-5 text-app-tertiary"/>
                </button>
                
                <button type="button" 
                        class="w-full flex items-center justify-between p-4 rounded-lg border border-app-dark hover:bg-app-secondary transition-colors text-left">
                    <div class="flex items-center">
                        <x-heroicon-o-arrow-path class="w-5 h-5 text-app-secondary mr-3"/>
                        <div>
                            <div class="font-medium text-app-primary">Backup & Restore</div>
                            <div class="text-sm text-app-secondary">Save and restore your data</div>
                        </div>
                    </div>
                    <x-heroicon-o-chevron-right class="w-5 h-5 text-app-tertiary"/>
                </button>
                
                <button type="button" 
                        class="w-full flex items-center justify-between p-4 rounded-lg border-2 border-[var(--color-error)] hover:bg-[var(--color-error-bg)] transition-colors text-left">
                    <div class="flex items-center">
                        <x-heroicon-o-trash class="w-5 h-5 text-[var(--color-error)] mr-3"/>
                        <div>
                            <div class="font-medium text-[var(--color-error)]">Clear All Data</div>
                            <div class="text-sm text-app-secondary">Permanently delete all records</div>
                        </div>
                    </div>
                    <x-heroicon-o-chevron-right class="w-5 h-5 text-app-tertiary"/>
                </button>
            </div>
        </div>
        
        {{-- About Section --}}
        <div class="bg-app-elevated rounded-xl p-6 border border-app">
            <div class="flex items-center mb-4">
                <x-heroicon-o-information-circle class="w-6 h-6 text-[var(--color-primary)] mr-3"/>
                <h2 class="text-xl font-semibold text-app-primary">About</h2>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3">
                    <span class="text-app-secondary">Version</span>
                    <span class="font-medium text-app-primary">{{ $appVersion }}</span>
                </div>
                <div class="flex justify-between items-center p-3">
                    <span class="text-app-secondary">Built with</span>
                    <span class="font-medium text-app-primary">Laravel + NativePHP</span>
                </div>
            </div>
        </div>
        
        {{-- Save Button --}}
        <div class="flex justify-end pt-4">
            <button type="submit" 
                    class="flex items-center px-6 py-3 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white font-semibold rounded-lg transition-colors">
                <x-heroicon-s-check class="w-5 h-5 mr-2"/>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    function selectAppTheme(theme) {
        const validThemes = ['light', 'dark', 'auto'];
        if (!validThemes.includes(theme)) {
            return;
        }

        const input = document.getElementById('theme-input');
        if (input) {
            input.value = theme;
        }

        const options = document.querySelectorAll('.theme-option');
        options.forEach((option) => {
            const isActive = option.dataset.themeOption === theme;
            option.classList.toggle('border-[var(--color-primary)]', isActive);
            option.classList.toggle('bg-[var(--color-primary-light)]', isActive);
            option.classList.toggle('shadow-sm', isActive);
            option.classList.toggle('border-app', !isActive);
            option.setAttribute('aria-pressed', isActive ? 'true' : 'false');

            const icon = option.querySelector('.theme-option-icon');
            if (icon) {
                icon.classList.toggle('text-[var(--color-primary)]', isActive);
                icon.classList.toggle('text-app-secondary', !isActive);
            }
        });

        const effectiveTheme = theme === 'auto'
            ? (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            : theme;

        document.documentElement.classList.toggle('dark', effectiveTheme === 'dark');

        try {
            localStorage.setItem('theme', theme);
        } catch (error) {
            console.warn('Could not save theme locally:', error);
        }

        fetch('/settings/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ theme }),
        }).catch(() => {
            // Non-blocking sync
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const initialTheme = document.getElementById('theme-input')?.value || 'auto';
        selectAppTheme(initialTheme);
    });
</script>
@endsection