<?php

namespace OGame\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use OGame\Http\Controllers\OGameController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageManagementController extends OGameController
{
    /**
     * Shows the image management page.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $category = $request->input('category', 'all');
        
        // Define image categories based on game content
        $categories = [
            'planets' => 'Planets',
            'ships' => 'Ships',
            'buildings' => 'Buildings',
            'research' => 'Research',
            'defense' => 'Defense',
            'screenshots' => 'Screenshots',
            'ui' => 'UI Elements',
            'backgrounds' => 'Backgrounds',
        ];
        
        // Scan public/img directory
        $images = [];
        $basePath = public_path('img');
        
        if ($category === 'all') {
            foreach ($categories as $cat => $label) {
                $categoryPath = $basePath . '/' . $cat;
                if (is_dir($categoryPath)) {
                    $files = glob($categoryPath . '/*.{jpg,jpeg,png,gif,webp,svg}', GLOB_BRACE);
                    foreach ($files as $file) {
                        $images[] = [
                            'path' => '/img/' . $cat . '/' . basename($file),
                            'name' => basename($file),
                            'category' => $cat,
                            'size' => filesize($file),
                            'modified' => filemtime($file),
                        ];
                    }
                }
            }
        } else {
            $categoryPath = $basePath . '/' . $category;
            if (is_dir($categoryPath)) {
                $files = glob($categoryPath . '/*.{jpg,jpeg,png,gif,webp,svg}', GLOB_BRACE);
                foreach ($files as $file) {
                    $images[] = [
                        'path' => '/img/' . $category . '/' . basename($file),
                        'name' => basename($file),
                        'category' => $category,
                        'size' => filesize($file),
                        'modified' => filemtime($file),
                    ];
                }
            }
        }
        
        return view('admin.images.index')->with([
            'images' => $images,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }

    /**
     * Upload a new image.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function upload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120', // 5MB max
            'category' => 'required|string',
            'custom_name' => 'nullable|string|max:100',
        ]);
        
        $file = $request->file('image');
        $category = $validated['category'];
        
        // Generate filename
        if ($request->filled('custom_name')) {
            $filename = Str::slug($validated['custom_name']) . '.' . $file->getClientOriginalExtension();
        } else {
            $filename = $file->getClientOriginalName();
        }
        
        // Ensure directory exists
        $categoryPath = public_path('img/' . $category);
        if (!is_dir($categoryPath)) {
            mkdir($categoryPath, 0755, true);
        }
        
        // Move file
        $file->move($categoryPath, $filename);
        
        return redirect()->back()->with('success', "Image uploaded successfully: {$filename}");
    }

    /**
     * Delete an image.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $path = $request->input('path');
        $fullPath = public_path($path);
        
        if (file_exists($fullPath)) {
            unlink($fullPath);
            return redirect()->back()->with('success', 'Image deleted successfully');
        }
        
        return redirect()->back()->with('error', 'Image not found');
    }

    /**
     * Rename an image.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function rename(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'old_path' => 'required|string',
            'new_name' => 'required|string|max:100',
        ]);
        
        $oldPath = public_path($validated['old_path']);
        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
        $directory = dirname($oldPath);
        $newFilename = Str::slug($validated['new_name']) . '.' . $extension;
        $newPath = $directory . '/' . $newFilename;
        
        if (file_exists($oldPath)) {
            rename($oldPath, $newPath);
            return redirect()->back()->with('success', "Image renamed to: {$newFilename}");
        }
        
        return redirect()->back()->with('error', 'Image not found');
    }
}
