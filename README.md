# DigitalBusinessCard 1.0.5

PlentyONE / plentyShop LTS Plugin für digitale Visitenkarten.

## Backend-Menü

Die Backend-UI wird gemäß PlentyONE `ui.json` unter folgendem Systembaum registriert:

**Einrichtung → Einstellungen → Digitale Visitenkarten**

Verwendete `ui.json`-Werte:

- `menu`: `settings`
- `urlKey`: `digitale-visitenkarten`
- `entryPoint`: `index.html`

Die resultierende Backend-Route ist sinngemäß `/settings/digitale-visitenkarten` innerhalb der PlentyONE-Backend-Routingstruktur.

## Wichtig

Das Plugin-Set, in dem dieses Plugin bereitgestellt wurde, muss dem angemeldeten Benutzer auch als **Backend-Plugin-Set** zugeordnet sein. Eine reine Mandanten-/Webshop-Zuordnung reicht für die Backend-UI nicht.

## Frontend

Eine aktive Karte mit dem Slug `maik` ist erreichbar unter:

`/visitenkarte/maik`

Die vCard unter:

`/visitenkarte/maik/kontakt.vcf`

## Version 1.0.5

- Plugin-Typ auf `general` gesetzt, da das Plugin Backend-UI und Frontend-Funktionen kombiniert.
- Backend-Menü strikt nach offizieller PlentyONE-Dokumentation mit `menu: settings` registriert.
- Vorherige Fixes für den PlentyONE-Codecheck (`esc`, dynamische Property-Namen) bleiben enthalten.
