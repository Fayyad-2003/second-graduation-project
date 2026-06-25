<?php

/**
 * Extract and analyze Word document content
 */

// Function to extract text from document.xml
function extractTextFromDocXml($xmlContent) {
    $xml = simplexml_load_string($xmlContent);
    if ($xml === false) {
        return "Failed to parse XML";
    }
    
    $text = "";
    $namespaces = $xml->getNamespaces(true);
    $xml->registerXPathNamespace('w', $namespaces['w']);
    
    $paragraphs = $xml->xpath('//w:p');
    
    foreach ($paragraphs as $p) {
        $pText = "";
        $textNodes = $p->xpath('.//w:t');
        
        foreach ($textNodes as $t) {
            $pText .= (string)$t;
        }
        
        if (!empty(trim($pText))) {
            $text .= $pText . "\n";
        }
    }
    
    return $text;
}

// Read the document
$docXmlPath = __DIR__ . '/temp-docx/word/document.xml';
$docXmlContent = file_get_contents($docXmlPath);

if ($docXmlContent === false) {
    die("Could not read document.xml\n");
}

// Extract text
$extractedText = extractTextFromDocXml($docXmlContent);

// Save to file for easy reading
file_put_contents(__DIR__ . '/document-content.txt', $extractedText);

echo "Document extracted successfully!\n";
echo "Total characters: " . strlen($extractedText) . "\n";
echo "Total lines: " . substr_count($extractedText, "\n") . "\n";
echo "\nFirst 2000 characters:\n";
echo "----------------------------------------\n";
echo substr($extractedText, 0, 2000) . "\n";
echo "----------------------------------------\n";
echo "\nFull content saved to: document-content.txt\n";
