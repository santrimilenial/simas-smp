<?php
/**
 * PWA Icon Generator Script
 * 
 * Run this script to generate PNG icons from the SVG source.
 * Required: PHP GD extension with PNG support
 * 
 * Usage: php generate-icons.php
 * 
 * Or manually create PNG icons in the sizes:
 * - 72x72, 96x96, 128x128, 144x144, 152x152, 192x192, 384x384, 512x512
 */

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$iconDir = __DIR__ . '/public/icons';
$sourceColor = '#4f46e5'; // Indigo-600

// Ensure directory exists
if (!is_dir($iconDir)) {
    mkdir($iconDir, 0755, true);
}

foreach ($sizes as $size) {
    $image = imagecreatetruecolor($size, $size);
    
    // Enable alpha blending
    imagealphablending($image, false);
    imagesavealpha($image, true);
    
    // Create colors
    $bgColor = imagecolorallocate($image, 79, 70, 229); // #4f46e5
    $white = imagecolorallocate($image, 255, 255, 255);
    $green = imagecolorallocate($image, 16, 185, 129); // #10b981
    
    // Fill background with rounded corners effect (simplified: just fill)
    imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);
    
    // Draw simple lines to represent a journal/checklist
    $lineHeight = (int)($size * 0.05);
    $margin = (int)($size * 0.2);
    $lineSpacing = (int)($size * 0.14);
    
    for ($i = 0; $i < 4; $i++) {
        $y = $margin + ($i * $lineSpacing);
        $lineWidth = ($i == 2) ? $size * 0.4 : $size * 0.5;
        imagefilledrectangle($image, $margin, $y, $margin + $lineWidth, $y + $lineHeight, $white);
    }
    
    // Draw checkmark circle
    $circleX = (int)($size * 0.74);
    $circleY = (int)($size * 0.62);
    $circleRadius = (int)($size * 0.12);
    imagefilledellipse($image, $circleX, $circleY, $circleRadius * 2, $circleRadius * 2, $green);
    
    // Save the image
    $filename = $iconDir . '/icon-' . $size . 'x' . $size . '.png';
    imagepng($image, $filename);
    imagedestroy($image);
    
    echo "Generated: icon-{$size}x{$size}.png\n";
}

echo "\nAll icons generated successfully!\n";
echo "Location: " . $iconDir . "\n";
