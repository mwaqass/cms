<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Setting::orderBy('group')->orderBy('label');
        
        if ($request->has('group')) {
            $query->group($request->group);
        }
        
        $settings = $query->get();
        
        // Group settings by their group
        $grouped = $settings->groupBy('group');
        
        return response()->json($grouped);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:settings',
            'value' => 'nullable|string',
            'type' => 'required|in:string,number,boolean,json,file',
            'group' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $setting = Setting::create([
            'key' => $request->key,
            'value' => $request->value,
            'type' => $request->type,
            'group' => $request->group,
            'label' => $request->label,
            'description' => $request->description,
            'is_public' => $request->get('is_public', false),
        ]);

        return response()->json($setting, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        return response()->json($setting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'value' => 'nullable|string',
            'type' => 'in:string,number,boolean,json,file',
            'group' => 'string|max:255',
            'label' => 'string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $setting->update([
            'value' => $request->value ?? $setting->value,
            'type' => $request->type ?? $setting->type,
            'group' => $request->group ?? $setting->group,
            'label' => $request->label ?? $setting->label,
            'description' => $request->description ?? $setting->description,
            'is_public' => $request->has('is_public') ? $request->is_public : $setting->is_public,
        ]);

        return response()->json($setting);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();
        return response()->json(['message' => 'Setting deleted successfully']);
    }

    /**
     * Update multiple settings at once
     */
    public function updateMultiple(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable|string',
        ]);

        $updated = [];
        foreach ($request->settings as $settingData) {
            $setting = Setting::where('key', $settingData['key'])->first();
            if ($setting) {
                $setting->update(['value' => $settingData['value']]);
                $updated[] = $setting;
            }
        }

        return response()->json(['message' => 'Settings updated successfully', 'updated' => $updated]);
    }
}
