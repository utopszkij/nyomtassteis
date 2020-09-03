# Area Manager wordpress plugin

Hierarchikus terület adat kezelés a woocomerce web áruházban, google map -on történő szinezett megjelenítéssel.

## Készültség: béta teszt. 
Egyenlőre csak az admin oldal müködik, a hívható funkciók még nem.

## Tulajdonságok 
- terület adatok és körvonal kezelés új termék felvitelekor és módosításakor. 
- termék végelges törlésekor a terület adatok és körvonal adatok is törlődnek

## Hívható funkciók

### Példák

**A 102 -es azonosítoju területet jeleníti meg google map -on, a 102 azonosítóju terület #d0d0d0 szinnel lesz szinezve.**
Ay template php megfelelő helyére illeszd be:
```
<div class="areaContainer">
<?php do_action('areaManager_show', 102, '#d0d0d0); ?>
</div>
```


**A 102 -es terület információinak lekérése**
```
$info = areaManaget_getInfo(102);

Result Json string:  
{   id:102,
    title:"terület megnevezése",
    description:"terület leírása",
    popuplation:1234,
    place:23456,
    poligons:[
        [25.4567, 32.45789],
        [25.4568, 32.45765],
        ...
    ]
}
```
A plugin által megjelenített képernyő részletek alapértelmezetten a 

"plugindir/htmls" 

könyvtárban vannak. Ha szükséges akkor az aktuális template könyvtárba is létre lehet hozni azonos nevü fájlokat a 

"templatesdir/areamanager/html" 

könyvtárba, ha ezek léteznek akkor a plugin ezeket használja.

## unittest

linux konsolon:
```
	cd <plugin dir>
	phpunit tests
```

## Telepítés

Szükséges: wordpress 4.4+ (admin jog), AdvancedCustomField plugin, php, mysql 

 1. Hozz létre egy **areamanager** nevü könyvtárat a wordpress "wp-content/plugins" könytárában.
 2. Másold ebbe be ennek a könyvtárnak a teljes tartalmát
 4. A wordpress adminisztrátori oldalon kapcsold be a plugint.
 5. Az admin panel beállítások részében nyissuk meg a plugin beállító paneljét és adjuk meg a kért adatokat.
 

 Szükség van google API_key -re
 lásd: https://developers.google.com/maps/documentation/javascript/get-api-key

## Beállítás
Az admin oldalon a "Beállítások" alatt található a plugin beállító panel linkje.


## Licensz

GNU/GPL

## Szerző

Fogler Tibor
tibor.fogler@gmail.com
https://github.com/utopszkij

Sas Tibor


## Forrás program
https://github.com/utopszkij/areamanager


