# DigitalBusinessCard 1.0.7

Digitale Visitenkarten für plentyShop LTS.

## Frontend
- Karte: `/visitenkarte/{slug}`
- vCard: `/visitenkarte/{slug}/kontakt.vcf`

## Verwaltung (Fallback ohne PlentyONE Backend-Menürechte)
Version 1.0.7 enthält zusätzlich eine geschützte Verwaltungsseite im Shop:

`/visitenkarten-admin`

Vorher in der Plugin-Konfiguration unter **Verwaltung** einen Verwaltungsschlüssel mit mindestens 12 Zeichen setzen.
Die Verwaltungs-API akzeptiert Änderungen nur mit diesem Schlüssel im HTTP-Header `X-DBC-Admin-Key`.

Damit kann die Kontaktpflege genutzt werden, selbst wenn PlentyONE den `ui.json`-Menüeintrag wegen der Rollen-/Plugin-Sichtbarkeit nicht freigibt.

## Backend-EntryPoint
Der bestehende Backend-EntryPoint `Start -> Digitale Visitenkarten` bleibt enthalten. Wenn PlentyONE ihn später für die Rolle freigibt, kann er ebenfalls verwendet werden.


## Version 1.0.9 – Logos & Icons

- Headerlogo sowie Markenlogo 1 bis 4 werden zentral in der Plugin-Konfiguration unter **Logos & Marken** über PlentyONE-Dateiauswahl (`inputFile`) gepflegt.
- Für jede Marke können zusätzlich Name und Ziel-URL hinterlegt werden.
- Die Frontend-Aktionssymbole wurden durch klare Outline-SVGs im Stil der gelieferten Referenz ersetzt (Kontakt, Telefon, E-Mail, Website, Standort).
- Per-Nutzer-Logo und Marken-JSON bleiben intern nur als Fallback erhalten.


## 1.0.9
- Mobile Frontend-Abstände und Größen näher an die Referenz angepasst.
- Aktionskarten mit warmem Off-White, feiner Kontur und angepassten Radien.
- Logo-, Adress-, Marken- und Footer-Abstände für Smartphone-Darstellung verfeinert.


## Version 1.0.10
- Telefon-, E-Mail- und Website-Icons auf die bereitgestellten SVG-Formen umgestellt.
- Diese drei Icons werden mit weißen Konturen dargestellt.
- Standort-Icon verwendet die bereitgestellte SVG-Form in Gold.
- Standort-Icon hat nun dieselbe sichtbare Icon-Groesse wie Telefon, E-Mail und Website.
