# DigitalBusinessCard 1.0.0

PlentyONE / plentyShop LTS Plugin fuer digitale Visitenkarten.

## Frontend
- `/visitenkarte/{slug}`
- `/visitenkarte/{slug}/kontakt.vcf`

Ein Prefix wird absichtlich verwendet, damit keine Kollision mit Kategorien, CMS-Seiten oder anderen plentyShop-Routen entsteht.

## Backend
Menue: **Start > Digitale Visitenkarten**

Die Backend-View ist absichtlich als schlanke HTML/JS-View umgesetzt. Sie greift auf die Plugin-CRUD-Routen zu.

## Farben
- Dunkelgruen: `#04302A`
- Petrol/Tuerkis: `#00746F`
- Gold: `#C2994E`
- Anthrazit: `#1E2122`
- Off-White: `#F9F8F4`
- Mittelgrau: `#7D7D7A`
- Weiss: `#FFFFFF`

## Logos
Logo- und Markenbilder werden als URL gepflegt. Fuer die finale optische Version bitte die Original-SVG/PNG-Dateien verwenden.

## Installation
1. Repository/Plugin in PlentyONE einspielen.
2. Dem Plugin-Set des passenden Mandanten zuordnen.
3. Plugin-Set bauen/deployen.
4. Unter **Start > Digitale Visitenkarten** Datensatz anlegen.
5. Frontend unter `/visitenkarte/{slug}` testen.

## Hinweis zur LTS-Kompatibilitaet
Die Struktur folgt dem klassischen PlentyONE Plugin-System (ServiceProvider, RouteServiceProvider, Twig, Plugin-Datenbank und ui.json). Vor Produktivsetzung bitte zuerst im Stage-Plugin-Set testen.


## Version 1.0.2
Backend-Menüeintrag wurde von `start` in den Setup-Systembaum unter `settings` verschoben. Nach dem Deployment ist die Oberfläche unter Einrichtung → Einstellungen → Digitale Visitenkarten vorgesehen.


## 1.0.4
- Backend-Menüroute auf `system/settings` aktualisiert.
- Plugin-Typ auf `general` gesetzt, da das Plugin Frontend- und Backend-Funktionen kombiniert.
