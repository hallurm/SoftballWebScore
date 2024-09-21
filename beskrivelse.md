# Live Score-opdatering for Egedal Softball

## Oversigt
Dette dokument beskriver, hvordan man kan implementere en live score-opdatering funktion for softballkampe på hjemmesiden https://egedalsoftball.dk ved hjælp af eksisterende temaer og plugins.

## Komponenter
1. X Pro tema fra theme.co
2. Quform plugin
3. Advanced Custom Fields (ACF) Pro plugin

## Implementeringsplan

### 1. Opret Custom Post Type
- Brug ACF Pro til at oprette en ny Custom Post Type kaldet "Kampe".
- Tilføj custom fields for:
  - Hjemmehold
  - Udehold
  - Aktuel inning
  - Score for hjemmehold
  - Score for udehold
  - Kamp status (ikke startet, i gang, afsluttet)

### 2. Opret formular med Quform
- Lav en formular til score-opdatering med felter for:
  - Vælg kamp (dropdown med aktive kampe)
  - Opdater inning
  - Opdater score for hjemmehold
  - Opdater score for udehold
  - Opdater kamp status

### 3. Backend-logik
- Opret en custom endpoint i WordPress ved hjælp af WordPress REST API.
- Implementer logik til at modtage data fra Quform og opdatere den relevante "Kamp" post.

### 4. Frontend-visning
- Brug X Pro temaets indbyggede funktioner til at oprette en template for visning af live scores.
- Implementer AJAX-kald til at hente opdaterede data fra backend hvert 30. sekund.

### 5. Mobilvenlig opdateringsside
- Opret en dedikeret side til mobilopdatering ved hjælp af X Pro's page builder.
- Indlejr Quform-formularen på denne side.
- Tilføj adgangsbegrænsning til denne side, så kun autoriserede brugere kan opdatere scores.

### 6. Sikkerhed
- Implementer brugerroller og -tilladelser for at sikre, at kun godkendte personer kan opdatere scores.
- Tilføj CAPTCHA eller lignende sikkerhedsforanstaltninger til opdateringsformularen for at forhindre misbrug.

## Konklusion
Denne løsning udnytter de eksisterende plugins og temaer til at skabe en brugervenlig og effektiv metode til live score-opdatering. Den er skalerbar og kan nemt tilpasses fremtidige behov.
