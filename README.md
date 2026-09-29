# DigitalBusinessCard 1.0.12

Digitale Visitenkarten für plentyShop LTS / Ceres.

## Sicherheit der Verwaltung

Version 1.0.12 verschärft den Schutz der alternativen Frontend-Verwaltung:

- Verwaltungsschlüssel: mindestens 24 Zeichen empfohlen/erforderlich.
- Individueller Verwaltungspfad in der Plugin-Konfiguration.
- Admin-URL: `/visitenkarten-admin/<verwaltungspfad>`.
- Nach erfolgreicher Anmeldung wird ein zufälliges, serverseitig gespeichertes Sitzungstoken verwendet. Der Verwaltungsschlüssel wird nicht in `sessionStorage` gespeichert.
- Sitzung wird nach 15 Minuten Inaktivität im Browser gesperrt; serverseitige Sessions laufen nach 30 Minuten ohne Aktivität ab.
- Nach 8 fehlerhaften Anmeldeversuchen wird die jeweilige Client-Adresse für 15 Minuten gesperrt.
- Security Header: CSP, X-Frame-Options DENY, nosniff, no-referrer, Permissions-Policy und noindex.
- Admin-Seite und API-Antworten werden nicht gecacht.

Hinweis: Der Verwaltungspfad ist eine zusätzliche Hürde, ersetzt aber nicht den starken Verwaltungsschlüssel. Die IP-Erkennung für das Rate-Limit basiert auf üblichen Proxy-Headern, die PlentyONE/CDN vorgelagert übermitteln.

## Öffentliche Karten

`/visitenkarte/<slug>`

vCard:

`/visitenkarte/<slug>/kontakt.vcf`
