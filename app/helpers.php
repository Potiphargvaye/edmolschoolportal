<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('user_avatar')) {
    function user_avatar($user, string $size = 'h-10 w-10'): string
    {
        // Generate initials
        $initials = '';
        $name = trim($user->name);
        if (!empty($name)) {
            $words = preg_split('/\s+/', $name);
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break;
            }
        }

        // Direct file check for Windows/Laragon compatibility
        if ($user->image) {
            $filename = basename($user->image);
            $physicalPath = storage_path('app/public/profile-images/'.$filename);
            
            if (file_exists($physicalPath)) {
                $url = asset('storage/profile-images/'.$filename);
                return '<img src="'.$url.'" class="'.$size.' rounded-full object-cover border border-gray-200" alt="User avatar">';
            }
        }

        // Fallback to initials
        return '<div class="'.$size.' rounded-full bg-gray-200 flex items-center justify-center">
            <span class="text-gray-500 text-xs">'.($initials ?: '?').'</span>
        </div>';
    }
}