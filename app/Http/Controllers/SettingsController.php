<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Check if running as NativePHP desktop app
     */
    protected function isNativeApp(): bool
    {
        return class_exists(\Native\Laravel\Facades\Settings::class) && 
               app()->bound('native.settings');
    }
    
    /**
     * Get a setting value
     */
    protected function getSetting(string $key, $default = null)
    {
        if ($this->isNativeApp()) {
            try {
                return \Native\Laravel\Facades\Settings::get($key, $default);
            } catch (\Exception $e) {
                // Fall back to cache if NativePHP fails
            }
        }
        
        // Use cache for web mode
        return Cache::get("settings.{$key}", $default);
    }
    
    /**
     * Set a setting value
     */
    protected function setSetting(string $key, $value): void
    {
        if ($this->isNativeApp()) {
            try {
                \Native\Laravel\Facades\Settings::set($key, $value);
                return;
            } catch (\Exception $e) {
                // Fall back to cache if NativePHP fails
            }
        }
        
        // Use cache for web mode (persist for 1 year)
        Cache::put("settings.{$key}", $value, now()->addYear());
    }
    
    /**
     * Display the settings page
     */
    public function index(): View
    {
        $currentTheme = $this->getSetting('theme', 'auto');
        $compactMode = (bool) $this->getSetting('compact_mode', false);
        $animationsEnabled = (bool) $this->getSetting('animations_enabled', true);
        
        return view('settings.index', [
            'currentTheme' => $currentTheme,
            'compactMode' => $compactMode,
            'animationsEnabled' => $animationsEnabled,
            'appVersion' => config('nativephp.version', '1.0.0'),
        ]);
    }
    
    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'nullable|in:light,dark,auto',
            'compact_mode' => 'required|boolean',
            'animations_enabled' => 'required|boolean',
        ]);
        
        // Save theme preference
        if (isset($validated['theme'])) {
            $this->setSetting('theme', $validated['theme']);
        }
        
        // Save display preferences
        $this->setSetting('compact_mode', (bool) $validated['compact_mode']);
        $this->setSetting('animations_enabled', (bool) $validated['animations_enabled']);
        
        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully');
    }
    
    /**
     * Update theme via AJAX (called from theme manager JS)
     */
    public function updateTheme(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark,auto',
        ]);
        
        $this->setSetting('theme', $validated['theme']);
        
        return response()->json(['success' => true]);
    }
}