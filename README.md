
[![Build Status](https://travis-ci.org/perryflynn/PerrysTags.svg?branch=master)](https://travis-ci.org/perryflynn/PerrysTags)

PerrysTags ist eine PHP Bibliothek welche es ermöglichen soll, Daten nach Tags zu filtern.
Sie ist so allgemein gehalten, dass sie sowohl mit Datenbanken als auch mit Datenstrukturen
welche zur Programmlaufzeit erzeugt werden zurecht kommt.

## Status

- In Entwicklung, erste lauffähige Version
- Docs nur in deutsch, muss noch in English umgesetzt werden

## Abhängigkeiten

Die Bibliothek nutzt PerrysLambda, eine implementierung der C# Lambda Expressions
für PHP. PerrysLambda erlaubt sehr einfach das Filtern und Verarbeiten von einfachen
und komplexen Datenstrukturen.

Folgende Aufgaben übernimmt PerrysLambda:

- Umwandeln der Tag strings in die entsprechenden Objekte
- Durchsuchen der Tag Collections
- Selektieren von Daten
- Serialisieren der Tag Objekte in die ursprünglichen strings

## Workflow MySQL Datenbank

- Alle verfügbaren Tags aus der MySQL Datenbank mit einem SELECT Statement auslesen.
  Zum Beispiel `SELECT tagname FROM tags GROUP BY tagname WHERE uid IN (SELECT tag_uid FROM content)`
- Tags in den Objekttyp `TagCollection` importieren
- Nach Tags suchen btw filtern
- Liste vollständiger Tags in einer weiteren SQL Abfrage zum filtern der eigentlichen
  Daten verwenden

Siehe hierzu `cliexample.php`.

## Code Beispiel

```php
<?php

// From cliexample.php

require_once __DIR__."/vendor/autoload.php";

use PerrysTags\Tag;
use PerrysTags\TagCollection;

// Example taglist, could come from sql statement
$taglist = array(
    'language:english',
    'language:german',
    'country:germany',
    'country:united states of america',
    'country:hungary',
    'country:italy',
    'country:spain',
    'country:netherlands',
    'color:green',
    'color:red',
    'color:yellow',
    'color:gray',
    'color:blue',
    'color:white',
    'color:light orange',
    'condition:slightly used',
    'condition:used',
    'condition:new',
    'condition:broken',
);

// Create tag collection
$collection = new TagCollection();

// Import tags
foreach($taglist as $tag)
{
    $collection->add($tag);
}

// Prints result of a search
// Supports Regex
var_dump($collection->tagSearch('country:"^united states" color:"e$" condition:used')->serialize());
```

Ergebnis:

```
array(5) {
  [0] =>
  string(32) "country:united states of america"
  [1] =>
  string(10) "color:blue"
  [2] =>
  string(11) "color:white"
  [3] =>
  string(18) "color:light orange"
  [4] =>
  string(14) "condition:used"
}
```

Siehe Unit Tests für detailiertere Beispiele.
