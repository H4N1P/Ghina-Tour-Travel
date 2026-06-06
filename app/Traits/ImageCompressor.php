<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ImageCompressor
{
    /**
     * Mengompres gambar dengan GD lalu menyimpannya ke direktori publik.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory The directory to store the file (e.g., 'pakets', 'galleries')
     * @return string The path to the stored file
     */
    public function compressAndStoreImage(UploadedFile $file, string $directory): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $directory . '/' . uniqid() . '_' . time() . '.jpg';

        // Membuat resource gambar dari file yang diunggah.
        $image = match ($extension) {
            'png' => imagecreatefrompng($file->getRealPath()),
            'gif' => imagecreatefromgif($file->getRealPath()),
            'webp' => imagecreatefromwebp($file->getRealPath()),
            default => imagecreatefromjpeg($file->getRealPath()),
        };

        if (!$image) {
            // Menyimpan file tanpa kompresi jika GD gagal membacanya.
            return $file->store($directory, 'public');
        }

        // Memperkecil gambar yang lebarnya melebihi 1920 piksel.
        $width = imagesx($image);
        $height = imagesy($image);
        $maxWidth = 1920;

        if ($width > $maxWidth) {
            $newHeight = (int) ($height * ($maxWidth / $width));
            $resized = imagecreatetruecolor($maxWidth, $newHeight);

            // Mempertahankan transparansi PNG selama proses perubahan ukuran.
            if ($extension === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Menyimpan hasil sebagai JPEG dengan kualitas 75 persen.
        ob_start();
        imagejpeg($image, null, 75);
        $compressedImage = ob_get_clean();

        imagedestroy($image);

        Storage::disk('public')->put($filename, $compressedImage);

        return $filename;
    }
}
