# MQTT

## General


### Specification
https://mqtt.org/mqtt-specification/

- v3.1: ~60 Seiten
- v5: ~130 Sseiten

### Software
https://mqtt.org/software/

- viele Broker
- viele Clients
- User Interfaces
  - MQTT Explorer
  - MQTTAnalyzer (iOS / MacOS)

## Demo 1

## Demo 2

# Box

Phar Archive bauen ist nicht sooo simple, da einiges zu tun ist.

###  Beispiel: Composer
Mit Box kann man die composer.phar aus den Sourcen bauen.
In der dazu nötigen Configuration ist zu sehen, was alles gemacht werden muss:
https://github.com/box-project/box2/wiki/Composer

Mit `directories` werden ganze Ordner rekursive hinzugefügt.
Mit `finder` werden andere Ordner gefiltert (Name Pattern, Exclude) hinzugefügt.
Mit `files` werden einzelne Dateien hinzugefügt.


### Beispiel: Manuell
https://github.com/CeusMedia/Hymn/blob/1.0.x/build/create.php

````
$rootPath	= dirname( __DIR__ );
$pathsToAdd	= ['locales', 'templates'];
$pharFlags	= FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME;

$archive	= new Phar( $rootPath.'/target.phar', $pharFlags, 'target.phar' );
$archive->startBuffering();
$archive->setStub( file_get_contents( __DIR__.'/'.'stub.php' ) );
$archive->buildFromDirectory( $rootPath.'/build/classes/', '$(.*)\.php$' );
$archive->addFromString( 'main.php, file_get_contents( __DIR__.'/main.php' ) );

foreach( $pathsToAdd as $pathToAdd ){
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $rootPath.$pathToAdd, RecursiveDirectoryIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);
	foreach( $iterator as $entry )
		if( !$entry->isDir() )
			$archive->addFile( $entry->getPathname() );
}

$archive->compressFiles( Phar::GZ );
$archive->stopBuffering();
````



## Box-Project / Humbug
- Version 4.6 for PHP ^8.2
- Version 4.5.1 for PHP ^8.1
- Site: https://box-project.github.io/box/
- GitHub: https://github.com/box-project/box

### Installation
#### Linux
````
composer global require humbug/box
````
hat bei mir nicht funktioniert, dafür die Installation per GitHub:
````
wget -O box.phar "https://github.com/box-project/box/releases/download/4.5.1/box.phar"
wget -O box.phar.asc "https://github.com/box-project/box/releases/download/4.5.1/box.phar.asc"
gpg --verify box.phar.asc box.phar
gpg --keyserver hkps://keys.openpgp.org --recv-keys 41539BBD4020945DB378F98B2DF45277AEF09A2F
rm box.phar.asc
chmod +x box.phar
mv box.phar box-4.5.1.phar
````

#### MacOS
````
brew tap box-project/box
brew install box
````
### Usage

- Doc Configuration: https://github.com/box-project/box/blob/main/doc/configuration.md#configuration

Bauen das Archivs in dieser Version mit `compile`:
````
./box-4.5.1.phar compile -vvv
````
scheitert an Prüfung der Composer Version :(

## Box2
- !! this is the old original
- Site: https://box-project.github.io/box2/
- GitHub: https://github.com/box-project/box2
- Mature: 4 Jahre alt :D

### Installation
````
curl -LSs https://box-project.github.io/box2/installer.php | php
````
### Usage

Bauen das Archivs in dieser Version mit `build`:
````
./box-2.7.5.phar build -v && chmod +x client.phar
````
Dann sendet `client.phar MyTestMessage_1` eine Nachricht in das MQTT Topic `test`.

Archive können entpackt werden:
````
./box-2.7.5.phar extract client.phar
````

