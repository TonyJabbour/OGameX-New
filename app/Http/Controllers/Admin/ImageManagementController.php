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
        
        // Scan public/img directory to find all subdirectories
        $basePath = public_path('img');
        $categories = [];
        
        if (is_dir($basePath)) {
            $dirs = glob($basePath . '/*', GLOB_ONLYDIR);
            foreach ($dirs as $dir) {
                $dirName = basename($dir);
                $categories[$dirName] = ucfirst(str_replace(['_', '-'], ' ', $dirName));
            }
        }
        
        // Sort categories alphabetically
        asort($categories);
        
        // Scan for images
        $images = [];
        
        if ($category === 'all') {
            // Scan all subdirectories
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
            // Scan specific category
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
        
        // Sort images by modified date (newest first)
        usort($images, function($a, $b) {
            return $b['modified'] - $a['modified'];
        });
        
        // Scan codebase for image usage
        $imageUsage = $this->scanImageUsage();
        
        // Add usage count to each image
        foreach ($images as &$image) {
            $imageName = $image['name'];
            $image['usage_count'] = $imageUsage[$imageName] ?? 0;
            $image['is_used'] = $image['usage_count'] > 0;
        }
        
        return view('admin.images.index')->with([
            'images' => $images,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }
    
    /**
     * Scan codebase for image usage.
     * Returns array of image filenames and their usage count.
     *
     * @return array
     */
    private function scanImageUsage(): array
    {
        $usage = [];
        $searchPaths = [
            base_path('resources/views'),
            base_path('public/css'),
            base_path('public/js'),
        ];
        
        foreach ($searchPaths as $path) {
            if (!is_dir($path)) continue;
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path)
            );
            
            foreach ($iterator as $file) {
                if (!$file->isFile()) continue;
                
                $extension = $file->getExtension();
                if (!in_array($extension, ['php', 'blade', 'css', 'js'])) continue;
                
                $content = file_get_contents($file->getPathname());
                
                // Search for image references
                // Patterns: /img/..., asset('img/...), url('/img/...)
                preg_match_all('/\/img\/[\w\/\-]+\/(\w+\.(?:jpg|jpeg|png|gif|webp|svg))/i', $content, $matches);
                
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $imageName) {
                        if (!isset($usage[$imageName])) {
                            $usage[$imageName] = 0;
                        }
                        $usage[$imageName]++;
                    }
                }
            }
        }
        
        return $usage;
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
