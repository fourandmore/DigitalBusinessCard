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
