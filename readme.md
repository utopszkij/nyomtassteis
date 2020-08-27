# Area Manager wordpress plugin

Hierarchikus terület adat kezelés a woocomerce web áruházban, google map -on történő szinezett megjelenítéssel.

## Tulajdonságok 
- terület körvonal kezelés új termék felvitelekor és módosításakor. 
- termék végelges törlésekor a terület körvonal adatok is törlődnek

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
    poligons:[
        [25.4567, 32.45789],
        [25.4568, 32.45765],
        ...
    ]
}
```

## Telepítés

Szükséges: wordpress (admin jog), AdvancedCustomField plugin, php, mysql 

 1. Hozz létre egy **areamanager** nevü könyvtárat a wordpress "wp-content/plugins" könytárában.
 2. Másold ebbe be ennek a könyvtárnak a teljes tartalmát
 3. Az AdvancedCustomField segitségével bővitsd a wooCommerce termékek adatait egy új boolean mezővel, a mező mnemonikja "is_area" legyen.
 4. A wordpress adminisztrátori oldalon kapcsold be a plugint.
 5. Az admin panel beállítások részében nyissuk meg a plugin beállító paneljét és adjuk meg a kért adatokat.
 

## Licensz

GNU/GPL

## Szerző

Fogler Tibor
tibor.fogler@gmail.com
https://github.com/utopszkij

Sas Tibor


## Forrás program
https://github.com/utopszkij/areamanager


