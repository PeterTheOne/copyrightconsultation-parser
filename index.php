<?php

// see: http://webcheatsheet.com/PHP/reading_the_clean_text_from_docx_odt.php
function readZippedXML($archiveFile, $dataFile) {
    // Create new ZIP archive
    $zip = new ZipArchive;
    // Open received archive file
    if (true === $zip->open($archiveFile)) {
        // If done, search for the data file in the archive
        if (($index = $zip->locateName($dataFile)) !== false) {
            // If found, read it to the string
            $data = $zip->getFromIndex($index);
            // Close archive file
            $zip->close();
            // Load XML from a string
            // Skip errors and warnings
            $domDocument = new DOMDocument();
            $domDocument->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            // Return data without XML formatting tags
            return strip_tags($domDocument->saveXML());
        }
        $zip->close();
    }
    // In case of failure return empty string
    return "";
}

function odt2text($filename) {
    return readZippedXML($filename, "content.xml");
}
function getFirstMatchBetween($fileContent, $before, $after) {
    preg_match('/' . $before . '(.*?)' . $after . '/', $fileContent, $match);
    return $match[1];
}

//echo odt2text('docs/original_forms/consultation-document_en.odt');
$rawtext = odt2text('docs/users/*****.odt');


$name = getFirstMatchBetween($rawtext, 'Please identify yourself:Name:', 'In the interests of transparency');
$organisationId = getFirstMatchBetween($rawtext, 'representing the views of your organisation.', 'If your organisation is not registered');
$q1 = getFirstMatchBetween($rawtext, 'journals and newspapers, games, applications and other software\)', 'NO');

echo 'name: ' . $name . '<br />';
echo 'organisation: ' . $organisationId . '<br />';
echo 'organisation: ' . $q1 . '<br />';


echo '<br />';
echo '<br />';
echo '<br />';
echo '<br />';

echo $rawtext;










