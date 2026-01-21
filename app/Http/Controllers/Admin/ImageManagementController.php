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
        
        if ($category === 'all' || $category === 'unused') {
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
        
        // Filter based on category
        if ($category === 'unused') {
            // Show only unused images
            $images = array_filter($images, function($img) {
                return !$img['is_used'];
            });
        } elseif ($category !== 'all') {
            // For specific categories, show all images from that category
            $images = array_filter($images, function($img) use ($category) {
                return $img['category'] === $category;
            });
        }
        // For 'all', show everything (both used and unused)
        
        // Count unused images for the filter
        $unusedCount = 0;
        $allImages = [];
        foreach ($categories as $cat => $label) {
            $categoryPath = $basePath . '/' . $cat;
            if (is_dir($categoryPath)) {
                $files = glob($categoryPath . '/*.{jpg,jpeg,png,gif,webp,svg}', GLOB_BRACE);
                foreach ($files as $file) {
                    $fileName = basename($file);
                    $isUsed = isset($imageUsage[$fileName]) && $imageUsage[$fileName] > 0;
                    if (!$isUsed) {
                        $unusedCount++;
                    }
                }
            }
        }
        
        return view('admin.images.index')->with([
            'images' => array_values($images),
            'categories' => $categories,
            'currentCategory' => $category,
            'unusedCount' => $unusedCount,
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
            
            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                
                foreach ($iterator as $file) {
                    if (!$file->isFile()) continue;
                    
                    $extension = $file->getExtension();
                    if (!in_array($extension, ['php', 'blade', 'css', 'js', 'html'])) continue;
                    
                    $content = @file_get_contents($file->getPathname());
                    if ($content === false) continue;
                    
                    // Multiple patterns to catch all image references
                    $patterns = [
                        // Standard paths: /img/category/filename.ext
                        '/\/img\/[\w\/\-]+\/([\w\-\.]+\.(?:jpg|jpeg|png|gif|webp|svg))/i',
                        // CSS url() with quotes: url('/img/...')
                        '/url\([\'"]?\/img\/[\w\/\-]+\/([\w\-\.]+\.(?:jpg|jpeg|png|gif|webp|svg))[\'"]?\)/i',
                        // Asset helper: asset('img/...')
                        '/asset\([\'"]img\/[\w\/\-]+\/([\w\-\.]+\.(?:jpg|jpeg|png|gif|webp|svg))[\'"]\)/i',
                        // Background-image in style attributes
                        '/background[\-\w]*:[\s]*url\([\'"]?\/img\/[\w\/\-]+\/([\w\-\.]+\.(?:jpg|jpeg|png|gif|webp|svg))[\'"]?\)/i',
                        // Src attributes
                        '/src=[\'"]?\/img\/[\w\/\-]+\/([\w\-\.]+\.(?:jpg|jpeg|png|gif|webp|svg))[\'"]?/i',
                    ];
                    
                    foreach ($patterns as $pattern) {
                        preg_match_all($pattern, $content, $matches);
                        
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
            } catch (\Exception $e) {
                // Skip directories that can't be read
                continue;
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
     * Replace an image with a new file (keeps same filename).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function replace(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'old_path' => 'required|string',
        ]);
        
        $oldPath = public_path($validated['old_path']);
        
        if (!file_exists($oldPath)) {
            return redirect()->back()->with('error', 'Original image not found');
        }
        
        $file = $request->file('image');
        $filename = basename($oldPath);
        $directory = dirname($oldPath);
        
        // Delete old file
        unlink($oldPath);
        
        // Move new file with same name
        $file->move($directory, $filename);
        
        return redirect()->back()->with('success', "Image replaced successfully: {$filename}");
    }
    
    /**
     * Archive all unused images by moving them to an archive folder.
     *
     * @return RedirectResponse
     */
    public function archiveUnused(): RedirectResponse
    {
        $basePath = public_path('img');
        $archivePath = public_path('img/archive');
        
        // Create archive directory if it doesn't exist
        if (!is_dir($archivePath)) {
            mkdir($archivePath, 0755, true);
        }
        
        // Get image usage
        $imageUsage = $this->scanImageUsage();
        
        $movedCount = 0;
        $errors = [];
        
        // Scan all directories
        if (is_dir($basePath)) {
            $dirs = glob($basePath . '/*', GLOB_ONLYDIR);
            foreach ($dirs as $dir) {
                $dirName = basename($dir);
                
                // Skip archive directory itself
                if ($dirName === 'archive') continue;
                
                $files = glob($dir . '/*.{jpg,jpeg,png,gif,webp,svg}', GLOB_BRACE);
                foreach ($files as $file) {
                    $fileName = basename($file);
                    $isUsed = isset($imageUsage[$fileName]) && $imageUsage[$fileName] > 0;
                    
                    if (!$isUsed) {
                        // Create category subfolder in archive
                        $archiveCategoryPath = $archivePath . '/' . $dirName;
                        if (!is_dir($archiveCategoryPath)) {
                            mkdir($archiveCategoryPath, 0755, true);
                        }
                        
                        $destination = $archiveCategoryPath . '/' . $fileName;
                        
                        // Move file
                        if (rename($file, $destination)) {
                            $movedCount++;
                        } else {
                            $errors[] = $fileName;
                        }
                    }
                }
            }
        }
        
        if (!empty($errors)) {
            return redirect()->back()->with('error', "Archived {$movedCount} images, but failed to move: " . implode(', ', $errors));
        }
        
        return redirect()->back()->with('success', "Successfully archived {$movedCount} unused images to /img/archive/");
    }
}
