<?php
/**
 * Certification logos for the marquee, each with the certificates shown on hover.
 * Standard and company names stay as issued; descriptive labels (label_key) and
 * "issued by" come from the language file. Groups without a logo render their
 * name as a wordmark. A group with a 'company' is that company's own portfolio,
 * so its rows name only the issuer.
 */
$escc  = 'Egyptian Swiss Company for Concentrates';
$njoro  = 'Njoro Canning Factory (Kenya) Limited';
$zwft   = 'Zhejiang Willing Foreign Trading Co., Ltd.';

return [
    ['name' => 'ISO', 'logo' => 'ISO.webp', 'certs' => [
        ['label' => 'ISO 22000:2018', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 9001:2015', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 14001:2015', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 45001:2018', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 9001:2015', 'company' => $zwft, 'issuer' => 'WIT Assessment'],
    ]],
    ['name' => 'FSSC 22000', 'certs' => [
        ['label_key' => 'fssc_v6', 'company' => $escc, 'issuer' => 'LSQA'],
    ]],
    ['name' => 'HACCP', 'logo' => 'HACCP.webp', 'certs' => [
        ['label_key' => 'haccp', 'company' => $zwft, 'issuer' => 'China Quality Certification Centre (CQC)'],
    ]],
    ['name' => 'Kosher', 'certs' => [
        ['label' => 'Kosher', 'company' => $escc, 'issuer' => 'Kosher Certification Jerusalem (KCJ)'],
    ]],
    ['name' => 'US FDA', 'logo' => 'US_FDA.webp', 'certs' => [
        ['label_key' => 'fda_facility', 'company' => $escc, 'issuer' => 'Registrar Corp'],
        ['label_key' => 'fda_fce_sid', 'company' => $escc, 'issuer' => 'Registrar Corp'],
    ]],
    ['name' => 'KEBS', 'logo' => 'KEBS.webp', 'certs' => [
        ['label_key' => 'kebs', 'company' => $njoro],
    ]],
    ['name' => 'Njoro Canning', 'logo' => 'Njoro_canning.webp', 'company' => $njoro, 'certs' => [
        ['label_key' => 'kebs'],
    ]],
];
