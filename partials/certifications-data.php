<?php
/**
 * Certification logos for the marquee, each with the certificates shown on hover.
 * Standard and company names stay as issued; descriptive labels (label_key) and
 * "issued by" come from the language file. Groups without a logo render their
 * name as a wordmark. A group with a 'company' is that company's own portfolio,
 * so its rows name only the issuer.
 */
$uefcon = 'UEFCON';
$escc   = 'Egyptian Swiss Company for Concentrates';
$njoro  = 'Njoro Canning Factory (Kenya) Limited';
$zwft   = 'Zhejiang Willing Foreign Trading Co., Ltd.';

return [
    ['name' => 'ISO', 'logo' => 'ISO.webp', 'certs' => [
        ['label' => 'ISO 22000:2018 (FSMS)', 'company' => $uefcon, 'issuer' => 'PCI Cert'],
        ['label' => 'ISO 22000:2018', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 9001:2015', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 14001:2015', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 45001:2018', 'company' => $escc, 'issuer' => 'LSQA'],
        ['label' => 'ISO 9001:2015', 'company' => $zwft, 'issuer' => 'WIT Assessment'],
    ]],
    ['name' => 'FSSC 22000', 'certs' => [
        ['label_key' => 'fssc_v6', 'company' => $uefcon, 'issuer' => 'PQS'],
        ['label_key' => 'fssc_v6', 'company' => $escc, 'issuer' => 'LSQA'],
    ]],
    ['name' => 'HACCP', 'logo' => 'HACCP.webp', 'certs' => [
        ['label_key' => 'haccp', 'company' => $zwft, 'issuer' => 'China Quality Certification Centre (CQC)'],
    ]],
    ['name' => 'Halal', 'logo' => 'halal.webp', 'certs' => [
        ['label_key' => 'halal_veg', 'company' => $uefcon, 'issuer' => 'SAGACI'],
        ['label_key' => 'halal_egypt', 'company' => $escc, 'issuer' => 'EOS'],
    ]],
    ['name' => 'Kosher', 'certs' => [
        ['label' => 'Kosher', 'company' => $escc, 'issuer' => 'Kosher Certification Jerusalem (KCJ)'],
        ['label' => 'Kosher', 'company' => $uefcon, 'issuer' => 'KosherCheck'],
    ]],
    ['name' => 'US FDA', 'logo' => 'US_FDA.webp', 'certs' => [
        ['label_key' => 'fda_facility', 'company' => $escc, 'issuer' => 'Registrar Corp'],
        ['label_key' => 'fda_fce_sid', 'company' => $escc, 'issuer' => 'Registrar Corp'],
    ]],
    ['name' => 'KEBS', 'logo' => 'KEBS.webp', 'certs' => [
        ['label_key' => 'kebs', 'company' => $njoro],
    ]],
    ['name' => 'UEFCON', 'logo' => 'UEFCON.webp', 'company' => $uefcon, 'certs' => [
        ['label' => 'ISO 22000:2018 (FSMS)', 'issuer' => 'PCI Cert'],
        ['label_key' => 'fssc_v6', 'issuer' => 'PQS'],
        ['label_key' => 'halal_veg', 'issuer' => 'SAGACI'],
        ['label' => 'Kosher', 'issuer' => 'KosherCheck'],
    ]],
    ['name' => 'Njoro Canning', 'logo' => 'Njoro_canning.webp', 'company' => $njoro, 'certs' => [
        ['label_key' => 'kebs'],
    ]],
];
