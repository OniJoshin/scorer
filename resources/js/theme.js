/**
 * Theme Manager for Alpine.js
 * Manages light/dark/auto theme switching with persistence
 */

function registerThemeManager() {
    if (!window.Alpine) {
        return false;
    }

    window.Alpine.data('themeManager', (initialTheme = 'auto') => ({
        // Current theme setting: 'light', 'dark', or 'auto'
        theme: ['light', 'dark', 'auto'].includes(initialTheme) ? initialTheme : 'auto',
        
        // Initialize theme from storage
        init() {
            // Try to get from localStorage first
            let stored = null;
            try {
                stored = localStorage.getItem('theme');
            } catch (error) {
                stored = null;
            }

            if (stored && ['light', 'dark', 'auto'].includes(stored)) {
                this.theme = stored;
            }
            
            // Apply the theme
            this.applyTheme();
            
            // Watch for system theme changes when in auto mode
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    if (this.theme === 'auto') {
                        this.applyTheme();
                    }
                });
            }
        },
        
        // Get the effective theme (resolves 'auto' to 'light' or 'dark')
        getEffectiveTheme() {
            if (this.theme === 'auto') {
                return this.getSystemTheme();
            }
            return this.theme;
        },
        
        // Get system theme preference
        getSystemTheme() {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return 'dark';
            }
            return 'light';
        },
        
        // Set theme and persist
        setTheme(newTheme) {
            if (!['light', 'dark', 'auto'].includes(newTheme)) {
                console.error('Invalid theme:', newTheme);
                return;
            }
            
            this.theme = newTheme;
            try {
                localStorage.setItem('theme', newTheme);
            } catch (error) {
                // Ignore storage errors and continue applying theme
            }
            this.applyTheme();
            
            // Also save to NativePHP settings if available
            this.saveToNativeSettings(newTheme);
        },
        
        // Apply the theme to the document
        applyTheme() {
            const effective = this.getEffectiveTheme();
            const html = document.documentElement;
            
            if (effective === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        },
        
        // Toggle between light and dark (skips auto)
        toggleTheme() {
            const current = this.getEffectiveTheme();
            this.setTheme(current === 'dark' ? 'light' : 'dark');
        },
        
        // Check if a specific theme is active
        isTheme(checkTheme) {
            if (checkTheme === 'auto') {
                return this.theme === 'auto';
            }
            return this.getEffectiveTheme() === checkTheme;
        },
        
        // Save to NativePHP settings (when running as desktop app)
        async saveToNativeSettings(theme) {
            // This would call a Laravel route that stores via NativePHP Settings facade
            try {
                const response = await fetch('/settings/theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ theme })
                });
                
                if (!response.ok) {
                    console.warn('Failed to save theme to server');
                }
            } catch (error) {
                // Fail silently - localStorage is still working
                console.warn('Could not sync theme to server:', error);
            }
        }
    }));

    return true;
}

if (!registerThemeManager()) {
    document.addEventListener('alpine:init', registerThemeManager, { once: true });
}

// Export for potential use in other modules
export function getCurrentTheme() {
    const stored = localStorage.getItem('theme') || 'auto';
    if (stored === 'auto') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    return stored;
}
