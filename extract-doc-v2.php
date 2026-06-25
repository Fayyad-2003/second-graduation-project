<?php

/**
 * Extract and analyze Word document content with proper UTF-8 handling
 */

header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Function to extract text from document.xml with structure
function extractDocumentStructure($xmlContent) {
    $xml = simplexml_load_string($xmlContent);
    if ($xml === false) {
        return ["error" => "Failed to parse XML"];
    }
    
    $result = [
        'paragraphs' => [],
        'headings' => [],
        'images' => [],
        'tables' => [],
    ];
    
    $namespaces = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('w', $namespaces['w']);
    
    $paragraphs = $xml->xpath('//w:p');
    
    foreach ($paragraphs as $pIndex => $p) {
        // Get paragraph style
        $pStyle = $p->xpath('.//w:pPr/w:pStyle');
        $styleName = !empty($pStyle) ? (string)$pStyle[0]->attributes($namespaces['w'])->val : 'Normal';
        
        // Extract text
        $pText = "";
        $textNodes = $p->xpath('.//w:t');
        
        foreach ($textNodes as $t) {
            $pText .= (string)$t;
        }
        
        $paragraphData = [
            'index' => $pIndex,
            'style' => $styleName,
            'text' => $pText,
        ];
        
        $result['paragraphs'][] = $paragraphData;
        
        // Identify headings
        if (str_starts_with(strtolower($styleName), 'heading')) {
            $result['headings'][] = $paragraphData;
        }
    }
    
    // Check for images
    $blips = $xml->xpath('//a:blip');
    if (!empty($blips)) {
        $result['images'] = count($blips);
    }
    
    // Check for tables
    $tables = $xml->xpath('//w:tbl');
    if (!empty($tables)) {
        $result['tables'] = count($tables);
    }
    
    return $result;
}

// Read the document
$docXmlPath = __DIR__ . '/temp-docx/word/document.xml';
$docXmlContent = file_get_contents($docXmlPath);

if ($docXmlContent === false) {
    die("Could not read document.xml\n");
}

// Extract structure
$docStructure = extractDocumentStructure($docXmlContent);

// Build a clean text version with structure
$cleanText = "";
foreach ($docStructure['paragraphs'] as $para) {
    if (!empty(trim($para['text']))) {
        if (str_starts_with(strtolower($para['style']), 'heading')) {
            $cleanText .= "\n" . str_repeat("=", 60) . "\n";
            $cleanText .= "[{$para['style']}] " . $para['text'] . "\n";
            $cleanText .= str_repeat("=", 60) . "\n";
        } else {
            $cleanText .= $para['text'] . "\n";
        }
    }
}

// Save to file with UTF-8 BOM for proper Arabic display
file_put_contents(__DIR__ . '/document-content-utf8.txt', "\xEF\xBB\xBF" . $cleanText);

// Also extract just headings for quick overview
$headingsText = "";
foreach ($docStructure['headings'] as $heading) {
    $headingsText .= "{$heading['style']}: {$heading['text']}\n";
}
file_put_contents(__DIR__ . '/document-headings.txt', "\xEF\xBB\xBF" . $headingsText);

// Output summary
echo "=== Document Analysis ===\n";
echo "Total paragraphs: " . count($docStructure['paragraphs']) . "\n";
echo "Total headings: " . count($docStructure['headings']) . "\n";
echo "Images: " . (isset($docStructure['images']) ? $docStructure['images'] : 0) . "\n";
echo "Tables: " . (isset($docStructure['tables']) ? $docStructure['tables'] : 0) . "\n";
echo "\n=== Headings ===\n";
echo $headingsText;
echo "\nFull content saved to: document-content-utf8.txt\n";
echo "Headings saved to: document-headings.txt\n";
