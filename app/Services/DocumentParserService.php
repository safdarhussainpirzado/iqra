<?php

namespace App\Services;

use Exception;

class DocumentParserService
{
    /**
     * Parse document and return extracted text.
     *
     * @param string $filePath
     * @param string $extension
     * @return string
     * @throws Exception
     */
    public function parse(string $filePath, string $extension): string
    {
        $extension = strtolower(ltrim($extension, '.'));

        switch ($extension) {
            case 'txt':
                return $this->parseTxt($filePath);
            case 'csv':
                return $this->parseCsv($filePath);
            case 'json':
                return $this->parseJson($filePath);
            case 'html':
                return $this->parseHtml($filePath);
            case 'docx':
                return $this->parseDocx($filePath);
            case 'xlsx':
                return $this->parseXlsx($filePath);
            case 'pdf':
                return $this->parsePdf($filePath);
            default:
                throw new Exception("Unsupported file format: {$extension}");
        }
    }

    private function parseTxt(string $filePath): string
    {
        return file_get_contents($filePath);
    }

    private function parseCsv(string $filePath): string
    {
        $text = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $text[] = implode(' ', array_filter($data));
            }
            fclose($handle);
        }
        return implode("\n", $text);
    }

    private function parseJson(string $filePath): string
    {
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON file.");
        }
        return is_array($data) ? json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $content;
    }

    private function parseHtml(string $filePath): string
    {
        $html = file_get_contents($filePath);
        return strip_tags($html);
    }

    private function parseDocx(string $filePath): string
    {
        $stripedText = '';
        $zip = new \ZipArchive();

        if ($zip->open($filePath) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $xml = $zip->getFromIndex($index);
                $dom = new \DOMDocument();
                $dom->loadXML($xml, LIBXML_NOENT | LIBXML_XHTML | LIBXML_NOERROR | LIBXML_NOWARNING);
                $stripedText = strip_tags($dom->saveXML());
            }
            $zip->close();
        } else {
            throw new Exception("Failed to open DOCX file.");
        }

        return html_entity_decode($stripedText, ENT_QUOTES, 'UTF-8');
    }

    private function parseXlsx(string $filePath): string
    {
        $stripedText = [];
        $zip = new \ZipArchive();

        if ($zip->open($filePath) === true) {
            // Read shared strings
            if (($index = $zip->locateName('xl/sharedStrings.xml')) !== false) {
                $xml = $zip->getFromIndex($index);
                $dom = new \DOMDocument();
                $dom->loadXML($xml, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING);
                $xpath = new \DOMXPath($dom);
                $xpath->registerNamespace('ns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                $entries = $xpath->query('//ns:t');
                foreach ($entries as $entry) {
                    $stripedText[] = $entry->textContent;
                }
            }
            $zip->close();
        } else {
            throw new Exception("Failed to open XLSX file.");
        }

        return implode(' ', $stripedText);
    }

    private function parsePdf(string $filePath): string
    {
        // Try parsing using pdftotext binary
        $output = [];
        $returnVar = 0;
        exec("pdftotext " . escapeshellarg($filePath) . " - 2>&1", $output, $returnVar);

        if ($returnVar === 0) {
            return implode("\n", $output);
        }

        // If pdftotext fails, return empty to trigger OCR downstream
        return '';
    }
}
