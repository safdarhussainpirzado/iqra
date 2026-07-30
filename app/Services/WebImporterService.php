<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebImporterService
{
    /**
     * Fetch a URL and parse its readable text content.
     *
     * @param string $url
     * @return string
     * @throws Exception
     */
    public function import(string $url): string
    {
        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid URL format.");
        }

        try {
            // Request the page
            $response = Http::withHeaders([
                'User-Agent' => 'IQRA Web Crawler/1.0',
            ])->timeout(15)->get($url);

            if ($response->failed()) {
                throw new Exception("Failed to fetch webpage. HTTP Status: " . $response->status());
            }

            $html = $response->body();

            // Extract readable text from HTML body
            $cleanText = $this->extractReadableText($html);

            Log::info("Successfully crawled URL: {$url}");

            return $cleanText;

        } catch (Exception $e) {
            Log::error("Web scraper failed for URL [{$url}]: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clean HTML and retrieve core text.
     *
     * @param string $html
     * @return string
     */
    private function extractReadableText(string $html): string
    {
        // 1. Remove scripts, styles, and headers
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
        $html = preg_replace('/<header\b[^>]*>(.*?)<\/header>/is', '', $html);
        $html = preg_replace('/<footer\b[^>]*>(.*?)<\/footer>/is', '', $html);
        $html = preg_replace('/<nav\b[^>]*>(.*?)<\/nav>/is', '', $html);

        // 2. Strip tags
        $text = strip_tags($html);

        // 3. Normalize spaces
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\n\s*\n+/', "\n\n", $text);

        return trim($text);
    }
}
